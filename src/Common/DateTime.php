<?php

namespace CherryPick\Common;

use Carbon\Carbon;

/**
 * A class supporting DateTime handling.
 */
class DateTime extends Carbon implements ArraySerializableInterface
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
     * @param Carbon $dt
     * @param bool   $abs
     * @return int|void
     */
    public function diffInMonths(Carbon $dt = null, $abs = true)
    {
        $years = $dt->year - $this->year;
        $months = $dt->month - $this->month;

        $result = $years * self::MONTHS_PER_YEAR + $months;

        return $abs ? abs($result) : $result;
    }

    /**
     * @param DateTime $otherDate
     * @return bool
     */
    public function isBefore(DateTime $otherDate)
    {
        return $this->lt($otherDate);
    }

    /**
     * @param DateTime $otherDate
     * @return bool
     */
    public function isAfter(DateTime $otherDate)
    {
        return $this->gt($otherDate);
    }

    /**
     * @param DateTime $otherDate
     * @return bool
     */
    public function isBeforeOrEqual(DateTime $otherDate)
    {
        return $this->lte($otherDate);
    }

    /**
     * @param DateTime $otherDate
     * @return bool
     */
    public function isAfterOrEqual(DateTime $otherDate)
    {
        return $this->gte($otherDate);
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
            'age'            => $this->getAge(),
            'quarter'        => $this->getQuarter(),
            'day'            => $this->getDay(),
            'month'          => $this->getMonth(),
            'year'           => $this->getYear(),
            'hour'           => $this->getHour(),
            'minute'         => $this->getMinute(),
            'second'         => $this->getSecond(),
            'microsecond'    => $this->getMicrosecond(),
            'timezone'       => $this->getTimezone(),
            'timestamp'      => $this->getTimestamp(),
            'milliTimestamp' => $this->getMilliTimestamp(),
            'dayOfWeek'      => $this->getDayOfWeek(),
            'dayOfYear'      => $this->getDayOfYear(),
            'week'           => $this->getWeek(),
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
     * @return int
     */
    public function getMilliTimestamp()
    {
        return $this->getTimestamp() * 1000;
    }
}
