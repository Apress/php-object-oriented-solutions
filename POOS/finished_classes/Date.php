<?php
/**
 * A user-friendly class for handling dates.
 * 
 * Extends the DateTime class in >= PHP 5.2 to build and format dates without the
 * need to memorize PHP date format specifiers. Eliminates inaccurate results when
 * modifying dates to add or subtract days, weeks, months, or years.
 * Static dateDiff() method calculates number of days between two dates. 
 * 
 * @package Pos
 * @author David Powers
 * @copyright David Powers 2008
 * @version 1.0.2
 */
class Pos_Date extends DateTime {
	/**#@+
	 *
	 * @var int
	 */
	/**
	 * Year as four-digit number.
	 */
	protected $_year;
	/**
	 * Month number (no leading zero).
	 */
	protected $_month;
	/**
	 * Day of month (no leading zero).
	 */
	protected $_day;
	/**#@-*/
	
	#####################################################
	# STATIC METHOD                                     #
	#####################################################
	/**
	 * Static method to calculate the number of days between two dates.
	 *
	 * @param Pos_Date $startDate Starting date.
	 * @param Pos_Date $endDate Finishing date.
	 * @return int Number of days between start and end dates.
	 */
	static public function dateDiff(Pos_Date $startDate, Pos_Date $endDate) {
		$start = gmmktime ( 0, 0, 0, $startDate->_month, $startDate->_day, $startDate->_year );
		$end = gmmktime ( 0, 0, 0, $endDate->_month, $endDate->_day, $endDate->_year );
		return ($end - $start) / (60 * 60 * 24);
	}
	
	####################################################
	# OVERRIDDEN METHODS                               #
	####################################################
	/**
	 * Constructor method.
	 * 
	 * The constructor overrides the PHP DateTime class and sets the value
	 * of the internal properties. Creates an object for the current date
	 * and time only, but accepts an optional argument to set the time zone.
	 * 
	 * @param DateTimeZone $timezone string Optional DateTimeZone object.
	 * @return Pos_Date
	 */
	public function __construct($timezone = null) {
		// call the parent constructor
		if ($timezone) {
			parent::__construct ( 'now', $timezone );
		} else {
			parent::__construct ( 'now' );
		}
		// assign the values to the class properties
		$this->_year = ( int ) $this->format ( 'Y' );
		$this->_month = ( int ) $this->format ( 'n' );
		$this->_day = ( int ) $this->format ( 'j' );
	}
	
	/**
	 * Resets the current time. 
	 * 
	 * This overrides the parent method to prevent out-of-range units
	 * being accepted.
	 *
	 * @param int $hours    Number between 0 and 23.
	 * @param int $minutes  Number between 0 and 59.
	 * @param int $seconds  (Optional) Number beween 0 and 59; default 0. 
	 */
	public function setTime($hours, $minutes, $seconds = 0) {
		if (! is_numeric ( $hours ) || ! is_numeric ( $minutes ) || ! is_numeric ( $seconds )) {
			throw new Exception ( 'setTime() expects two or three numbers separated by commas in the order: hours, minutes, seconds' );
		}
		$outOfRange = false;
		if ($hours < 0 || $hours > 23) {
			$outOfRange = true;
		}
		if ($minutes < 0 || $minutes > 59) {
			$outOfRange = true;
		}
		if ($seconds < 0 || $seconds > 59) {
			$outOfRange = true;
		}
		if ($outOfRange) {
			throw new Exception ( 'Invalid time.' );
		}
		parent::setTime ( $hours, $minutes, $seconds );
	}
	
	/**
	 * Changes the date represented by the object.
	 * 
	 * Overrides the parent setDate() method, and checks
	 * that the arguments supplied constitute a valid date.
	 * 
	 * @param  int   $year   The year as a four-digit number.
	 * @param  int   $month  The month as a number between 1 and 12.
	 * @param  int   $day    The day as a number between 1 and 31.
	 */
	public function setDate($year, $month, $day) {
		if (! is_numeric ( $year ) || ! is_numeric ( $month ) || ! is_numeric ( $day )) {
			throw new Exception ( 'setDate() expects three numbers separated by commas in the order: year, month, day.' );
		}
		if (! checkdate ( $month, $day, $year )) {
			throw new Exception ( 'Non-existent date.' );
		}
		parent::setDate ( $year, $month, $day );
		$this->_year = ( int ) $year;
		$this->_month = ( int ) $month;
		$this->_day = ( int ) $day;
	}
	
	/**
	 * Overrides parent modify() method and throws exception to prevent use.
	 * 
	 * The parent method emulates strtotime(), which produces inaccurate 
	 * results if out-of-range values are used (e.g., September 31 is 
	 * converted to October 1). Throws an exception to prevent its
	 * use as a public method.
	 */
	public function modify() {
		throw new Exception ( 'modify() has been disabled.' );
	}
	
	####################################################
	# PUBLIC METHODS FOR SETTING DATE                  #
	####################################################
	/**
	 * Sets the date using input in MM/DD/YYYY format.
	 * 
	 * Separator can be any of the following: dash, forward slash, space, 
	 * colon, or period. After extracting the year, month, and day parts,
	 * passes them to the overridden setDate() method.
	 *
	 * @param string $USDate  Date string in US format (MM/DD/YYYY).
	 */
	public function setMDY($USDate) {
		$dateParts = preg_split ( '{[-/ :.]}', $USDate );
		if (! is_array ( $dateParts ) || count ( $dateParts ) != 3) {
			throw new Exception ( 'setMDY() expects a date as "MM/DD/YYYY".' );
		}
		$this->setDate ( $dateParts [2], $dateParts [0], $dateParts [1] );
	}
	
	/**
	 * Sets the date using input in DD/MM/YYYY format
	 * 
	 * Separator can be any of the following: dash, forward slash, space, 
	 * colon, or period. After extracting the year, month, and day parts,
	 * passes them to the overridden setDate() method.
	 *
	 * @param string $EuroDate  Date string in European DD/MM/YYYYY format.
	 */
	public function setDMY($EuroDate) {
		$dateParts = preg_split ( '{[-/ :.]}', $EuroDate );
		if (! is_array ( $dateParts ) || count ( $dateParts ) != 3) {
			throw new Exception ( 'setDMY() expects a date as "DD/MM/YYYY".' );
		}
		$this->setDate ( $dateParts [2], $dateParts [1], $dateParts [0] );
	}
	
	/**
	 * Sets the date using input in the MySQL (ISO 8601) format.
	 * 
	 * Accepts date in the format used by MySQL.
	 * Separator can be any of the following: dash, forward slash, space, 
	 * colon, or period. After extracting the year, month, and day parts,
	 * passes them to the overridden setDate() method.
	 *
	 * @param string $MySQLDate  Date string in ISO 8601 format (YYYY-MM-DD).
	 */
	public function setFromMySQL($MySQLDate) {
		$dateParts = preg_split ( '{[-/ :.]}', $MySQLDate );
		if (! is_array ( $dateParts ) || count ( $dateParts ) != 3) {
			throw new Exception ( 'setFromMySQL() expects a date as "YYYY-MM-DD".' );
		}
		$this->setDate ( $dateParts [0], $dateParts [1], $dateParts [2] );
	}
	
	###################################################
	# PUBLIC METHODS FOR GETTING FULL DATE            #
	###################################################    
	/**
	 * Get date formatted as MM/DD/YYYY.
	 * 
	 * Accepts an optional Boolean argument that determines whether to format
	 * the month and day with leading zeros. Defaults to false.
	 *
	 * @param bool $leadingZeros  (Optional) adds leading zeros if true; default false.
	 * @return string  Date formatted as MM/DD/YYYY.
	 */
	public function getMDY($leadingZeros = false) {
		if ($leadingZeros) {
			return $this->format ( 'm/d/Y' );
		} else {
			return $this->format ( 'n/j/Y' );
		}
	}
	
	/**
	 * Get date formatted as DD/MM/YYYY.
	 *
	 * Accepts an optional Boolean argument that determines whether to format
	 * the month and day with leading zeros. Defaults to false.
	 *
	 * @param bool $leadingZeros  (Optional) adds leading zeros if true; default false.
	 * @return string  Date formatted as MM/DD/YYYY.
	 */
	public function getDMY($leadingZeros = false) {
		if ($leadingZeros) {
			return $this->format ( 'd/m/Y' );
		} else {
			return $this->format ( 'j/n/Y' );
		}
	}
	
	/**
	 * Gets date formatted as YYYY-MM-DD.
	 *
	 * @return string Date formatted as YYYY-MM-DD.
	 */
	public function getMySQLFormat() {
		return $this->format ( 'Y-m-d' );
	}
	
	##################################################
	# PUBLIC METHODS FOR GETTING DATE PARTS          #
	##################################################
	/**
	 * Get year as four-digit number.
	 *
	 * @return int Year as four-digit number
	 */
	public function getFullYear() {
		return $this->_year;
	}
	
	/**
	 * Get year as two-digit number.
	 *
	 * @return string Year as two-digit number
	 */
	public function getYear() {
		return $this->format ( 'y' );
	}
	
	/**
	 * Get month with or without leading zero.
	 * 
	 * Optional Boolean argument adds leading zero if true. Defaults to false.
	 *
	 * @param bool $leadingZero (Optional) adds leading zero if true; default false.
	 * @return int|string Month.
	 */
	public function getMonth($leadingZero = false) {
		return $leadingZero ? $this->format ( 'm' ) : $this->_month;
	}
	
	/**
	 * Get month name in full.
	 *
	 * @return string Month name in full.
	 */
	public function getMonthName() {
		return $this->format ( 'F' );
	}
	
	/**
	 * Get month name as three-letter abbreviation.
	 *
	 * @return string Abbreviated month name.
	 */
	public function getMonthAbbr() {
		return $this->format ( 'M' );
	}
	
	/**
	 * Get day with or without leading zero.
	 * 
	 * Optional Boolean argument adds leading zero if true. Defaults to false.
	 *
	 * @param bool $leadingZero (Optional) adds leading zero if true; default false.
	 * @return int|string Day.
	 */
	public function getDay($leadingZero = false) {
		return $leadingZero ? $this->format ( 'd' ) : $this->_day;
	}
	
	/**
	 * Get day number as English ordinal (1st, 2nd, etc.)
	 *
	 * @return string Day number as ordinal
	 */
	public function getDayOrdinal() {
		return $this->format ( 'jS' );
	}
	
	/**
	 * Get day name in full.
	 *
	 * @return string Full day name.
	 */
	public function getDayName() {
		return $this->format ( 'l' );
	}
	
	/**
	 * Get day name as abbreviation.
	 *
	 * @return string Abbreviated day name.
	 */
	public function getDayAbbr() {
		return $this->format ( 'D' );
	}
	
	################################################
	# PUBLIC METHODS FOR DATE CALCULATIONS         #
	################################################
	/**
	 * Add specified number of days to stored date.
	 *
	 * @param int $numDays Number of days to be added, must be positive.
	 */
	public function addDays($numDays) {
		if (! is_numeric ( $numDays ) || $numDays < 1) {
			throw new Exception ( 'addDays() expects a positive integer.' );
		}
		parent::modify ( '+' . intval ( $numDays ) . ' days' );
	}
	
	/**
	 * Subtract specified number of days from stored date.
	 * 
	 * Accepts either a positive or negative number, but uses only the
	 * absolute value.
	 *
	 * @param int $numDays Number of days to be subtracted.
	 */
	public function subDays($numDays) {
		if (! is_numeric ( $numDays )) {
			throw new Exception ( 'subDays() expects an integer.' );
		}
		parent::modify ( '-' . abs ( intval ( $numDays ) ) . ' days' );
	}
	
	/**
	 * Add specified number of weeks to date.
	 *
	 * @param int $numWeeks Number of weeks to be added, must be positive.
	 */
	public function addWeeks($numWeeks) {
		if (! is_numeric ( $numWeeks ) || $numWeeks < 1) {
			throw new Exception ( 'addWeeks() expects a positive integer.' );
		}
		parent::modify ( '+' . intval ( $numWeeks ) . ' weeks' );
	}
	
	/**
	 * Subtract specified number of weeks from date.
	 * 
	 * Accepts either a positive or negative number, but uses only the
	 * absolute value.
	 * 
	 * @param int $numWeeks Number of weeks to be subtracted.
	 */
	public function subWeeks($numWeeks) {
		if (! is_numeric ( $numWeeks )) {
			throw new Exception ( 'subWeeks() expects an integer.' );
		}
		parent::modify ( '-' . abs ( intval ( $numWeeks ) ) . ' weeks' );
	}
	
	/**
	 * Add specified number of months to date.
	 * 
	 * This method adjusts the result to the final day of the month if
	 * the resulting date is invalid, e.g., September 31 is converted
	 * to September 30. Results in February also take account of leap
	 * year. This contrasts with DateTime::modify() and strtotime, which
	 * produce unexpected results by adding the day(s). 
	 *
	 * @param int $numMonths Number of months to be added.
	 */
	public function addMonths($numMonths) {
		if (! is_numeric ( $numMonths ) || $numMonths < 1) {
			throw new Exception ( 'addMonths() expects a positive integer.' );
		}
		$numMonths = ( int ) $numMonths;
		// Add the months to the current month number.
		$newValue = $this->_month + $numMonths;
		// If the new value is less than or equal to 12, the year
		// doesn't change, so just assign the new value to the month.
		if ($newValue <= 12) {
			$this->_month = $newValue;
		} else {
			// A new value greater than 12 means calculating both
			// the month and the year. Calculating the year is
			// different for December, so do modulo division 
			// by 12 on the new value. If the remainder is not 0,
			// the new month is not December. 
			$notDecember = $newValue % 12;
			if ($notDecember) {
				// The remainder of the modulo division is the new month.
				$this->_month = $notDecember;
				// Divide the new value by 12 and round down to get the
				// number of years to add.
				$this->_year += floor ( $newValue / 12 );
			} else {
				// The new month must be December
				$this->_month = 12;
				$this->_year += ($newValue / 12) - 1;
			}
		}
		$this->checkLastDayOfMonth ();
		parent::setDate ( $this->_year, $this->_month, $this->_day );
	}
	
	/**
	 * Subtract specified number of months from date.
	 * 
	 * This method adjusts the result to the final day of the month if
	 * the resulting date is invalid, e.g., September 31 is converted
	 * to September 30. Results in February also take account of leap
	 * year. This contrasts with DateTime::modify() and strtotime, which
	 * produce unexpected results by subtracting the day(s). 
	 *
	 * @param int $numMonths Number of months to be subtracted.
	 */
	public function subMonths($numMonths) {
		if (! is_numeric ( $numMonths )) {
			throw new Exception ( 'addMonths() expects an integer.' );
		}
		$numMonths = abs ( intval ( $numMonths ) );
		// Subtract the months from the current month number.
		$newValue = $this->_month - $numMonths;
		// If the result is greater than 0, it's still the same year,
		// and you can assign the new value to the month.
		if ($newValue > 0) {
			$this->_month = $newValue;
		} else {
			// Create an array of the months in reverse.
			$months = range (12, 1);
			// Get the absolute value of $newValue.
			$newValue = abs ( $newValue );
			// Get the array position of the resulting month.
			$monthPosition = $newValue % 12;
			$this->_month = $months [$monthPosition];
			// Arrays begin at 0, so if $monthPosition is 0,
			// it must be December.
			if ($monthPosition) {
				$this->_year -= ceil ( $newValue / 12 );
			} else {
				$this->_year -= ceil ( $newValue / 12 ) + 1;
			}
		}
		$this->checkLastDayOfMonth ();
		parent::setDate ( $this->_year, $this->_month, $this->_day );
	}
	
	/**
	 * Add specified number of years to date.
	 * 
	 * Adding years to February 29 can produce an invalid date if
	 * the resulting year is not a leap year. This method takes
	 * this into account, and adjusts the result to February 28
	 * if necessary
	 *
	 * @param int $numYears Number of years to be added.
	 */
	public function addYears($numYears) {
		if (! is_numeric ( $numYears ) || $numYears < 1) {
			throw new Exception ( 'addYears() expects a positive integer.' );
		}
		$this->_year += ( int ) $numYears;
		$this->checkLastDayOfMonth ();
		parent::setDate ( $this->_year, $this->_month, $this->_day );
	}
	
	/**
	 * Subtract specified number of years from date.
	 * 
	 * Subtracting years from February 29 can produce an invalid date if
	 * the resulting year is not a leap year. This method takes
	 * this into account, and adjusts the result to February 28
	 * if necessary
	 *
	 * @param int $numYears Number of years to be subtracted.
	 */
	public function subYears($numYears) {
		if (! is_numeric ( $numYears )) {
			throw new Exception ( 'subYears() expects an integer.' );
		}
		$this->_year -= abs ( intval ( $numYears ) );
		$this->checkLastDayOfMonth ();
		parent::setDate ( $this->_year, $this->_month, $this->_day );
	}
	
	/**
	 * Determines whether the year is a leap year.
	 *
	 * @return bool True if it is a leap year, otherwise false.
	 */
	public function isLeap() {
		if ($this->_year % 400 == 0 || ($this->_year % 4 == 0 && $this->_year % 100 != 0)) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Calculates the number of days between two dates.
	 * 
	 * This differs from the static method in that it takes just one
	 * argument. The start date is the object that calls the method, 
	 * and the end date is passed to it as an argument. 
	 *
	 * @param Pos_Date $endDate Date that is to be compared with object.
	 * @return int Number of days between both dates.
	 */
	public function dateDiff2(Pos_Date $endDate) {
		$start = gmmktime ( 0, 0, 0, $this->_month, $this->_day, $this->_year );
		$end = gmmktime ( 0, 0, 0, $endDate->_month, $endDate->_day, $endDate->_year );
		return ($end - $start) / (60 * 60 * 24);
	}
	
	####################################################
	# MAGIC METHODS                                    #
	####################################################
	/**
	 * Outputs date as string
	 *
	 * @return string Date formatted like Saturday, March 1st, 2008
	 */
	public function __toString() {
		return $this->format ( 'l, F jS, Y' );
	}
	
	/**
	 * Output date parts as read-only properties.
	 * 
	 * Uses __get() magic method to create the following read-only 
	 * properties, all of which are case-insensitive:
	 * 
	 * - MDY: date formatted as MM/DD/YYYY
	 * - MDY0: date formatted as MM/DD/YYYY with leading zeros
	 * - DMY: date formatted as DD/MM/YYYY
	 * - DMY0: date formatted as DD/MM/YYYY with leading zeros
	 * - MySQL: date formatted as YYYY-MM-DD
	 * - fullYear: year as four digits
	 * - year: year as two digits
	 * - month: month number
	 * - month0: month number with leading zero
	 * - monthName: full name of month
	 * - monthAbbr: month as 3-letter abbreviation
	 * - day: day of month as number
	 * - day0: day of month as number with leading zero
	 * - dayOrdinal: day of month as ordinal number (1st, 2nd, etc.)
	 * - dayName: full weekday name
	 * - dayAbbr: weekday name as 3-letter abbreviation
	 * 
	 * Any other value returns "Invalid property".
	 *
	 * @param string $name Name of read-only property.
	 * @return string Formatted date or date part.
	 */
	public function __get($name) {
		switch ( strtolower ( $name )) {
			case 'mdy' :
				return $this->format ( 'n/j/Y' );
			case 'mdy0' :
				return $this->format ( 'm/d/Y' );
			case 'dmy' :
				return $this->format ( 'j/n/Y' );
			case 'dmy0' :
				return $this->format ( 'd/m/Y' );
			case 'mysql' :
				return $this->format ( 'Y-m-d' );
			case 'fullyear' :
				return $this->_year;
			case 'year' :
				return $this->format ( 'y' );
			case 'month' :
				return $this->_month;
			case 'month0' :
				return $this->format ( 'm' );
			case 'monthname' :
				return $this->format ( 'F' );
			case 'monthabbr' :
				return $this->format ( 'M' );
			case 'day' :
				return $this->_day;
			case 'day0' :
				return $this->format ( 'd' );
			case 'dayordinal' :
				return $this->format ( 'jS' );
			case 'dayname' :
				return $this->format ( 'l' );
			case 'dayabbr' :
				return $this->format ( 'D' );
			default :
				return 'Invalid property';
		}
	}
	
	###########################################################
	# PROTECTED METHOD                                        #
	###########################################################
	/**
	 * Adjusts the day to the last day of the month when necessary.
	 * 
	 * This internal method is called by addMonths(), subMonths(),
	 * addYears(), and subYears(). It checks whether the date calculation
	 * results in an invalid date, such as February 31. If the date is 
	 * invalid, it readjusts the $_day property to the last day of
	 * the month.
	 */
	final protected function checkLastDayOfMonth() {
		if (! checkdate ( $this->_month, $this->_day, $this->_year )) {
			$use30 = array (4, 6, 9, 11 );
			if (in_array ( $this->_month, $use30 )) {
				$this->_day = 30;
			} else {
				$this->_day = $this->isLeap () ? 29 : 28;
			}
		}
	}
}

