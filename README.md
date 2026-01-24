[![Packagist Version](https://img.shields.io/packagist/v/dwuty/calendar?label=version)](https://packagist.org/packages/dwuty/calendar)
[![Packagist Downloads](https://img.shields.io/packagist/dt/dwuty/calendar)](https://packagist.org/packages/dwuty/calendar)
[![GitHub License](https://img.shields.io/github/license/dwuty/calendar)](https://github.com/dwuty/calendar/blob/main/LICENSE)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/dwuty/calendar)](https://packagist.org/packages/dwuty/calendar)

**dwuty/calendar** provides a simple and easy way to generate a multi-language (i18n) calendar with item(s) on your website.

## Installation

> index.php

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use dwuty\calendar\calendar;

echo '<link href="/vendor/dwuty/calendar/dist/css/calendar.min.css" rel="stylesheet" type="text/css">';

$date = date_create(date('Y-m-d'));

$aryDates = [
	date_format($date, 'Y-m-d') => [
		[
			'EVENT_TITLE' => 'Event 4',
			'TIME_FROM' => '08:00:00',
			'TIME_TO' => '13:00:00',
			'DETAILS' => 'lorem ipsun dolar sit genda tersd dsad'
		],
		[
			'EVENT_TITLE' => 'Event 5',
			'TIME_FROM' => '15:00:00',
			'TIME_TO' => '21:00:00',
			'DETAILS' => 'lorem ipsun dolar sit genda tersd dsad'
		]
	],
	date_format(date_sub($date, date_interval_create_from_date_string('2 days')), 'Y-m-d') => [
		[
			'EVENT_TITLE' => 'Event 2',
			'TIME_FROM' => '02:00:00',
			'TIME_TO' => '05:00:00',
			'DETAILS' => 'lorem ipsun dolar sit genda tersd dsad'
		],
		[
			'EVENT_TITLE' => 'Event 3',
			'TIME_FROM' => '18:00:00',
			'TIME_TO' => '23:59:00',
			'DETAILS' => 'lorem ipsun dolar sit genda tersd dsad'
		]
	],
	date_format(date_sub($date, date_interval_create_from_date_string('10 days')), 'Y-m-d') => [
		[
			'EVENT_TITLE' => 'Event 1',
			'TIME_FROM' => '02:00:00',
			'TIME_TO' => '05:00:00',
			'DETAILS' => 'lorem ipsun dolar sit genda tersd dsad'
		]
	]
];


calendar::createCalendar('en-EN', $aryDates);
// calendar::createCalendar('de-DE', $aryDates);

echo '<script src="/vendor/dwuty/calendar/src/calendar.js"></script>';
```
