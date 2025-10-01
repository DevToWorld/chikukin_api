<?php

namespace App\Services\Mailer;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ResendMailer
{
    /** @var \Resend\Client */
    protected $client;

    public function __construct(?string $apiKey = null)
    {
        $apiKey = $apiKey ?: env('RESEND_API_KEY');
        $this->client = \Resend::client($apiKey);
    }

    /**
     * Send a simple email via Resend API.
     * $payload keys: to(array|string), subject, html?, text?, from.address?, from.name?, attachments?
     */
    public function send(array $payload): void
    {
        $fromAddress = $payload['from']['address'] ?? env('MAIL_FROM_ADDRESS');
        $fromName = $payload['from']['name'] ?? env('MAIL_FROM_NAME');
        $from = $fromName ? sprintf('%s <%s>', $fromName, $fromAddress) : $fromAddress;

        $to = $payload['to'] ?? [];
        if (!is_array($to)) $to = [$to];

        $data = [
            'from' => $from,
            'to' => $to,
            'subject' => $payload['subject'] ?? '(no subject)',
        ];

        if (!empty($payload['body_html'])) $data['html'] = (string) $payload['body_html'];
        if (!empty($payload['body_text'])) $data['text'] = (string) $payload['body_text'];

        // Attachments mapping: supports path or raw data (base64 in payload)
        $attachments = [];
        foreach (($payload['attachments'] ?? []) as $att) {
            if (!empty($att['path'])) {
                $path = $att['path'];
                $name = $att['as'] ?? basename($path);
                if (str_starts_with($path, 'storage://')) {
                    // Custom scheme: storage://<disk>/<path>
                    $rel = substr($path, strlen('storage://'));
                    [$disk, $relPath] = explode('/', $rel, 2);
                    $content = Storage::disk($disk)->get($relPath);
                } else {
                    $content = @file_get_contents($path);
                }
                if ($content !== false) {
                    $attachments[] = [
                        'filename' => $name,
                        'content' => base64_encode($content),
                    ];
                }
            } elseif (!empty($att['data']) && !empty($att['as'])) {
                $attachments[] = [
                    'filename' => $att['as'],
                    'content' => $att['data'], // expect base64
                ];
            }
        }
        if (!empty($attachments)) $data['attachments'] = $attachments;

        try {
            $this->client->emails->send($data);
        } catch (\Throwable $e) {
            Log::error('Resend send failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
