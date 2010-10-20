<?php

/**
 * Data helper functions.
 *
 */
class qdat {

	public static function getDataFromFieldLine($line) {
		$parts = qstr::breakIntoParts($line, '::');
		$data = count($parts) > 1 ? $parts[1] : "";
		return $data;
	}

	public static function getDataFromFieldIndex($rawFieldLines, $index) {
		$rawFieldLine = count($rawFieldLines) > $index ? $rawFieldLines[$index] : "";
		$parts = qstr::breakIntoParts($rawFieldLine, '::');
		$data = count($parts) > 1 ? $parts[1] : "";
		return $data;
	}

	/**
	 * Convert a base date to american letter date, e.g. "2004-12-15" to "Thursday, December 15, 2004".
	 *
	 * @param string $baseDate
	 * @return string
	 */
	public static function getNiceAmericanDayAndDate($baseDate) {
		$r = '';
		$niceAmericanDate = qdat::getNiceAmericanDate($baseDate);
		$dayOfWeek = qdat::getDayOfWeek($baseDate);
		$r .= $dayOfWeek . ', ' . $niceAmericanDate;
		return $r;
	}

	public static function getNiceShortAmericanDayAndDateWithoutYearAndTime($baseDate) {
		$r = '';
		$niceAmericanDate = qdat::getNiceAmericanDate($baseDate, 'withoutYear');
		$dayOfWeek = qdat::getDayOfWeek($baseDate);
		$r .= $dayOfWeek . ', ' . $niceAmericanDate . ' at ' . qdat::getMinuteTimeFromDateTime($baseDate);
		return $r;
	}

	/**
	 * Convert a base date to american letter date, e.g. "2004-12-15" to "December 15, 2004".
	 *
	 * @param string $baseDate
	 * @return string
	 */
	public static function getNiceAmericanDate($baseDate, $option = '') {
		$r = "";
		$year = qdat::getYearFromDate($baseDate);
		$monthNumber = qdat::getFullMonthNumberFromDate($baseDate);
		$smartDigitDay = qdat::getSmartDigitDayFromDate($baseDate);
		$monthName = qdat::getAmericanMonthNameFromNumber($monthNumber);
		if(!qstr::isEmpty($baseDate)) {
			if($option == 'withoutYear') {
				$r .= $monthName . ' ' . $smartDigitDay;
			} else {
				$r .= $monthName . ' ' . $smartDigitDay . ', ' . $year;
			}
		}
		return $r;
	}

	/**
	 * Returns "2009" from "2009-12-31".
	 *
	 * @param string $selectedDate
	 * @return string
	 */
	public static function getYearFromDate($date) {
		return substr($date, 0, 4);
	}

	/**
	 * Return month number from base date, e.g. 2004-12-01 returns "1".
	 *
	 * @param string $baseDate
	 * @return string
	 */
	public static function getSmartDigitDayFromDate($baseDate) {
		$r = '';
		$r .= substr($baseDate,8,2);
		$r = qstr::chopLeft($r, '0');
		return $r;
	}

	/**
	 * Get e.g. "January" from 1.
	 *
	 * @param int $monthNumber
	 * @return string
	 */
	public static function getAmericanMonthNameFromNumber($monthNumber) {
		$r = '';
		switch($monthNumber) {
			case 1;
			$r .= 'January';
			break;
			case 2;
			$r .= 'February';
			break;
			case 3;
			$r .= 'March';
			break;
			case 4;
			$r .= 'April';
			break;
			case 5;
			$r .= 'May';
			break;
			case 6;
			$r .= 'June';
			break;
			case 7;
			$r .= 'July';
			break;
			case 8;
			$r .= 'August';
			break;
			case 9;
			$r .= 'September';
			break;
			case 10;
			$r .= 'October';
			break;
			case 11;
			$r .= 'November';
			break;
			case 12;
			$r .= 'December';
			break;
			default:
				$r .= '(UNKNOWN MONTH NUMBER: ' . $monthNumber . ')';
				break;
		}
		return $r;
	}

	/**
	 * Get e.g. "Thursday" from a date sent in standard format.
	 *
	 * @param string $baseDate
	 * @return string
	 */
	public static function getDayOfWeek($baseDate) {
		$r = "";
		$dateArray = qdat::getDateArray($baseDate);
		$r = $dateArray["weekday"];
		return $r;
	}

	/**
	 * Takes "2004-12-15" and returns a PHP array with lots of info in it to pick out.
	 *
	 * @param string $baseDate
	 * @return array
	 */
	public static function getDateArray($baseDate) {
		$ra = array();
		$month = substr($baseDate,5,2);
		$day = substr($baseDate,8,2);
		$year = substr($baseDate,0,4);
		$ra = getDate(mktime(9,0,0,$month,$day,$year));
		return $ra;
	}

	/**
	 * Returns "12" from "2009-12-31".qqqqq
	 *
	 * @param string $selectedDate
	 * @return string
	 */
	public static function getFullMonthNumberFromDate($date) {
		return substr($date, 5, 2);
	}

	/**
	 * Check if it is a valid date entry, e.g. "2008-12-31 14:50:59".
	 *
	 * @param string $dateTime
	 * @return boolean
	 */
	public static function isValidDateTime($dateTime) {
		//TODO: tighten this up, it still allows, e.g. 2010-18-39
		if(qreg::matches('^([0-9]{4})-([0-1][0-9])-([0-3][0-9]) ([0-1][0-9]|[2][0-3]):([0-5][0-9]):([0-5][0-9])$', $dateTime)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Check if it is a valid date entry, e.g. "2008-12-31".
	 *
	 * @param string $dateTime
	 * @return boolean
	 */
	public static function isValidDate($potentialDate) {
		//TODO: tighten this up, it still allows, e.g. 2010-18-39
		if(qreg::matches('^([0-9]{4})-([0-1][0-9])-([0-3][0-9])$', $potentialDate)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Converts "2008-12-31 23:59:59" to "2008-12-31"
	 *
	 * @param string $dateTime
	 * @return string
	 */
	public static function convertDateTimeToDate($dateTime) {
		return substr($dateTime, 0,10);
	}

	/**
	 * Returns a nice date based on language, e.g. "Freitag, 4. Juli 2008", "Friday, July 4, 2008", etc.
	 *
	 * @param string $theDate e.g. 2008-12-31
	 * @param string $languageIdCode e.g. "de" or "en"
	 * @return string "Freitag, 4. Juli 2008"
	 */
	public static function getNiceDate($theDate, $languageIdCode = 'en') {
			
		$r = '';

		if(qdat::isValidDateTime($theDate)) {
			$theDate = qdat::convertDateTimeToDate($theDate);
		}
		if(qdat::isValidDate($theDate)) {

			//variables
			$year = substr($theDate, 0, 4);
			$monthNumber = intval(substr($theDate, 5,2));
			$day = substr($theDate, 8, 2);
			$dateArray = getDate(mktime(9,0,0,$monthNumber,$day,$year));
			$dayOfWeekIndex = $dateArray['wday']; // e.g. 0 (sunday) through 6 (saturday)
			$dayOfWeek = qdat::getDayOfWeekWithIndex($dayOfWeekIndex, $languageIdCode);
			$monthName = qdat::getMonthNameFromMonthNumber($monthNumber, $languageIdCode);
			//build it
			switch ($languageIdCode) {
				case 'nl':
					$r = $dayOfWeek . ', ' . $day . ' ' . $monthName . ' ' . $year;
					break;
				case 'de':
					$r = $dayOfWeek . ', ' . $day . '. ' . $monthName . ' ' . $year;
					break;
				case 'en':
					$r = $dayOfWeek . ', ' . $monthName . ' ' . $day . ', ' . $year;
					break;
				case 'fr':
					$r = $dayOfWeek . ' ' . $day . ' ' . $monthName . ' ' . $year;
					break;
				default:
					$r = '';
					break;
			}
		}

		return $r;

	}

	/**
	 * Returns the current date, e.g. "2008-09-22".
	 *
	 * @return string
	 */
	public static function getCurrentDate() {
		return date("Y-m-d");
	}

	/**
	 * Takes an day-of-week index (4) and a language (de) and returns the day of week (e.g. Donnerstag).
	 *
	 * @param int $dayOfWeekIndex e.g. 0 (sunday) through 6 (saturday)
	 * @param string $languageIdCode e.g. "de" for Germany
	 * @return string e.g. "Donnerstag"
	 */
	public static function getDayOfWeekWithIndex($dayOfWeekIndex, $languageIdCode='en') {
		//German
		$dayOfWeek[0]['de'] = 'Sonntag';
		$dayOfWeek[1]['de'] = 'Montag';
		$dayOfWeek[2]['de'] = 'Dienstag';
		$dayOfWeek[3]['de'] = 'Mittwoch';
		$dayOfWeek[4]['de'] = 'Donnerstag';
		$dayOfWeek[5]['de'] = 'Freitag';
		$dayOfWeek[6]['de'] = 'Samstag';
		//English
		$dayOfWeek[0]['en'] = 'Sunday';
		$dayOfWeek[1]['en'] = 'Monday';
		$dayOfWeek[2]['en'] = 'Tuesday';
		$dayOfWeek[3]['en'] = 'Wednesday';
		$dayOfWeek[4]['en'] = 'Thursday';
		$dayOfWeek[5]['en'] = 'Friday';
		$dayOfWeek[6]['en'] = 'Saturday';
		//French
		$dayOfWeek[0]['fr'] = 'Dimanche';
		$dayOfWeek[1]['fr'] = 'Lundi';
		$dayOfWeek[2]['fr'] = 'Mardi';
		$dayOfWeek[3]['fr'] = 'Mercredi';
		$dayOfWeek[4]['fr'] = 'Jeudi';
		$dayOfWeek[5]['fr'] = 'Samedi';
		$dayOfWeek[6]['fr'] = 'Samstag';
		return $dayOfWeek[$dayOfWeekIndex][$languageIdCode];
	}

	/**
	 * Takes a month number (e.g. 1 = January) and a language code (e.g. "de" = German) and returns e.g. "Januar"
	 *
	 * @param int $monthNumber e.g. 1 = January
	 * @param string $languageIdCode e.g. de = Germany
	 * @return string e.g. "Januar"
	 */
	public static function getMonthNameFromMonthNumber($monthNumber, $languageIdCode) {
		//German
		$monthName[1]['de'] = 'Januar';
		$monthName[2]['de'] = 'Februar';
		$monthName[3]['de'] = 'März';
		$monthName[4]['de'] = 'April';
		$monthName[5]['de'] = 'Mai';
		$monthName[6]['de'] = 'Juni';
		$monthName[7]['de'] = 'Juli';
		$monthName[8]['de'] = 'August';
		$monthName[9]['de'] = 'September';
		$monthName[10]['de'] = 'Oktober';
		$monthName[11]['de'] = 'November';
		$monthName[12]['de'] = 'December';
		//English
		$monthName[1]['en'] = 'January';
		$monthName[2]['en'] = 'February';
		$monthName[3]['en'] = 'March';
		$monthName[4]['en'] = 'April';
		$monthName[5]['en'] = 'May';
		$monthName[6]['en'] = 'June';
		$monthName[7]['en'] = 'July';
		$monthName[8]['en'] = 'August';
		$monthName[9]['en'] = 'September';
		$monthName[10]['en'] = 'October';
		$monthName[11]['en'] = 'November';
		$monthName[12]['en'] = 'December';
		//French
		$monthName[1]['fr'] = 'janvier';
		$monthName[2]['fr'] = 'février';
		$monthName[3]['fr'] = 'mars';
		$monthName[4]['fr'] = 'avril';
		$monthName[5]['fr'] = 'mai';
		$monthName[6]['fr'] = 'juin';
		$monthName[7]['fr'] = 'juillet';
		$monthName[8]['fr'] = 'août';
		$monthName[9]['fr'] = 'septembre';
		$monthName[10]['fr'] = 'octobre';
		$monthName[11]['fr'] = 'novembre';
		$monthName[12]['fr'] = 'décembre';
		return $monthName[$monthNumber][$languageIdCode];
	}

	/**
	 * Returns e.g. 25.12.2010
	 */
	public static function getSimpleGermanDate($theDate) {
		return date('d.m.Y', strtotime($theDate));
	}

	/**
	 * Returns e.g. 25.Dezember 2010
	 */
	public static function getSimpleGermanDateWithMonthName($theDate) {
		$monthNumber = intval(substr($theDate, 5,2));
		$monthName = qdat::getMonthNameFromMonthNumber($monthNumber, 'de');
		return substr($theDate, 8, 2) . '. '. $monthName . ' ' . substr($theDate, 0, 4);

	}

	/**
	 * Gets the number of days between two dates.
	 *
	 * @param date $date1, e.g. 2009-12-31
	 * @param date $date2, e.g. 2009-12-31
	 * @return int
	 */
	public static function getDaysBetweenTwoDates($date1, $date2) {
		$date1Seconds =qdat::getAbsoluteSeconds($date1);
		$date2Seconds =qdat::getAbsoluteSeconds($date2);
		$totalSeconds = $date2Seconds - $date1Seconds;
		$daysDecimal = qdat::convertSecondsToDays($totalSeconds);
		$daysRounded = intval($daysDecimal);
		return $daysRounded;
	}

	/**
	 * Gets the number of days between two dates.
	 *
	 * @param date $date1, e.g. 2009-12-31
	 * @param date $date2, e.g. 2009-12-31
	 * @return int
	 */
	public static function getAmountOfTimeToGo($datetime1, $datetime2, $prefix = '') {
		$datetime1Seconds =qdat::getAbsoluteSeconds($datetime1);
		$datetime2Seconds =qdat::getAbsoluteSeconds($datetime2);
		$totalSeconds = $datetime2Seconds - $datetime1Seconds;
		if($totalSeconds > 86400) {
			$daysDecimal = qdat::convertSecondsToDays($totalSeconds);
			$daysRounded = intval($daysDecimal);
			return $prefix . qstr::smartPlural($daysRounded, 'days');
		} else if($totalSeconds <= 0) {
			return '';
		} else {
			$numberOfHoursToGo = qdat::getHoursFromSeconds($totalSeconds);
			$numberOfMinutesToGo = qdat::getMinutesFromSeconds($totalSeconds) - ($numberOfHoursToGo * 60);
			if($numberOfHoursToGo != 0) {
				return $prefix . ' ' . qstr::smartPlural($numberOfHoursToGo, 'hours') . ' ' . qstr::smartPlural($numberOfMinutesToGo, 'mins');
			} else {
				return $prefix . qstr::smartPlural($numberOfMinutesToGo, 'mins');
			}
		}
	}

	/**
	 * This returns the number of seconds since Jan. 1, 1970 in a MySQL-formatted date/time string, e.g. "2008-12-31 23:59:59"
	 *
	 * @param string $dateTime
	 * @return string MySQL-formatted text date/time
	 */
	public static function getAbsoluteSeconds($dateTime) {
		return strtotime($dateTime);
	}


	/**
	 * Converts seconds to number of days, used in date calculations.
	 *
	 * @param int $seconds
	 * @return int
	 */
	public static function convertSecondsToDays($seconds) {
		$minutes = $seconds / 24;
		$days = $minutes / 3600;
		$exactDays = intval($days);
		return $exactDays;
	}

	/**
	 * Takes date in past and verb and shows e.g. "added 33 minutes ago" or "just added" or "added 2 days ago".
	 *
	 * @param Output $output
	 * @param string $dateTimeToProcess
	 * @param string $verb
	 * @return string
	 */
	public static function getSmartVerbPhrasePast($dateTimeToProcess, $verb) {
		$r = '';
		$now = qdat::getCurrentDateAndTime();
		$americanNiceDate = qdat::getNiceDate($dateTimeToProcess);
		$seconds = qdat::getDurationInSeconds($dateTimeToProcess, $now);
		if($seconds < 60) {
			$amountOfSecondsPhrase = qstr::smartPlural($seconds, 'seconds');
			if(qstr::AreEqual($amountOfSecondsPhrase, '0 seconds')) {
				$amountOfSecondsPhrase = '1 second';
			}
			$r .= '<span class="justAdded">' . qstr::hardenSpaces($verb . ' ' . $amountOfSecondsPhrase . ' ago</span>');
		} else {
			//within an hour ago
			if($seconds < 60 * 60) {
				$minutes = qdat::getSecondsToWholeMinutes($seconds);
				$r .= '<span class="recentlyAdded">' . qstr::hardenSpaces($verb . ' ' . qstr::smartPlural($minutes, 'minutes')) . ' ago</span>';
			} else {
				//within the last 24 hours
				if($seconds < 3600 * 24) {
					$hours = qdat::getSecondsToWholeHours($seconds);
					$r .= '<span class="recentlyAdded">' . $verb . ' ' . qstr::smartPlural($hours, 'hours') . ' ago</span>';
				} else {
					//within the last 7 days
					if($seconds < (3600 * 24) * 7) {
						$days = qdat::getSecondsToWholeDays($seconds);
						$r .= $verb . ' ' . qdat::smartDaysAgo($days);
					} else {
						//it was so long ago, just display the date
						$r .= $verb . ' on ' . $americanNiceDate;
					}
				}
			}
		}
		//hackfix occasional space before issue, e.g.: <span class="recentlyAdded"> 2&nbsp;hours ago</span>before
		$r = str_replace('<span class="recentlyAdded">&nbsp;', '<span class="recentlyAdded">', $r);
		return $r;
	}

	/**
	 * Returns the current date and time in format: "2005-12-31 23:59:59".
	 *
	 * @return string
	 */
	public static function getCurrentDateAndTime() {
		if(qsys::getSiteLocation() == 'online') {
			$currentDateTimeOnServer = qdat::getCurrentDateAndTimeOnCurrentComputer();
			$currentAbsoluteSeconds = qdat::getAbsoluteSeconds($currentDateTimeOnServer);
			$adjustedCurrentAbsoluteSeconds = $currentAbsoluteSeconds + (Config::getServerOffsetInHours() * -1 * 3600);
			return qdat::convertAbsoluteSecondsToDateTime($adjustedCurrentAbsoluteSeconds);
		} else {
			return qdat::getCurrentDateAndTimeOnCurrentComputer();
		}
	}

	/**
	 * Returns the current date and time in format: "2005-12-31 23:59:59".
	 *
	 * @return string
	 */
	public static function getCurrentDateAndTimeOnCurrentComputer() {
		return date ( 'Y-m-d H:i:s');
	}

	/**
	 * Returns the duration in seconds between two date/time variables.
	 *
	 * @param string $dateTime1
	 * @param string $dateTime2
	 * @return int
	 */
	public static function getDurationInSeconds($dateTime1, $dateTime2) {
		$ri = 0;
		$dateTime1Seconds = qdat::getAbsoluteSeconds($dateTime1);
		$dateTime2Seconds = qdat::getAbsoluteSeconds($dateTime2);
		$ri = $dateTime1Seconds - $dateTime2Seconds;
		$ri = abs($ri);
		return $ri;
	}

	/**
	 * Converts seconds to whole hours.
	 *
	 * @param int $seconds
	 * @return int
	 */
	public static function getSecondsToWholeHours($seconds) {
		return round($seconds / 3600);
	}

	/**
	 * Converts seconds to whole minutes, e.g. 129 to 2.
	 *
	 * @param int $seconds
	 * @return int
	 */
	public static function getSecondsToWholeMinutes($seconds) {
		return round($seconds / 60);
	}

	/**
	 * Converts seconds to whole days.
	 *
	 * @param int $seconds
	 * @return int
	 */
	public static function getSecondsToWholeDays($seconds) {
		return round($seconds / (3600 * 24));
	}

	/**
	 * If days ago is 1 then returns "yesterday".
	 *
	 * @param int $days
	 * @return string
	 */
	public static function smartDaysAgo($days) {
		$r = '';
		if($days == 1) {
			$r .= 'yesterday';
		} else {
			$r .= qstr::smartPlural($days, 'days') . ' ago';
		}
		return $r;
	}

	/**
	 * Return e.g. "Jun 04".
	 *
	 * @param string $theDate
	 * @return string
	 */
	public static function getShortMonthDay($theDate) {
		$monthNumber = intval(substr($theDate, 5,2));
		$monthName = substr(qdat::getMonthNameFromMonthNumber($monthNumber, 'en'), 0, 3);
		$day = substr($theDate, 8, 2);

		return $monthName . '&nbsp;' . $day;
	}

	/**
	 * Return e.g. "Fri Jun 04".
	 *
	 * @param string $theDate
	 * @return string
	 */
	public static function getShortDayOfWeekMonthDay($theDate) {
		$monthNumber = intval(substr($theDate, 5,2));
		$monthName = substr(qdat::getMonthNameFromMonthNumber($monthNumber, 'en'), 0, 3);
		$day = substr($theDate, 8, 2);
		$dayOfWeekAbbreviation = qdat::getDayOfWeekAbbreviation($theDate);

		return $dayOfWeekAbbreviation . '&nbsp;' . $monthName . '&nbsp;' . $day;
	}

	public static function getDayOfWeekAbbreviation($theDate) {
		return substr(qdat::getDayOfWeek($theDate), 0, 3);
	}

	/**
	 * Converts '2007-12-31 09:23:11' and returns '09:23'.
	 *
	 * @param string $dateTime
	 * @return string
	 */
	public static function getMinuteTimeFromDateTime($dateTime) {
		return substr($dateTime, 11, 5);
	}

	public static function getHoursFromSeconds($seconds) {
		return floor($seconds / 3600);
	}

	public static function getMinutesFromSeconds($seconds) {
		return round($seconds / 60);
	}

	/**
	 * Converts e.g. "238473843" to "2005-09-03 13:59:59".
	 *
	 * @param int $numberOfSeconds
	 * @return string MySQL-formatted text date/time
	 */
	public static function convertAbsoluteSecondsToDateTime($numberOfSeconds) {
		return date("Y-m-d H:i:s", $numberOfSeconds);
	}

}