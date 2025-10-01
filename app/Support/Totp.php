<?php

namespace App\Support;

class Totp
{
    // Default TOTP parameters
    public const PERIOD = 30; // seconds
    public const DIGITS = 6;
    public const ALGO = 'sha1';

    public static function verify(string $base32Secret, string $code, int $window = 1): bool
    {
        $secret = self::base32Decode($base32Secret);
        if ($secret === false) return false;

        $time = time();
        for ($i = -$window; $i <= $window; $i++) {
            $expected = self::generate($secret, $time + ($i * self::PERIOD));
            if (hash_equals($expected, self::normalizeCode($code))) {
                return true;
            }
        }
        return false;
    }

    public static function provisioningUri(string $label, string $issuer, string $base32Secret): string
    {
        $params = http_build_query([
            'secret' => $base32Secret,
            'issuer' => $issuer,
            'algorithm' => strtoupper(self::ALGO),
            'digits' => self::DIGITS,
            'period' => self::PERIOD,
        ]);
        return sprintf('otpauth://totp/%s:%s?%s', rawurlencode($issuer), rawurlencode($label), $params);
    }

    public static function generate(string $secretBinary, ?int $forTime = null): string
    {
        $forTime = $forTime ?? time();
        $counter = intdiv($forTime, self::PERIOD);
        $binCounter = pack('N*', 0) . pack('N*', $counter);
        $hash = hash_hmac(self::ALGO, $binCounter, $secretBinary, true);
        $offset = ord(substr($hash, -1)) & 0x0F;
        $binary = (ord($hash[$offset]) & 0x7F) << 24
                | (ord($hash[$offset + 1]) & 0xFF) << 16
                | (ord($hash[$offset + 2]) & 0xFF) << 8
                | (ord($hash[$offset + 3]) & 0xFF);
        $otp = $binary % (10 ** self::DIGITS);
        return str_pad((string)$otp, self::DIGITS, '0', STR_PAD_LEFT);
    }

    private static function normalizeCode(string $code): string
    {
        return str_pad(preg_replace('/\D+/', '', $code), self::DIGITS, '0', STR_PAD_LEFT);
    }

    // RFC4648 base32 decode (upper/lower accepted)
    public static function base32Decode(string $input)
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $clean = strtoupper(preg_replace('/[^A-Z2-7]/i', '', $input));
        $buffer = 0;
        $bitsLeft = 0;
        $result = '';
        for ($i = 0; $i < strlen($clean); $i++) {
            $val = strpos($alphabet, $clean[$i]);
            if ($val === false) continue;
            $buffer = ($buffer << 5) | $val;
            $bitsLeft += 5;
            if ($bitsLeft >= 8) {
                $bitsLeft -= 8;
                $result .= chr(($buffer >> $bitsLeft) & 0xFF);
            }
        }
        return $result;
    }

    // RFC4648 base32 encode (for secret creation)
    public static function base32Encode(string $data): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $bits = '';
        for ($i = 0; $i < strlen($data); $i++) {
            $bits .= str_pad(decbin(ord($data[$i])), 8, '0', STR_PAD_LEFT);
        }
        $out = '';
        for ($i = 0; $i < strlen($bits); $i += 5) {
            $chunk = substr($bits, $i, 5);
            if (strlen($chunk) < 5) {
                $chunk = str_pad($chunk, 5, '0', STR_PAD_RIGHT);
            }
            $out .= $alphabet[bindec($chunk)];
        }
        return $out;
    }
}

