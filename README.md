# HammerTime
[Alright stop!](http://www.youtube.com/watch?v=6y1qzEx0Ry4&feature=youtu.be&t=2m7s) This date library extends [Carbon](https://github.com/briannesbitt/Carbon) with some more sophisticated date calculations and getters / setters for all date parts.

## Installation
Install using composer:

    composer require cherrypick/hammertime
    
## Features

### More sophisticated date calculations
The date calculations with HammerTime are closer to real world problems. When you are at the end of a month and add another month, it doesn't leap into the beginning of the month afterwards.
```php
$date = HammerTime::createFromDate(2014, 5, 31);
$date->addMonths(1); // 2014-06-30 (with default PHP DateTime (and Carbon) it would be 2014-07-01)

$date = HammerTime::createFromDate(2012, 2, 29);
$date->addYear(1); // 2013-02-28 (with default PHP DateTime (and Carbon) it would be 2013-03-01)
```

Similar, it applies to the diff of months. You can find more information and examples [here.](http://php.net/manual/de/datetime.diff.php#101990)
```php
$date1 = HammerTime::createFromDate(2014, 2, 1);
$date2 = HammerTime::createFromDate(2014, 3, 1);

$date1->diffInMonths($date2); // 1 (with default PHP DateTime (and Carbon) it would be 0)
```

### Date Comparisons
This library provides some more clear names for date comparisons.
```php
$date1->isSameDate($date2);
$date1->isBefore($date2);
$date1->isBeforeOrEqual($date2);
$date1->isAfter($date2);
$date1->isAfterOrEqual($date2);
```

### Getters and Setters
This library provides getters and setters for all available date information:
```php
$date = HammerTime::createFromDate(2014, 11, 30, 12, 42, 42);
$date->getDay(); // 30
$date->getMonth(); // 11
$date->getYear(); // 2014
$date->getHour(); // 12
$date->getMinute(); // 42
$date->getSecond(); // 42
// and many more...

// the same applies to setters.
$date->setDay(20); // 2014-11-20
// etc..
```

There are Getters and Setters for every availble property.
