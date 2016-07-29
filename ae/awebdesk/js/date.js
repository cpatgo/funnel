// date.js

var adesk_date = {

    ms_second: 1000,
    ms_minute: 1000 * 60,
    ms_hour:   1000 * 60 * 60,
    ms_day:    1000 * 60 * 60 * 24,
    ms_week:   1000 * 60 * 60 * 24 * 7

};

function adesk_date_today() {
    var d = new Date();
    d.setHours(0);
    d.setMinutes(0);
    d.setSeconds(0);
    d.setMilliseconds(0);
    return d;
}

function adesk_date_month_first(d) {
    var r = new Date(d);
    r.setDate(1);
    return r;
}

function adesk_date_month_end(d) {
    var r = adesk_date_month_first(d);
    r.setMonth(d.getMonth() + 1);
    return new Date(r - adesk_date.ms_day);
}

function adesk_date_month_days(d) {
    var r = adesk_date_month_end(d);
    return r.getDate();
}

function adesk_date_month_next(d) {
    var r = new Date(d);
    r.setMonth(d.getMonth() + 1);
    return r;
}

function sql2date(str) {
	var arr = str.match(/\d+/g);
	if ( arr.length != 6 && arr.length != 3 ) return new Date;
	if ( arr.length == 3 ) {
		if ( str.match(/-/) ) {
			// dates
			return new Date(arr[0], arr[1] - 1, arr[2]);
		} else {
			// times
			return new Date(0, 0, 0, arr[0], arr[1], arr[2]);
		}
	}
	return new Date(arr[0], arr[1] - 1, arr[2], arr[3], arr[4], arr[5]);
}



/**
 * Date.format()
 * string format ( string format )
 * Formatting rules according to http://php.net/strftime
 *
 * Copyright (C) 2006  Dao Gottwald
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * Contact information:
 *   Dao Gottwald  <dao at design-noir.de>
 *
 * @version  0.7
 * @todo     %g, %G, %U, %V, %W, %z, more/better localization
 * @url      http://design-noir.de/webdev/JS/Date.format/
 */

var _lang = (navigator.systemLanguage || navigator.userLanguage || navigator.language || navigator.browserLanguage || '').replace(/-.*/,'');
switch (_lang) {
	case 'de':
		Date._l10n = {
			days: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
			months: ['Januar','Februar','M\u00E4rz','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
			date: '%e.%m.%Y',
			time: '%H:%M:%S'};
		break;
	case 'es':
		Date._l10n = {
			days: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','S\u00E1bado'],
			months: ['enero','febrero','marcha','abril','puede','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'],
			date: '%e.%m.%Y',
			time: '%H:%M:%S'};
		break;
	case 'fr':
		Date._l10n = {
			days: ['dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi'],
			months: ['janvier','f\u00E9vrier','mars','avril','mai','juin','juillet','ao\u00FBt','septembre','octobre','novembre','decembre'],
			date: '%e/%m/%Y',
			time: '%H:%M:%S'};
		break;
	case 'it':
		Date._l10n = {
			days: ['domenica','luned\u00EC','marted\u00EC','mercoled\u00EC','gioved\u00EC','venerd\u00EC','sabato'],
			months: ['gennaio','febbraio','marzo','aprile','maggio','giugno','luglio','agosto','settembre','ottobre','novembre','dicembre'],
			date: '%e/%m/%y',
			time: '%H.%M.%S'};
		break;
	case 'pt':
		Date._l10n = {
			days: ['Domingo','Segunda-feira','Ter\u00E7a-feira','Quarta-feira','Quinta-feira','Sexta-feira','S\u00E1bado'],
			months: ['Janeiro','Fevereiro','Mar\u00E7o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
			date: '%e/%m/%y',
			time: '%H.%M.%S'};
		break;
	case 'en':
	default:
		Date._l10n = {
			days: ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
			months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
			date: '%Y-%m-%e',
			time: '%H:%M:%S'};
		break;
}
Date._pad = function(num, len) {
	for (var i = 1; i <= len; i++)
		if (num < Math.pow(10, i))
			return new Array(len-i+1).join(0) + num;
	return num;
};
Date.prototype.format = function(format) {
	if (format.indexOf('%%') > -1) { // a literal `%' character
		format = format.split('%%');
		for (var i = 0; i < format.length; i++)
			format[i] = this.format(format[i]);
		return format.join('%');
	}
	format = format.replace(/%D/g, '%m/%d/%y'); // same as %m/%d/%y
	format = format.replace(/%r/g, '%I:%M:%S %p'); // time in a.m. and p.m. notation
	format = format.replace(/%R/g, '%H:%M:%S'); // time in 24 hour notation
	format = format.replace(/%T/g, '%H:%M:%S'); // current time, equal to %H:%M:%S
	format = format.replace(/%x/g, Date._l10n.date); // preferred date representation for the current locale without the time
	format = format.replace(/%X/g, Date._l10n.time); // preferred time representation for the current locale without the date
	var dateObj = this;
	return format.replace(/%([aAbhBcCdegGHIjmMnpStuUVWwyYzZ])/g, function(match0, match1) {
		return dateObj.format_callback(match0, match1);
	});
}
Date.prototype.format_callback = function(match0, match1) {
	switch (match1) {
		case 'a': // abbreviated weekday name according to the current locale
			return Date._l10n.days[this.getDay()].substr(0,3);
		case 'A': // full weekday name according to the current locale
			return Date._l10n.days[this.getDay()];
		case 'b':
		case 'h': // abbreviated month name according to the current locale
			return Date._l10n.months[this.getMonth()].substr(0,3);
		case 'B': // full month name according to the current locale
			return Date._l10n.months[this.getMonth()];
		case 'c': // preferred date and time representation for the current locale
			return this.toLocaleString();
		case 'C': // century number (the year divided by 100 and truncated to an integer, range 00 to 99)
			return Math.floor(this.getFullYear() / 100);
		case 'd': // day of the month as a decimal number (range 01 to 31)
			return Date._pad(this.getDate(), 2);
		case 'e': // day of the month as a decimal number, a single digit is preceded by a space (range ' 1' to '31')
			return Date._pad(this.getDate(), 2);
		/*case 'g': // like %G, but without the century
			return ;
		case 'G': // The 4-digit year corresponding to the ISO week number (see %V). This has the same format and value as %Y, except that if the ISO week number belongs to the previous or next year, that year is used instead
			return ;*/
		case 'H': // hour as a decimal number using a 24-hour clock (range 00 to 23)
			return Date._pad(this.getHours(), 2);
		case 'I': // hour as a decimal number using a 12-hour clock (range 01 to 12)
			var x = this.getHours() % 12;
			if ( x == 0 ) x = 12;
			return Date._pad(x, 2);
		case 'j': // day of the year as a decimal number (range 001 to 366)
			return Date._pad(this.getMonth() * 30 + Math.ceil(this.getMonth() / 2) + this.getDay() - 2 * (this.getMonth() > 1) + (!(this.getFullYear() % 400) || (!(this.getFullYear() % 4) && this.getFullYear() % 100)), 3);
		case 'm': // month as a decimal number (range 01 to 12)
			return Date._pad(this.getMonth() + 1, 2);
		case 'M': // minute as a decimal number
			return Date._pad(this.getMinutes(), 2);
		case 'n': // newline character
			return '\n';
		case 'p': // either `am' or `pm' according to the given time value, or the corresponding strings for the current locale
			return this.getHours() < 12 ? 'am' : 'pm';
		case 'S': // second as a decimal number
			return Date._pad(this.getSeconds(), 2);
		case 't': // tab character
			return '\t';
		case 'u': // weekday as a decimal number [1,7], with 1 representing Monday
			return this.getDay() || 7;
		/*case 'U': // week number of the current year as a decimal number, starting with the first Sunday as the first day of the first week
			return ;
		case 'V': // The ISO 8601:1988 week number of the current year as a decimal number, range 01 to 53, where week 1 is the first week that has at least 4 days in the current year, and with Monday as the first day of the week. (Use %G or %g for the year component that corresponds to the week number for the specified timestamp.)
			return ;
		case 'W': // week number of the current year as a decimal number, starting with the first Monday as the first day of the first week
			return ;*/
		case 'w': // day of the week as a decimal, Sunday being 0
			return this.getDay();
		case 'y': // year as a decimal number without a century (range 00 to 99)
			return this.getFullYear().toString().substr(2);
		case 'Y': // year as a decimal number including the century
			return this.getFullYear();
		/*case 'z':
		case 'Z': // time zone or name or abbreviation
			return ;*/
		default:
			return match0;
	}
}


/**
 * strftime() - JavaScript porting from PHP's strftime: http://php.net/strftime
 * string strftime ( string format [, int timestamp] )
 *
 * Copyright (C) 2006  Dao Gottwald  <dao at design-noir.de>
 *
 * Licensed under the terms of the GNU Lesser General Public License:
 *   http://www.opensource.org/licenses/lgpl-license.php
 *
 * @version  1.0
 * @require  Date.format()
 * @url      http://design-noir.de/webdev/JS/Date.format/
 */

function strftime(format, timestamp) {
	var t = new Date;
	if (typeof timestamp != 'undefined')
		t.setTime(timestamp * 1000);
	return t.format(format);
}

function tstamp() { return Math.round(((new Date()).getTime()-Date.UTC(1970,0,1))/1000); }