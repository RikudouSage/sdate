<?php

namespace Rikudou\Sdate;

use DateTime;
use LogicException;

/**
 * @internal
 */
final class Sdate
{
    private const DATE_FLAGS = ['d', 'j', 'S', 'z', 'L', 'o', 'y', 'Y', 'F', 'm', 'M', 'n', 't'];

    /**
     * @param string   $format
     * @param int|null $timestamp
     *
     * @return false|string
     */
    public static function sdate(string $format, int $timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }

        $newFormat = '';
        $length = strlen($format);
        for ($i = 0; $i < $length; ++$i) {
            $char = $format[$i];
            if (
                in_array($char, self::DATE_FLAGS, true)
                && ($format[$i - 1] !== '\\' || ($format[$i - 1] === '\\' && $format[$i - 2] === '\\'))
            ) {
                $newFormat .= self::replaceDateCharacter($char, $timestamp);
            } else {
                $newFormat .= $char;
            }
        }

        return date($newFormat, $timestamp);
    }

    private static function replaceDateCharacter(string $character, int $timestamp): string
    {
        $september = new DateTime('1993-09-01');
        $target = new DateTime();
        $target->setTimestamp($timestamp);

        if ($target < $september) {
            return $character;
        }

        $diff = $september->diff($target);
        $days = $diff->days + 1;

        switch ($character) {
            case 'd':
                return sprintf('%02d', $days);
            case 'j':
                return (string) $days;
            case 'S':
                $days = (string) $days;
                $last = substr($days, -1);
                if (strlen($days) < 2) {
                    $secondToLast = false;
                } else {
                    $secondToLast = substr($days, -2, 1);
                }
                if ($last === '1' && $secondToLast !== '1') {
                    return '\s\t';
                } elseif ($last === '2' && $secondToLast !== '1') {
                    return '\n\d';
                } elseif ($last === '3' && $secondToLast !== '1') {
                    return '\r\d';
                } else {
                    return '\t\h';
                }
                // no break
            case 'z':
                return (string) ((int) $september->format('z') + $diff->days);
            case 'L':
                return '0';
            case 'o':
            case 'Y':
                return '1993';
            case 'y':
                return '93';
            case 'F':
                return '\S\e\p\t\e\m\b\e\r';
            case 'm':
                return '09';
            case 'M':
                return '\S\e\p';
            case 'n':
                return '9';
            case 't':
                return '∞';
            default:
                throw new LogicException("Unsupported flag '{$character}'");
        }
    }
}
