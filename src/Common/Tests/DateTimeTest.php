<?php

namespace CherryPick\Common\Tests;

use CherryPick\Common\DateTime;
use PHPUnit_Framework_TestCase;

class DateTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function testIsToday()
    {
        $today = DateTime::today();

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
        $today = DateTime::today();

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
        $today = DateTime::today();

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
        $monday = DateTime::today()->startOfWeek();
        $this->assertEquals(DateTime::MONDAY, $monday->getDayOfWeek());
        $monday->addWeekdays(7);
        $this->assertEquals(DateTime::WEDNESDAY, $monday->getDayOfWeek());

        // Test from Sunday
        $sunday = DateTime::today()->endOfWeek();
        $this->assertEquals(DateTime::SUNDAY, $sunday->getDayOfWeek());
        $sunday->addWeekday();
        $this->assertEquals(DateTime::MONDAY, $sunday->getDayOfWeek());

        // Test from Saturday
        $saturday = DateTime::today()->endOfWeek()->subDay();
        $this->assertEquals(DateTime::SATURDAY, $saturday->getDayOfWeek());
        $saturday->addWeekday();
        $this->assertEquals(DateTime::MONDAY, $saturday->getDayOfWeek());
    }

    /**
     * @test
     */
    public function testDiffs()
    {
        $startOfYear = DateTime::today()->startOfYear();
        $endOfYear = DateTime::today()->endOfYear();
        $startOfNextYear = DateTime::today()->startOfYear()->addYear();

        $this->assertEquals(11, $endOfYear->diffInMonths($startOfYear));
        $this->assertEquals(12, $startOfNextYear->diffInMonths($startOfYear));

        for ($month = DateTime::JANUARY; $month <= DateTime::DECEMBER; $month++) {
            $monthDate = DateTime::today()->setMonth($month);
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
        $date = new DateTime('2014-05-31 12:00:00');
        $date->addMonths(1);
        $this->assertEquals(new DateTime('2014-06-30 12:00:00'), $date);

        $date = new DateTime('2014-05-31 12:00:00');
        $date->addMonths(2);
        $this->assertEquals(new DateTime('2014-07-31 12:00:00'), $date);

        // sub months
        $date = new DateTime('2014-05-31 12:00:00');
        $date->subMonths(1);
        $this->assertEquals(new DateTime('2014-04-30 12:00:00'), $date);

        $date = new DateTime('2014-05-31 12:00:00');
        $date->subMonths(2);
        $this->assertEquals(new DateTime('2014-03-31 12:00:00'), $date);

        // february
        $date = new DateTime('2014-01-31 12:00:00');
        $date->addMonths(1);
        $this->assertEquals(new DateTime('2014-02-28 12:00:00'), $date);

        // leap year
        $date = new DateTime('2012-01-30 12:00:00');
        $date->addMonths(1);
        $this->assertEquals(new DateTime('2012-02-29 12:00:00'), $date);
    }

    public function testAddYears()
    {
        // leap year
        $date = new DateTime('2012-02-29 12:00:00');
        $date->addYear(1);
        $this->assertEquals(new DateTime('2013-02-28 12:00:00'), $date);
    }
}
