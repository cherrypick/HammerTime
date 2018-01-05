<?php

namespace CherryPick\HammerTime;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;

/**
 * A class supporting DateTime handling.
 */
class HammerTime extends Chronos
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
     * @param ChronosInterface $dt
     * @param bool   $abs
     * @return int|void
     */
    public function diffInMonths(ChronosInterface $dt = null, $abs = true)
    {
        $years = $dt->year - $this->year;
        $months = $dt->month - $this->month;

        $result = $years * self::MONTHS_PER_YEAR + $months;

        return $abs
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

        $date = $this;
        while ($value > 0) {
            $date = $date->addDays($direction);
            while ($date->isWeekend()) {
                $date = $date->addDays($direction);
            }

            $value--;
        }

        return $date;
    }

    /**
     * Add months to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * In difference to ChronosInterface::addMonths(), this function adds "actual" months and stays in the
     * requested months, even in the last days of the month (e.g. 2013-05-31 + 1 month = 2013-06-30)
     *
     * @param integer $value
     *
     * @return $this
     */
    public function addMonths($value)
    {
        $startMonth = $this->getMonth();
        $date = $this->modify($value . ' months'); // couldn't use addMonths, because this would be a recursive call.
        $reverted = $date->modify((-$value) . ' months');

        while ($startMonth != $reverted->getMonth()) {
            $date = $date->subDay();
            $reverted = $date->modify((-$value) . ' months');
        }

        return $date;
    }

    /**
     * Add years to the instance. Positive $value travel forward while
     * negative $value travel into the past.
     *
     * In difference to ChronosInterface::addYears(), this function adds "actual" years and keeps the
     * current months, even if the month in the next year has not enough days (in leap years)
     *
     * @param integer $value
     *
     * @return $this
     */
    public function addYears($value)
    {
        $date = $this;
        $startMonth = $this->getMonth();

        $date = $date->modify($value . ' years');
        while ($date->getMonth() != $startMonth) {
            $date = $date->modify('-1 day');
        }

        return $date;
    }

    /**
     * @param HammerTime $otherDate
     *
     * @return bool
     */
    public function isBefore(HammerTime $otherDate)
    {
        return $this->lt($otherDate);
    }

    /**
     * @param HammerTime $otherDate
     * @return bool
     */
    public function isAfter(HammerTime $otherDate)
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

        return $this->addDays($diff);
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
        return $this->hour($hour);
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
        return $this->minute($minute);
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
        return $this->month($month);
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
        return $this->second($second);
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
        return $this->year($year);
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
        return $this->day($day);
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

        return $this->addWeeks($week - $currentWeek);
    }

    /**
     * @return int
     */
    public function getMilliTimestamp()
    {
        return $this->getTimestamp() * 1000;
    }
}
