<?php

namespace CherryPick\HammerTime;

use Carbon\Carbon;

/**
 * A class supporting DateTime handling.
 */
class HammerTime extends Carbon
{

    /**
     * Month constants
     */
    const JANUARY = 1;
    const FEBRUARY = 2;
    const MARCH = 3;
    const APRIL = 4;
    const MAY = 5;
    const JUNE = 6;
    const JULY = 7;
    const AUGUST = 8;
    const SEPTEMBER = 9;
    const OCTOBER = 10;
    const NOVEMBER = 11;
    const DECEMBER = 12;

    /**
     * Formatting constants
     */
    const DAY = 'd';
    const DAY_OF_YEAR = 'z';
    const WEEK = 'W';
    const WEEKDAY = 'l';
    const WEEKDAY_SHORT = 'D';
    const WEEKDAY_DIGIT = 'w';
    const MONTH = 'm';
    const MONTH_NAME = 'F';
    const MONTH_SHORT = 'M';
    const MONTH_DAYS = 't';
    const YEAR = 'Y';
    const YEAR_SHORT = 'y';
    const YEAR_8601 = 'o';
    const LEAPYEAR = 'L';
    const MERIDIEM = 'a';
    const SWATCH = 'B';
    const HOUR = 'H';
    const HOUR_SHORT = 'G';
    const HOUR_AM = 'h';
    const HOUR_SHORT_AM = 'g';
    const MINUTE = 'i';
    const SECOND = 's';
    const MICROSECOND = 'u';
    const SUMMERTIME = 'I';
    const GMT_DIFF = 'O';
    const GMT_DIFF_SEP = 'P';
    const TIMEZONE = 'T';
    const TIMEZONE_NAME = 'e';
    const TIMEZONE_SECS = 'Z';
    const ISO_8601 = 'c';
    const RFC_2822 = 'r';
    const TIMESTAMP = 'U';

    /**
     * @param \Carbon\Carbon|\DateTimeInterface|null $date
     * @param bool                                   $absolute Get the absolute of the difference
     * @return int
     */
    public function diffInMonths($date = null, $absolute = true)
    {
        $dt = $this->resolveCarbon($date);
        $years = $dt->year - $this->year;
        $months = $dt->month - $this->month;

        $result = $years * self::MONTHS_PER_YEAR + $months;

        return $absolute
            ? abs($result)
            : $result;
    }

    /**
     * Add the given amount of weekdays to the current datetime object. This is necessary as the modification
     * of the weekday in the internal PHP DateTime removes the time (sets it to 00:00:00).
     *
     * @param int $value
     * @return $this
     */
    public function addWeekdays($value)
    {
        $direction = $value > 0 ? 1 : -1;
        $value = abs($value);

        while ($value > 0) {
            $this->addDays($direction);

            while ($this->isWeekend()) {
                $this->addDays($direction);
            }

            $value--;
        }

        return $this;
    }

    /**
     * Add months to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * In difference to Carbon::addMonths(), this function adds "actual" months and stays in the
     * requested months, even in the last days of the month (e.g. 2013-05-31 + 1 month = 2013-06-30)
     *
     * @param integer $value
     *
     * @return $this
     */
    public function addMonths($value)
    {
        $startMonth = $this->getMonth();
        $this->modify($value . ' months'); // couldn't use addMonths, because this would be a recursive call.

        $reverted = $this->copy();
        $reverted->modify((-$value) . ' months');

        while ($startMonth != $reverted->getMonth()) {
            $this->subDay();

            $reverted = $this->copy();
            $reverted->modify((-$value) . ' months');
        }

        return $this;
    }

    /**
     * Add years to the instance. Positive $value travel forward while
     * negative $value travel into the past.
     *
     * In difference to Carbon::addYears(), this function adds "actual" years and keeps the
     * current months, even if the month in the next year has not enough days (in leap years)
     *
     * @param integer $value
     *
     * @return $this
     */
    public function addYears($value)
    {
        $init = $this->copy();
        $startMonth = $init->getMonth();

        $this->modify($value . ' years');
        while ($this->format('m') != $startMonth) {
            $this->modify('-1 day');
        }

        return $this;
    }

    /**
     * @param HammerTime $otherDate
     *
     * @return bool
     */
    public function isBefore($otherDate)
    {
        return $this->lt($otherDate);
    }

    /**
     * @param HammerTime $otherDate
     * @return bool
     */
    public function isAfter($otherDate)
    {
        return $this->gt($otherDate);
    }

    /**
     * @param HammerTime $otherDate
     * @return bool
     */
    public function isBeforeOrEqual(HammerTime $otherDate)
    {
        return $this->lte($otherDate);
    }

    /**
     * @param HammerTime $otherDate
     * @return bool
     */
    public function isAfterOrEqual(HammerTime $otherDate)
    {
        return $this->gte($otherDate);
    }

    /**
     * @param HammerTime $otherDate
     * @return bool
     */
    public function isSameDate(HammerTime $otherDate)
    {
        return $this->copy()->startOfDay()->eq($otherDate->copy()->startOfDay());
    }

    /**
     * @return bool
     */
    public function isSummerTime()
    {
        return $this->dst;
    }

    /**
     * @return bool
     */
    public function isLocal()
    {
        return $this->local;
    }

    /**
     * @return bool
     */
    public function isUtc()
    {
        return $this->utc;
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'age' => $this->getAge(),
            'quarter' => $this->getQuarter(),
            'day' => $this->getDay(),
            'month' => $this->getMonth(),
            'year' => $this->getYear(),
            'hour' => $this->getHour(),
            'minute' => $this->getMinute(),
            'second' => $this->getSecond(),
            'microsecond' => $this->getMicrosecond(),
            'timezone' => $this->getTimezone(),
            'timestamp' => $this->getTimestamp(),
            'milliTimestamp' => $this->getMilliTimestamp(),
            'dayOfWeek' => $this->getDayOfWeek(),
            'dayOfYear' => $this->getDayOfYear(),
            'week' => $this->getWeek(),
        );
    }

    /**
     * @return int
     */
    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
    }

    /**
     * @param int $dayOfWeek
     * @return $this
     */
    public function setDayOfWeek($dayOfWeek)
    {
        $currentDayOfWeek = $this->getDayOfWeek();
        $diff = $dayOfWeek - $currentDayOfWeek;

        $this->addDays($diff);

        return $this;
    }

    /**
     * @return int
     */
    public function getDayOfYear()
    {
        return $this->dayOfYear;
    }

    /**
     * @return int
     */
    public function getDaysInMonth()
    {
        return $this->daysInMonth;
    }

    /**
     * @return int
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * @param int $hour
     * @return $this
     */
    public function setHour($hour)
    {
        $this->hour = $hour;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * @param int $minute
     * @return $this
     */
    public function setMinute($minute)
    {
        $this->minute = $minute;

        return $this;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param int $month
     * @return $this
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * @return int
     */
    public function getOffsetHours()
    {
        return $this->offsetHours;
    }

    /**
     * @return int
     */
    public function getQuarter()
    {
        return $this->quarter;
    }

    /**
     * @return int
     */
    public function getSecond()
    {
        return $this->second;
    }

    /**
     * @param int $second
     * @return $this
     */
    public function setSecond($second)
    {
        $this->second = $second;

        return $this;
    }

    /**
     * @return string
     */
    public function getTimezoneName()
    {
        return $this->timezoneName;
    }

    /**
     * @return int
     */
    public function getWeekOfMonth()
    {
        return $this->weekOfMonth;
    }

    /**
     * @return int
     */
    public function getWeekOfYear()
    {
        return $this->weekOfYear;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int $year
     * @return $this
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param int $day
     * @return $this
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * @return int
     */
    public function getMicrosecond()
    {
        return intval($this->format(self::MICROSECOND));
    }

    /**
     * @return int
     */
    public function getWeek()
    {
        return intval($this->format(self::WEEK));
    }

    /**
     * @param int $week
     * @return static
     */
    public function setWeek($week)
    {
        $currentWeek = $this->getWeek();
        $this->addWeeks($week - $currentWeek);

        return $this;
    }

    /**
     * @return int
     */
    public function getMilliTimestamp()
    {
        return $this->getTimestamp() * 1000;
    }
}
