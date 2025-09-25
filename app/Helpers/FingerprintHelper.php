<?php

namespace App\Helpers;

class FingerprintHelper
{
    /**
     * Compare two fingerprint templates.
     * Returns true if similarity >= $threshold.
     */
    public static function match(string $template1, string $template2, float $threshold = 0.9): bool
    {
        $bytes1 = unpack("C*", base64_decode($template1));
        $bytes2 = unpack("C*", base64_decode($template2));

        $len = min(count($bytes1), count($bytes2));
        if ($len === 0) return false;

        $matches = 0;
        for ($i = 1; $i <= $len; $i++) {
            if ($bytes1[$i] === $bytes2[$i]) {
                $matches++;
            }
        }

        $similarity = $matches / $len;

        return $similarity >= $threshold;
    }
}
