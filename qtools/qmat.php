<?php

/**
 * Math functions
 *
 */
class qmat {

	/**
	 * Returns a random number based on the NUMBER OF DIGITS, e.g. "5" returns "23423" or "83984".
	 *
	 * @param int $numberOfDigits
	 * @return string
	 */
	public static function getRandomDigits($numberOfDigits) {
		$r = '';
		for($i=1; $i<=$numberOfDigits; $i++) {
			$nr=rand(0,9);
			$r .=  $nr;
		}
		return $r;
	}

	public static function isWholeNumber($number) {
		return $number == round($number);
	}


}

?>