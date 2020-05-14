# Rough Date
Needed to store a "rough date" in one of my project, may be useful for otheres.

![CI](https://github.com/mareg/rough-date/workflows/CI/badge.svg?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/mareg/rough-date/badges/build.png?b=master)](https://scrutinizer-ci.com/g/mareg/rough-date/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mareg/rough-date/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mareg/rough-date/?branch=master)
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

# Installation

Execute from shell the following command:
```bash
$ composer require mareg/rough-date
```
Or add `"mareg/rough-date": "^1.0"` to your `composer.json`:
```json
    "require": {
        "mareg/rough-date": "^1.0"
    },
```

# Usage

Create a `RoughDate` object from the string:
```php
$roughDate = RoughDate::fromString('May 2005');
echo $roughDate->format();
```

Or from a `DateTime` date object:
```php
$date = new \DateTime();
$roughDate = RoughDate::fromDateTime($date);
echo $roughDate->format();
```

## Accepted date formats
When creating an object through `RoughDate::fromString()`:

* `Y-m-d`, e.g. `2015-02-22`, but also `2013-05-00` and `2013-00-00` are correct
* `Y/m/d` and `Y.m.d` are accepted
* `j. M Y`, e.g. `13. May 2005`
* `M Y`, e.g. `May 1985`
* `Y`, e.g. `1978`

## Output date formats
When calling `RoughDate::format()`:
* For a full date output accepts all variables as in [date()](http://php.net/manual/en/function.date.php) method
* For other dates only available variables will be substituded and unavailable ones will be removed, e.g.:
 * for date `2015-02-00` and format `j M Y` you'll get `Feb 2015`
 * for date `1978-00-00` and format `j M Y` you'll get `1978`
