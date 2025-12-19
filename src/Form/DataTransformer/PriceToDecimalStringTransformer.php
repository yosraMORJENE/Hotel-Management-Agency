<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Normalizes user-entered prices into a DECIMAL-compatible string.
 *
 * Accepts inputs like:
 * - "700"
 * - "700dt" / "700 dt"
 * - "1 200,50"
 * - "1200.5"
 *
 * Outputs a string with 2 decimals (e.g. "700.00", "1200.50").
 */
final class PriceToDecimalStringTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value Model value (string|null)
     */
    public function transform($value): string
    {
        if ($value === null) {
            return '';
        }

        return (string) $value;
    }

    /**
     * @param mixed $value View value (string|int|float|null)
     */
    public function reverseTransform($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $raw = trim((string) $value);
        if ($raw === '') {
            return null;
        }

        // Remove common whitespace including non‑breaking spaces.
        $raw = str_replace([" ", "\t", "\n", "\r", "\u{00A0}"], '', $raw);

        // Normalize decimal separator.
        $raw = str_replace(',', '.', $raw);

        // Extract first numeric occurrence.
        if (!preg_match('/-?\d+(?:\.\d+)?/', $raw, $m)) {
            throw new TransformationFailedException('Please enter a valid price (example: 1200.50).');
        }

        $num = $m[0];

        // Normalize to 2 decimals.
        if (str_contains($num, '.')) {
            [$int, $dec] = explode('.', $num, 2);
            $dec = preg_replace('/\D/', '', (string) $dec);
            $dec = substr($dec, 0, 2);
            $num = $int . '.' . str_pad($dec, 2, '0');
        } else {
            $num .= '.00';
        }

        return $num;
    }
}
