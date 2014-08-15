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
}
