<?php

use Rikudou\Sdate\Sdate;

/**
 * @param string $format
 * @param int|null $timestamp
 * @return false|string
 */
function sdate(string $format, int $timestamp = null)
{
    return Sdate::sdate($format, $timestamp);
}
