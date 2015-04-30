<?php

namespace CherryPick\CherryTime\Tests;

use CherryPick\HammerTime\HammerTime;
use PHPUnit_Framework_TestCase;

class HammerTimeTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @dataProvider sameDatesProvider
     *
     * @param HammerTime $date1
     * @param HammerTime $date2
     * @param HammerTime $date3
     */
    public function testIsSameDate(HammerTime $date1, HammerTime $date2, HammerTime $date3)
    {
        $this->assertTrue($date1->isSameDate($date2));
        $this->assertTrue($date2->isSameDate($date1));
        $this->assertFalse($date1->isSameDate($date3));
        $this->assertFalse($date3->isSameDate($date2));
    }

    /**
     * @test
     * @dataProvider sameDatesProvider
     *
     * @param HammerTime $date1
     * @param HammerTime $date2
     * @param HammerTime $date3
     */
    public function testIsBeforeOrEqual(HammerTime $date1, HammerTime $date2, HammerTime $date3)
    {
        // date1 and date2 are the same.
        $this->assertFalse($date1->isBefore($date2));
        $this->assertTrue($date1->isBeforeOrEqual($date2));
        $this->assertFalse($date2->isBefore($date1));
        $this->assertTrue($date2->isBeforeOrEqual($date1));

        // date3 is later
        $this->assertTrue($date1->isBefore($date3));
        $this->assertTrue($date1->isBeforeOrEqual($date3));
    }

    /**
     * @test
     * @dataProvider sameDatesProvider
     *
     * @param HammerTime $date1
     * @param HammerTime $date2
     * @param HammerTime $date3
     */
    public function testIsAfterOrEqual(HammerTime $date1, HammerTime $date2, HammerTime $date3)
    {
        // date1 and date2 are the same.
        $this->assertFalse($date1->isAfter($date2));
        $this->assertTrue($date1->isAfterOrEqual($date2));
        $this->assertFalse($date2->isAfter($date1));
        $this->assertTrue($date2->isAfterOrEqual($date1));

        // date3 is later
        $this->assertTrue($date3->isAfter($date1));
        $this->assertTrue($date3->isAfterOrEqual($date1));
    }

    /**
     * @test
     */
    public function testIsToday()
    {
        $today = HammerTime::today();

        $this->assertTrue($today->isToday());
        $this->assertFalse($today->subDay()->isToday());
        $this->assertFalse($today->subDay()->isToday());
        $this->assertTrue($today->addDays(2)->isToday());
        $this->assertFalse($today->addDay()->isToday());
        $this->assertFalse($today->addDay()->isToday());
    }

    /**
     * @test
     */
    public function testIsTomorrow()
    {
        $today = HammerTime::today();

        $this->assertFalse($today->isTomorrow());
        $this->assertFalse($today->subDay()->isTomorrow());
        $this->assertFalse($today->subDay()->isTomorrow());
        $this->assertFalse($today->addDays(2)->isTomorrow());
        $this->assertTrue($today->addDay()->isTomorrow());
        $this->assertTrue($today->isFuture());
        $this->assertFalse($today->addDay()->isTomorrow());
        $this->assertTrue($today->isFuture());
    }

    /**
     * @test
     */
    public function testIsYesterday()
    {
        $today = HammerTime::today();

        $this->assertFalse($today->isYesterday());
        $this->assertTrue($today->subDay()->isYesterday());
        $this->assertTrue($today->isPast());
        $this->assertFalse($today->subDay()->isYesterday());
        $this->assertTrue($today->isPast());
        $this->assertFalse($today->addDays(2)->isYesterday());
        $this->assertFalse($today->addDay()->isYesterday());
        $this->assertFalse($today->addDay()->isYesterday());
    }

    /**
     * @test
     */
    public function testWeekdays()
    {
        // Test from Monday
        $monday = HammerTime::today()->startOfWeek();
        $this->assertEquals(HammerTime::MONDAY, $monday->getDayOfWeek());
        $monday->addWeekdays(7);
        $this->assertEquals(HammerTime::WEDNESDAY, $monday->getDayOfWeek());

        // Test from Sunday
        $sunday = HammerTime::today()->endOfWeek();
        $this->assertEquals(HammerTime::SUNDAY, $sunday->getDayOfWeek());
        $sunday->addWeekday();
        $this->assertEquals(HammerTime::MONDAY, $sunday->getDayOfWeek());

        // Test from Saturday
        $saturday = HammerTime::today()->endOfWeek()->subDay();
        $this->assertEquals(HammerTime::SATURDAY, $saturday->getDayOfWeek());
        $saturday->addWeekday();
        $this->assertEquals(HammerTime::MONDAY, $saturday->getDayOfWeek());
    }

    /**
     * @test
     */
    public function testDiffs()
    {
        $startOfYear = HammerTime::today()->startOfYear();
        $endOfYear = HammerTime::today()->endOfYear();
        $startOfNextYear = HammerTime::today()->startOfYear()->addYear();

        $this->assertEquals(11, $endOfYear->diffInMonths($startOfYear));
        $this->assertEquals(12, $startOfNextYear->diffInMonths($startOfYear));

        for ($month = HammerTime::JANUARY; $month <= HammerTime::DECEMBER; $month++) {
            $monthDate = HammerTime::today()->setMonth($month);
            $start = $monthDate->startOfMonth();
            $end = clone $monthDate;
            $end->endOfMonth();
            $next = clone $start;
            $next->addMonth();

            $this->assertEquals(0, $end->diffInMonths($start));
            $this->assertEquals(1, $next->diffInMonths($start));
        }
    }

    /**
     * @test
     */
    public function testAddMonth()
    {
        $date = new HammerTime('2014-05-31 12:00:00');
        $date->addMonths(1);
        $this->assertEquals(new HammerTime('2014-06-30 12:00:00'), $date);

        $date = new HammerTime('2014-05-31 12:00:00');
        $date->addMonths(2);
        $this->assertEquals(new HammerTime('2014-07-31 12:00:00'), $date);
    }

    /**
     * @ŧest
     */
    public function testSubMonth()
    {
        $date = new HammerTime('2014-05-31 12:00:00');
        $date->subMonths(1);
        $this->assertEquals(new HammerTime('2014-04-30 12:00:00'), $date);

        $date = new HammerTime('2014-05-31 12:00:00');
        $date->subMonths(2);
        $this->assertEquals(new HammerTime('2014-03-31 12:00:00'), $date);
    }

    /**
     * @test
     */
    public function testAddMonths_February()
    {
        $date = new HammerTime('2014-01-31 12:00:00');
        $date->addMonths(1);
        $this->assertEquals(new HammerTime('2014-02-28 12:00:00'), $date);
    }

    /**
     * @test
     */
    public function testAddMonths_LeapYear()
    {
        $date = new HammerTime('2012-01-30 12:00:00');
        $date->addMonths(1);
        $this->assertEquals(new HammerTime('2012-02-29 12:00:00'), $date);
    }

    /**
     * @test
     */
    public function testAddYears()
    {
        // leap year
        $date = new HammerTime('2012-02-29 12:00:00');
        $date->addYear(1);
        $this->assertEquals(new HammerTime('2013-02-28 12:00:00'), $date);
    }

    /**
     * Test all getters and setters.
     *
     * @test
     */
    public function testGettersAndSetters()
    {
        $date = new HammerTime();

        // dates
        $date->setDay(14);
        $this->assertEquals(14, $date->getDay());

        $date->setMonth(12);
        $this->assertEquals(12, $date->getMonth());

        $date->setYear(1991);
        $this->assertEquals(1991, $date->getYear());

        $this->assertEquals(HammerTime::SATURDAY, $date->getDayOfWeek()); // 1991-12-14 was a Saturday
        $this->assertEquals(31, $date->getDaysInMonth()); // December has 31 days.
        $this->assertEquals(50, $date->getWeekOfYear());
        $this->assertEquals(2, $date->getWeekOfMonth());
        $this->assertEquals(347, $date->getDayOfYear());
        $this->assertEquals(4, $date->getQuarter());
        $this->assertFalse($date->isSummerTime());

        $date->setHour(13);
        $this->assertEquals(13, $date->getHour());

        $date->setMinute(37);
        $this->assertEquals(37, $date->getMinute());

        $date->setSecond(42);
        $this->assertEquals(42, $date->getSecond());

        $this->assertEquals('1991-12-14 13:37:42', $date->toDateTimeString());
    }

    /**
     * Test to change the day of week.
     *
     * @test
     */
    public function testSetDayOfWeek()
    {
        $date = HammerTime::create(2015, 4, 20, 13, 37, 42);

        $this->assertEquals(HammerTime::MONDAY, $date->getDayOfWeek());

        $date->setDayOfWeek(HammerTime::WEDNESDAY);
        $this->assertEquals(HammerTime::WEDNESDAY, $date->getDayOfWeek());
        $this->assertEquals('2015-04-22 13:37:42', $date->toDateTimeString());

        // Sunday is the start of the week
        $date->setDayOfWeek(HammerTime::SUNDAY);
        $this->assertEquals(HammerTime::SUNDAY, $date->getDayOfWeek());
        $this->assertEquals('2015-04-19 13:37:42', $date->toDateTimeString());
    }

    /**
     * @test
     */
    public function testToArray()
    {
        HammerTime::setTestNow(HammerTime::create(2014, 12, 17, 22, 36, 43, 'Europe/Berlin'));
        $date = HammerTime::create(2011, 6, 17, 22, 35, 42, 'Europe/Berlin');

        $expected = [
            'age' => 3,
            'quarter' => 2,
            'day' => 17,
            'month' => 6,
            'year' => 2011,
            'hour' => 22,
            'minute' => 35,
            'second' => 42,
            'microsecond' => 0,
            'timezone' => new \DateTimeZone('Europe/Berlin'),
            'timestamp' => 1308342942,
            'milliTimestamp' => 1308342942000,
            'dayOfWeek' => HammerTime::FRIDAY,
            'dayOfYear' => 167,
            'week' => 24,
        ];
        $this->assertEquals($expected, $date->toArray());
    }

    /**
     * Returns date to compare dates with.
     * The first and second are equal, the third isn't (but after the first).
     *
     * @return array
     */
    public function sameDatesProvider()
    {
        return [
            [
                HammerTime::create(2014, 2, 3, 12, 45, 3),
                HammerTime::create(2014, 2, 3, 12, 45, 3),
                HammerTime::create(2014, 2, 4, 12, 45, 3),
            ],
            [
                HammerTime::create(2014, 2, 3),
                HammerTime::create(2014, 2, 3),
                HammerTime::create(2014, 3, 5),
            ],
        ];
    }
}
