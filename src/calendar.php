<?php

declare(strict_types=1);

namespace dwuty\calendar;

class calendar
{
	function __construct() {}
	function __destruct() {}
	public static function createCalendar($language = 'de-DE', $events = null)
	{
		$content = json_decode(file_get_contents(dirname(__DIR__, 1) . '/dist/i18n/' . $language . '.json'), true);
		echo '<div class="container-calendar">';
		echo '<!-- special thanks to Open Source Coding https://www.youtube.com/@opensourcecoding -->';
		echo '<div class="left">';
		echo '<div class="calendar">';
		echo '<div class="month">';
		echo '<i class="prev arrow-left"></i>';
		echo '<div class="date"></div>';
		echo '<i class="next arrow-right"></i>';
		echo '</div>';
		echo '<div class="weekdays">';
		foreach ($content['weekdays'] as $value) {
			echo '<div>' . $value . '</div>';
		}

		echo '</div>';
		echo '<div class="days">';
		echo '</div>';
		echo '<div class="goto-today">';
		echo '<div class="goto">';
		echo '<input type="text" placeholder="' . $content['goto']['placeholder'] . '" class="date-input">';
		echo '<button class="goto-btn">' . $content['goto']['button'] . '</button>';
		echo '</div>';
		echo '<button class="today-btn">' . $content['today'] . '</button>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '<div class="right">';
		echo '<div class="today-date">';
		echo '<div class="event-day"></div>';
		echo '<div class="event-date"></div>';
		echo '</div>';
		echo '<div class="events">';
		echo '</div>';
		echo '</div>';
		echo '</div>';

		echo '<script defer>';
		self::addJS($language, $content, $events);
		echo '</script>';
	}
	private static function addJS($language, $content, $events)
	{
		echo 'const calendar = document.querySelector(\'.calendar\'),';
		echo 'date = document.querySelector(\'.date\'),';
		echo 'daysContainer = document.querySelector(\'.days\'),';
		echo 'prev = document.querySelector(\'.prev\'),';
		echo 'next = document.querySelector(\'.next\'),';
		echo 'todayBtn = document.querySelector(\'.today-btn\'),';
		echo 'gotoBtn = document.querySelector(\'.goto-btn\'),';
		echo 'dateInput = document.querySelector(\'.date-input\'),';
		echo 'eventDay = document.querySelector(\'.event-day\'),';
		echo 'eventDate = document.querySelector(\'.event-date\'),';
		echo 'eventsContainer = document.querySelector(\'.events\');';

		echo 'let today = new Date();';
		echo 'let activeDay;';
		echo 'let month = today.getMonth();';
		echo 'let year = today.getFullYear();';

		echo 'const json_months =' . json_encode($content['months']) . ';';
		echo 'const months = Object.keys(json_months).map((key) => json_months[key]);';

		echo 'const json_events =' . json_encode($events) . ';';
		echo 'const eventsArr = Object.keys(json_events).map((key) => [key,json_events[key]]);';

		echo 'function initCalendar() {';

		echo 'const lastDay = new Date(year, month + 1, 0);';
		echo 'const prevLastDay = new Date(year, month, 0);';
		echo 'const prevDays = prevLastDay.getDate();';
		echo 'const lastDate = lastDay.getDate();';

		switch ($language) {
			case 'de-DE':
				echo 'const firstDay = new Date(year, month, 0);';
				echo 'const day = firstDay.getDay();';
				echo 'const nextDays = 7 - lastDay.getDay();';
				break;
			case 'en-EN':
				echo 'const firstDay = new Date(year, month, 1);';
				echo 'const day = firstDay.getDay();';
				echo 'const nextDays = 7 - lastDay.getDay() - 1;';
				break;

			default:
				echo 'const firstDay = new Date(year, month, 0);';
				echo 'const day = firstDay.getDay();';
				echo 'const nextDays = 7 - lastDay.getDay();';
				break;
		}

		echo 'date.innerHTML = months[month] + \' \' + year;';
		echo 'let days = \'\';';

		echo 'for (let x = day; x > 0; x--) {';
		echo 'days += `<div class="day prev-date">${prevDays - x + 1}</div>`;';
		echo '}';

		echo 'for (let i = 1; i <= lastDate; i++) {';
		echo 'let event = false;';
		echo 'eventsArr.forEach((eventObj) => {';
		echo 'const eventdate = new Date(eventObj[0]);';
		echo 'if (';
		echo 'eventdate.getDate() === i &&';
		echo 'eventdate.getMonth() === month &&';
		echo 'eventdate.getFullYear() === year';
		echo ') {';
		echo 'event = true;';
		echo '}';
		echo '});';

		echo 'if (';
		echo 'i === new Date().getDate() &&';
		echo 'month === new Date().getMonth() &&';
		echo 'year === new Date().getFullYear()';
		echo ') {';
		echo 'activeDay = i;';
		echo 'getActiveDay(i);';
		echo 'updateEvents(i);';
		echo 'if (event) {';
		echo 'days += `<div class="day today active event">${i}</div>`;';
		echo '} else {';
		echo 'days += `<div class="day today active">${i}</div>`;';
		echo '}';
		echo '}';
		echo 'else {';
		echo 'if (event) {';
		echo 'days += `<div class="day event">${i}</div>`;';
		echo '} else {';
		echo 'days += `<div class="day">${i}</div>`;';
		echo '}';
		echo '}';
		echo '}';

		switch ($language) {
			case 'de-DE':
				echo 'if (nextDays != 7) {';
				echo 'for (let j = 1; j <= nextDays; j++) {';
				echo 'days += `<div class="day next-date">${j}</div>`;';
				echo '}';
				echo '}';
				break;
			case 'en-EN':
				echo 'for (let j = 1; j <= nextDays; j++) {';
				echo 'days += `<div class="day next-date">${j}</div>`;';
				echo '}';
				break;

			default:
				echo 'if (nextDays != 7) {';
				echo 'for (let j = 1; j <= nextDays; j++) {';
				echo 'days += `<div class="day next-date">${j}</div>`;';
				echo '}';
				echo '}';
				break;
		}

		echo 'daysContainer.innerHTML = days;';
		echo 'addListener();';
		echo '}';

		echo 'function prevMonth() {';
		echo 'month--;';
		echo 'if (month < 0) {';
		echo 'month = 11;';
		echo 'year--;';
		echo '}';
		echo 'initCalendar();';
		echo '}';

		echo 'function nextMonth() {';
		echo 'month++;';
		echo 'if (month > 11) {';
		echo 'month = 0;';
		echo 'year++;';
		echo '}';
		echo 'initCalendar();';
		echo '}';

		echo 'prev.addEventListener(\'click\', prevMonth);';
		echo 'next.addEventListener(\'click\', nextMonth);';

		echo 'todayBtn.addEventListener(\'click\', () => {';
		echo 'today = new Date();';
		echo 'month = today.getMonth();';
		echo 'year = today.getFullYear();';
		echo 'initCalendar();';
		echo '});';

		echo 'dateInput.addEventListener(\'keyup\', (e) => {';
		echo 'dateInput.value = dateInput.value.replace(/[^0-9/]/g, \'\');';
		echo 'if (dateInput.value.length === 2) {';
		echo 'dateInput.value += \'/\';';
		echo '}';
		echo 'if (dateInput.value.length > 7) {';
		echo 'dateInput.value = dateInput.value.slice(0, 7);';
		echo '}';

		echo 'if (e.inputType === \'deleteContentBackward\') {';
		echo 'if (dateInput.value.length === 3) {';
		echo 'dateInput.value = dateInput.value.slice(0, 2);';
		echo '}';
		echo '}';
		echo '});';

		echo 'gotoBtn.addEventListener(\'click\', gotoDate);';

		echo 'function gotoDate() {';
		echo 'const dateArr = dateInput.value.split(\'/\');';
		echo 'if (dateArr.length === 2) {';
		echo 'if (dateArr[0] > 0 && dateArr[0] < 13 && dateArr[1].length === 4) {';
		echo 'month = dateArr[0] - 1;';
		echo 'year = dateArr[1];';
		echo 'initCalendar();';
		echo 'return;';
		echo '}';
		echo '}';
		echo 'alert(' . json_encode($content['error']['date']) . ');';
		echo '}';

		echo 'function addListener() {';
		echo 'const days = document.querySelectorAll(\'.day\');';
		echo 'days.forEach((day) => {';
		echo 'day.addEventListener(\'click\', (e) => {';
		echo 'pointerEvent(e);';
		echo 'days.forEach((day) => {';
		echo 'day.classList.remove(\'active\');';
		echo '});';
		echo 'if (e.target.classList.contains(\'prev-date\')) {';
		echo 'prevMonth();';
		echo 'setTimeout(() => {';
		echo 'const days = document.querySelectorAll(\'.day\');';
		echo 'days.forEach((day) => {';
		echo 'if (';
		echo '!day.classList.contains(\'prev-date\') &&';
		echo 'day.innerHTML === e.target.innerHTML';
		echo ') {';
		echo 'day.classList.add(\'active\');';
		echo '}';
		echo '});';
		echo '}, 100);';
		echo 'pointerEvent(e);';
		echo '} else if (e.target.classList.contains(\'next-date\')) {';
		echo 'nextMonth();';
		echo 'setTimeout(() => {';
		echo 'const days = document.querySelectorAll(\'.day\');';
		echo 'days.forEach((day) => {';
		echo 'if (';
		echo '!day.classList.contains(\'next-date\') &&';
		echo 'day.innerHTML === e.target.innerHTML';
		echo ') {';
		echo 'day.classList.add(\'active\');';
		echo '}';
		echo '});';
		echo '}, 100);';
		echo 'pointerEvent(e);';
		echo '} else {';
		echo 'e.target.classList.add(\'active\');';
		echo '}';
		echo '});';
		echo '});';
		echo '}';

		echo 'function pointerEvent(e) {';
		echo 'activeDay = Number(e.target.innerHTML);';
		echo 'getActiveDay(e.target.innerHTML);';
		echo 'updateEvents(Number(e.target.innerHTML));';
		echo '}';

		echo 'function getActiveDay(date) {';
		echo 'const day = new Date(year, month, date);';
		echo 'const dayName = day.toLocaleDateString(\'' . $language . '\', { weekday: \'short\' });';
		echo 'eventDay.innerHTML = dayName;';
		echo 'eventDate.innerHTML = day.toLocaleDateString(\'' . $language . '\', {  day: \'numeric\', month: \'long\', year: \'numeric\' })';
		echo '}';

		echo 'function updateEvents(date) {';
		echo 'let events = \'\';';
		echo 'eventsArr.forEach((eventObj) => {';
		echo 'const eventdate = new Date(eventObj[0]);';
		echo 'if (';
		echo 'date === eventdate.getDate() &&';
		echo 'month === eventdate.getMonth() &&';
		echo 'year === eventdate.getFullYear()';
		echo ') {';
		echo 'eventObj[1].forEach((event) => {';

		echo 'var timeFrom = new Date(\'1970-01-01T\' + event.TIME_FROM).toLocaleString(';
		echo '\'' . $language . '\',';
		echo '{';
		echo 'hour: \'numeric\',';
		echo 'minute: \'numeric\',';
		echo '},';
		echo ');';
		echo 'var timeTo = new Date(\'1970-01-01T\' + event.TIME_TO).toLocaleString(';
		echo '\'' . $language . '\',';
		echo '{';
		echo 'hour: \'numeric\',';
		echo 'minute: \'numeric\',';
		echo '},';
		echo ');';
		echo 'var details = event.DETAILS;';
		echo 'if (!details) {';
		echo 'details = \'\';';
		echo '}';
		echo 'events += `';
		echo '<div class="event">';
		echo '<div class="title">';
		echo '${event.EVENT_TITLE}';
		echo '</div>';
		echo '<div class="event-time">';
		echo '${timeFrom} - ${timeTo}';
		echo '</div>';
		echo '<div class="event-details">';
		echo '${details}';
		echo '</div>';
		echo '</div>';
		echo '`;';
		echo '});';
		echo '}';
		echo '});';

		echo 'if (events === \'\') {';
		echo 'events = `';
		echo '<div class="no-event">';
		echo '<h3>' . str_replace("\"", "", json_encode($content['error']['item'])) . '</h3>';
		echo '</div>';
		echo '`;';
		echo '}';

		echo 'eventsContainer.innerHTML = events;';
		echo '}';
	}
}
