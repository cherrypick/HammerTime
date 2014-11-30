<?php

namespace CherryPick\CherryTime\Tests;

use CherryPick\HammerTime\HammerTime;
use PHPUnit_Framework_TestCase;

class HammerTimeTest extends PHPUnit_Framework_TestCase
{

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
     * Test wheter two HammerTime-objects have the same date
     */
    public function testIsSameDay()
    {
        $this->assertTrue(HammerTime::today()->isSameDate(HammerTime::now()));
        $this->assertTrue(HammerTime::yesterday()->isSameDate(HammerTime::now()->subDay()));
        $this->assertFalse(HammerTime::yesterday()->isSameDate(HammerTime::today()));
    }
}
