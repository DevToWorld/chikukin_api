<?php

namespace App\Services\Mailer;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class GmailApiMailer
{
    protected Client $http;

    public function __construct()
    {
        $this->http = new Client(['timeout' => 10]);
    }

    protected function parseDsn(): array
    {
        $dsn = env('MAIL_URL', '');
        $cfg = [
            'user' => env('GOOGLE_GMAIL_USER'),
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'refresh_token' => env('GOOGLE_REFRESH_TOKEN'),
        ];
        if (is_string($dsn) && str_starts_with($dsn, 'gmail+api://')) {
            $parts = parse_url($dsn);
            if (!empty($parts['user'])) $cfg['user'] = $parts['user'];
            if (!empty($parts['query'])) {
                parse_str($parts['query'], $q);
                foreach (['client_id','client_secret','refresh_token'] as $k) {
                    if (!empty($q[$k])) $cfg[$k] = $q[$k];
                }
            }
        }
        return $cfg;
    }

    protected function fetchAccessToken(array $cfg): string
    {
        $resp = $this->http->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'client_id' => $cfg['client_id'],
                'client_secret' => $cfg['client_secret'],
                'refresh_token' => $cfg['refresh_token'],
                'grant_type' => 'refresh_token',
            ],
        ]);
        $data = json_decode((string) $resp->getBody(), true);
        return (string) ($data['access_token'] ?? '');
    }

    protected function base64url(string $raw): string
    {
        return rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');
    }

    protected function needsEncoding(string $s): bool
    {
        return (bool) preg_match('/[^\x20-\x7E]/', $s); // non-ASCII
    }

    protected function encodePhrase(string $s): string
    {
        // Encode display-name using RFC 2047 encoded-word if non-ASCII
        if ($this->needsEncoding($s)) {
            return '=?UTF-8?B?'.base64_encode($s).'?=';
        }
        // Quote if it contains specials
        if (preg_match('/[\(\)<>@,;:\\"\.\[\]\s]/', $s)) {
            return '"'.addcslashes($s, '\\"').'"';
        }
        return $s;
    }

    protected function qpBase64(string $text): string
    {
        return chunk_split(base64_encode($text));
    }

    public function send(array $payload): void
    {
        $cfg = $this->parseDsn();
        $fromAddress = $payload['from']['address'] ?? env('MAIL_FROM_ADDRESS');
        $fromName = $payload['from']['name'] ?? env('MAIL_FROM_NAME');
        $fromPhrase = $fromName ? $this->encodePhrase($fromName).' <'.$fromAddress.'>' : $fromAddress;

        $to = $payload['to'] ?? [];
        if (!is_array($to)) $to = [$to];

        $subject = (string) ($payload['subject'] ?? '(no subject)');
        $bodyHtml = $payload['body_html'] ?? null;
        $bodyText = $payload['body_text'] ?? null;
        $attachments = is_array($payload['attachments'] ?? null) ? $payload['attachments'] : [];

        $accessToken = $this->fetchAccessToken($cfg);

        foreach ($to as $addr) {
            $mime = $this->buildMimeMessage(
                to: (string) $addr,
                from: $fromPhrase,
                subject: $subject,
                bodyHtml: $bodyHtml,
                bodyText: $bodyText,
                attachments: $attachments
            );

            $raw = $this->base64url($mime);

            $this->http->post('https://gmail.googleapis.com/gmail/v1/users/me/messages/send', [
                'headers' => [
                    'Authorization' => 'Bearer '.$accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => ['raw' => $raw],
            ]);
        }
    }

    protected function headersToString(array $headers): string
    {
        $lines = [];
        foreach ($headers as $k => $v) {
            $lines[] = $k.': '.$v;
        }
        return implode("\r\n", $lines)."\r\n\r\n";
    }

    /**
     * Build a MIME message string supporting optional HTML/TEXT and attachments.
     * Attachments support:
     *  - ['disk'=>..., 'path'=>..., 'filename'=>..., 'mime'=>...?]
     *  - ['path'=>'storage://<disk>/<path>', 'as'=>'name.ext', 'mime'=>...?]
     *  - ['path'=>'/abs/or/relative/file', 'as'=>'name.ext', 'mime'=>...?]
     *  - ['data'=>base64_string, 'as'=>'name.ext', 'mime'=>...?]
     */
    protected function buildMimeMessage(string $to, string $from, string $subject, ?string $bodyHtml, ?string $bodyText, array $attachments): string
    {
        $baseHeaders = [
            'To' => $to,
            'From' => $from,
            'Subject' => '=?UTF-8?B?'.base64_encode($subject).'?=',
            'MIME-Version' => '1.0',
        ];

        $hasAttachments = !empty($attachments);
        $hasHtml = !empty($bodyHtml);
        $hasText = !empty($bodyText);

        if (!$hasAttachments) {
            // Single-part or alternative-only
            if ($hasHtml && $hasText) {
                $alt = 'ALT_'.bin2hex(random_bytes(6));
                $headers = $baseHeaders + ['Content-Type' => 'multipart/alternative; boundary='.$alt];
                $out = $this->headersToString($headers);
                $out .= '--'.$alt."\r\n".'Content-Type: text/plain; charset=UTF-8' . "\r\n" . 'Content-Transfer-Encoding: base64' . "\r\n\r\n" . $this->qpBase64($bodyText ?? '') . "\r\n";
                $out .= '--'.$alt."\r\n".'Content-Type: text/html; charset=UTF-8' . "\r\n" . 'Content-Transfer-Encoding: base64' . "\r\n\r\n" . $this->qpBase64($bodyHtml ?? '') . "\r\n";
                $out .= '--'.$alt.'--' . "\r\n";
                return $out;
            }
            // Plain single part
            $headers = $baseHeaders + [
                'Content-Type' => ($hasHtml ? 'text/html' : 'text/plain').'; charset=UTF-8',
                'Content-Transfer-Encoding' => 'base64',
            ];
            return $this->headersToString($headers) . $this->qpBase64(($hasHtml ? ($bodyHtml ?? '') : ($bodyText ?? '')));
        }

        // multipart/mixed with optional alternative body
        $mixed = 'MIXED_'.bin2hex(random_bytes(6));
        $headers = $baseHeaders + ['Content-Type' => 'multipart/mixed; boundary='.$mixed];
        $out = $this->headersToString($headers);

        if ($hasHtml && $hasText) {
            $alt = 'ALT_'.bin2hex(random_bytes(6));
            $out .= '--'.$mixed."\r\n".'Content-Type: multipart/alternative; boundary='.$alt."\r\n\r\n";
            $out .= '--'.$alt."\r\n".'Content-Type: text/plain; charset=UTF-8' . "\r\n" . 'Content-Transfer-Encoding: base64' . "\r\n\r\n" . $this->qpBase64($bodyText ?? '') . "\r\n";
            $out .= '--'.$alt."\r\n".'Content-Type: text/html; charset=UTF-8' . "\r\n" . 'Content-Transfer-Encoding: base64' . "\r\n\r\n" . $this->qpBase64($bodyHtml ?? '') . "\r\n";
            $out .= '--'.$alt.'--' . "\r\n";
        } else {
            // Single body part
            $out .= '--'.$mixed."\r\n".'Content-Type: '.($hasHtml ? 'text/html' : 'text/plain').'; charset=UTF-8' . "\r\n" . 'Content-Transfer-Encoding: base64' . "\r\n\r\n" . $this->qpBase64(($hasHtml ? ($bodyHtml ?? '') : ($bodyText ?? ''))) . "\r\n";
        }

        // Append attachments
        foreach ($attachments as $att) {
            $filename = $att['as'] ?? $att['filename'] ?? 'attachment';
            $mime = $att['mime'] ?? 'application/octet-stream';
            $content = null;

            if (!empty($att['data'])) {
                // already base64 (common in API payloads)
                $content = base64_decode($att['data']);
            } elseif (!empty($att['path'])) {
                $path = (string) $att['path'];
                if (str_starts_with($path, 'storage://')) {
                    // storage://<disk>/<relativePath>
                    $rel = substr($path, strlen('storage://'));
                    [$disk, $relPath] = explode('/', $rel, 2);
                    $content = Storage::disk($disk)->get($relPath);
                } else {
                    $content = @file_get_contents($path);
                }
            } elseif (!empty($att['disk']) && !empty($att['path'])) {
                $content = Storage::disk($att['disk'])->get($att['path']);
            }

            if ($content === null) continue;
            $b64 = chunk_split(base64_encode($content));

            $fallback = preg_replace('/[^A-Za-z0-9._-]/', '_', $filename);
            $out .= '--'.$mixed."\r\n";
            $out .= 'Content-Type: '.$mime.'; name="'.$fallback.'"' . "\r\n";
            $out .= 'Content-Transfer-Encoding: base64' . "\r\n";
            $out .= 'Content-Disposition: attachment; filename="'.$fallback.'"; filename*=UTF-8\'\''.rawurlencode($filename)."\r\n\r\n";
            $out .= $b64 . "\r\n";
        }

        $out .= '--'.$mixed.'--' . "\r\n";
        return $out;
    }
}
