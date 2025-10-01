<?php

namespace App\Jobs;

use App\Models\EmailCampaign;
use App\Models\EmailRecipient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 120;

    public function __construct(public int $campaignId) {}

    public function handle(): void
    {
        $campaign = EmailCampaign::find($this->campaignId);
        if (!$campaign) return;

        $campaign->update(['status' => 'sending']);

        // If there are no pending recipients, immediately mark as sent to avoid being stuck in "sending"
        $pendingCount = EmailRecipient::where('campaign_id', $campaign->id)->where('status', 'pending')->count();
        if ($pendingCount === 0) {
            $campaign->update(['status' => 'sent']);
            return;
        }

        EmailRecipient::where('campaign_id', $campaign->id)
            ->where('status', 'pending')
            ->orderBy('id')
            ->chunkById(500, function ($recipients) {
                foreach ($recipients as $recipient) {
                    SendCampaignEmailToRecipient::dispatch($recipient->id);
                }
            });

        // Do not mark as sent here; per-recipient jobs will update statuses.
    }
}
