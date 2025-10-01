<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SimpleMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class EmailImmediateController extends Controller
{
    /**
     * Send a simple email immediately.
     * POST /api/admin/emails/send-simple
     */
    public function send(Request $request)
    {
        Gate::authorize('manage-mails');

        $data = $request->validate([
            'to' => 'required', // string email or array of emails
            'subject' => 'required|string|max:255',
            'body_html' => 'nullable|string',
            'body_text' => 'nullable|string',
            'from.address' => 'nullable|email',
            'from.name' => 'nullable|string|max:255',
            'cc' => 'nullable|array',
            'cc.*' => 'email',
            'bcc' => 'nullable|array',
            'bcc.*' => 'email',
            'attachments' => 'nullable|array',
        ]);

        // Decide mailer (fallback to log if SMTP is not configured)
        $mailer = config('mail.default', 'smtp');
        $smtpHost = config('mail.mailers.smtp.host');
        $smtpDsn  = config('mail.mailers.smtp.url');
        $dryRun = filter_var(env('MAIL_DRY_RUN', false), FILTER_VALIDATE_BOOLEAN);
        if ($mailer === 'resend') {
            // Use Resend API path
            if ($dryRun) {
                return response()->json(['success' => true, 'message' => '[MAIL_DRY_RUN] Resend skipped', 'mailer' => 'resend']);
            }
            $payload = $data;
            // Normalize attachments: support storage disk references
            $attachments = [];
            foreach (($data['attachments'] ?? []) as $att) {
                if (!empty($att['path'])) {
                    $attachments[] = ['path' => $att['path'], 'as' => $att['as'] ?? null];
                } elseif (!empty($att['data']) && !empty($att['as'])) {
                    $attachments[] = ['data' => $att['data'], 'as' => $att['as']];
                }
            }
            $payload['attachments'] = $attachments;
            (new \App\Services\Mailer\ResendMailer())->send($payload);
            return response()->json(['success' => true, 'message' => 'Email sent via Resend', 'mailer' => 'resend']);
        }
        // Fallback to log only when SMTP is clearly unconfigured AND no DSN is provided
        if ($dryRun || ($mailer === 'smtp' && empty($smtpDsn) && (empty($smtpHost) || $smtpHost === 'smtp.mailgun.org'))) {
            // In dry-run or when SMTP looks unconfigured, use log mailer to avoid hanging
            $mailer = 'log';
        }

        $mailUrl = (string) env('MAIL_URL', '');
        $useGmailApi = str_starts_with($mailUrl, 'gmail+api://');
        if ($useGmailApi) {
            // Bypass Laravel Mailer entirely when DSN is gmail+api
            (new \App\Services\Mailer\GmailApiMailer())->send($data);
        } else {
            $mailable = new SimpleMail($data);
            $to = is_array($data['to']) ? $data['to'] : [$data['to']];
            $pending = Mail::mailer($mailer)->to($to);
            if (!empty($data['from']['address'])) {
                $pending->alwaysFrom($data['from']['address'], $data['from']['name'] ?? null);
            }
            if (!empty($data['cc'])) $pending->cc($data['cc']);
            if (!empty($data['bcc'])) $pending->bcc($data['bcc']);
            $pending->send($mailable);
        }

        return response()->json([
            'success' => true,
            'message' => 'Email queued for sending',
            'mailer' => $mailer,
        ]);
    }
}
