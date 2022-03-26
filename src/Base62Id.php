<?php

namespace Solarsnowfall;

use Exception;

class Base62Id
{
    const CHARS = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    /**
     * @param string $data
     * @return string
     */
    public static function decode(string $data): string
    {
        $data = str_split($data);

        $count = count($data);

        for ($i = 0; $i < $count; $i++)
            $data[$i] = strpos(self::CHARS, $data[$i]);

        $sourceBase = strlen(static::CHARS);

        $decoded = self::baseConvert($data, $sourceBase, 10);

        return implode('', $decoded);
    }

    /**
     * @param int $data
     * @return string
     * @throws Exception
     */
    public static function encode(int $data): string
    {
        $targetBase = strlen(static::CHARS);

        $converted = self::baseConvert($data, 10, $targetBase);

        $encoded = '';

        $count = count($converted);

        for ($i = 0; $i < $count; $i++)
            $encoded .= self::CHARS[$converted[$i]];

        return $encoded;
    }

    /**
     * @param string|int $source
     * @param int $sourceBase
     * @param int $targetBase
     * @return array
     */
    protected static function baseConvert($source, int $sourceBase, int $targetBase): array
    {
        $source = (array) $source;

        $result = [];

        while ($count = count($source))
        {
            $quotient = [];

            $remainder = 0;

            for ($i = 0; $i !== $count; $i++)
            {
                $accumulator = $source[$i] + $remainder * $sourceBase;

                $digit = (integer) ($accumulator / $targetBase);

                $remainder = $accumulator % $targetBase;

                if (count($quotient) || $digit)
                    $quotient[] = $digit;
            }

            array_unshift($result, $remainder);

            $source = $quotient;
        }

        return $result;
    }
}