<?php

use PHPUnit\Framework\TestCase;
use Rikudou\Sdate\Sdate;

final class SdateTest extends TestCase
{
    public function testDateBeforeSeptember()
    {
        $time = strtotime('1992-05-03');
        $formatString = implode('', $this->getDateFlags());
        self::assertEquals(date($formatString, $time), Sdate::sdate($formatString, $time));
    }

    public function testSeptemberDates()
    {
        for ($i = 1; $i <= 30; ++$i) {
            $day = sprintf('%02d', $i);
            $time = strtotime("1993-09-{$day}");
            foreach ($this->getDateFlags() as $dateFlag) {
                if ($dateFlag === 't') { // this is already infinity, all other should match
                    continue;
                }
                self::assertEquals(
                    date($dateFlag, $time),
                    Sdate::sdate($dateFlag, $time),
                    "Results for 1993-09-{$day} (format: {$dateFlag}) are not equal"
                );
            }
        }
    }

    public function testFutureDates()
    {
        $time = strtotime('1993-10-01');

        self::assertEquals('31', Sdate::sdate('d', $time));
        self::assertEquals('31', Sdate::sdate('j', $time));
        self::assertEquals('st', Sdate::sdate('S', $time));
        self::assertEquals(date('z', $time), Sdate::sdate('z', $time));
        self::assertEquals('0', Sdate::sdate('L', $time));
        self::assertEquals('1993', Sdate::sdate('o', $time));
        self::assertEquals('1993', Sdate::sdate('Y', $time));
        self::assertEquals('93', Sdate::sdate('y', $time));
        self::assertEquals('September', Sdate::sdate('F', $time));
        self::assertEquals('09', Sdate::sdate('m', $time));
        self::assertEquals('Sep', Sdate::sdate('M', $time));
        self::assertEquals('9', Sdate::sdate('n', $time));
        self::assertEquals('∞', Sdate::sdate('t', $time));
    }

    public function testOrdinal()
    {
        $baseTime = strtotime('1993-09-01');

        self::assertEquals('st', Sdate::sdate('S', $baseTime));
        self::assertEquals('nd', Sdate::sdate('S', strtotime('+1 days', $baseTime)));
        self::assertEquals('rd', Sdate::sdate('S', strtotime('+2 days', $baseTime)));
        self::assertEquals('th', Sdate::sdate('S', strtotime('+3 days', $baseTime)));

        self::assertEquals('th', Sdate::sdate('S', strtotime('+10 days', $baseTime)));
        self::assertEquals('th', Sdate::sdate('S', strtotime('+11 days', $baseTime)));
        self::assertEquals('th', Sdate::sdate('S', strtotime('+12 days', $baseTime)));
        self::assertEquals('th', Sdate::sdate('S', strtotime('+13 days', $baseTime)));

        self::assertEquals('st', Sdate::sdate('S', strtotime('+20 days', $baseTime)));
        self::assertEquals('nd', Sdate::sdate('S', strtotime('+21 days', $baseTime)));
        self::assertEquals('rd', Sdate::sdate('S', strtotime('+22 days', $baseTime)));
        self::assertEquals('th', Sdate::sdate('S', strtotime('+23 days', $baseTime)));

        self::assertEquals('st', Sdate::sdate('S', strtotime('+100 days', $baseTime)));
        self::assertEquals('nd', Sdate::sdate('S', strtotime('+101 days', $baseTime)));
        self::assertEquals('rd', Sdate::sdate('S', strtotime('+102 days', $baseTime)));
        self::assertEquals('th', Sdate::sdate('S', strtotime('+103 days', $baseTime)));

        self::assertEquals('th', Sdate::sdate('S', strtotime('+110 days', $baseTime)));
        self::assertEquals('th', Sdate::sdate('S', strtotime('+111 days', $baseTime)));
        self::assertEquals('th', Sdate::sdate('S', strtotime('+112 days', $baseTime)));
        self::assertEquals('th', Sdate::sdate('S', strtotime('+113 days', $baseTime)));
    }

    private function getDateFlags()
    {
        $reflection = new ReflectionClass(Sdate::class);

        return $reflection->getConstants()['DATE_FLAGS'];
    }
}
