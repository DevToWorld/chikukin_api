<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SimpleMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $payload;

    /**
     * Create a new message instance.
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function build()
    {
        $mail = $this->subject($this->payload['subject'] ?? '(no subject)');

        // Body rendering
        $html = $this->payload['body_html'] ?? null;
        $text = $this->payload['body_text'] ?? null;

        if ($html) {
            $mail->view('emails.simple', ['html' => $html, 'text' => $text]);
        } elseif ($text) {
            $mail->text('emails.simple_text', ['text' => $text]);
        } else {
            $mail->text('emails.simple_text', ['text' => '(empty)']);
        }

        // Attachments (optional)
        $attachments = $this->payload['attachments'] ?? [];
        foreach ($attachments as $att) {
            // Supported formats:
            // 1) ['path' => storage_path('app/public/xxx.pdf'), 'as' => 'xxx.pdf', 'mime' => 'application/pdf']
            // 2) ['data' => base64_string, 'as' => 'name.ext', 'mime' => 'application/octet-stream']
            if (isset($att['path'])) {
                $mail->attach($att['path'], [
                    'as' => $att['as'] ?? null,
                    'mime' => $att['mime'] ?? null,
                ]);
            } elseif (isset($att['data']) && isset($att['as'])) {
                $mail->attachData(base64_decode($att['data']), $att['as'], [
                    'mime' => $att['mime'] ?? 'application/octet-stream',
                ]);
            }
        }

        return $mail;
    }
}

