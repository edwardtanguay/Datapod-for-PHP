<?php

/**
 * Regular Expression functions.
 *
 */
class qreg {

	/**
	 * Allows you to send a syntax that a string should have, e.g. "^is within".
	 *
	 * @param string $regex
	 * @param string $body
	 * @return boolean
	 */
	public static function matches($regex, $body) {
		$rb = false;
		$fullRegex = "/" . $regex . "/";
		preg_match($fullRegex,$body,$matches);
		if(count($matches) > 0) {
			$theFirstMatch = $matches[0];
			if(!qstr::IsEmpty($theFirstMatch)) {
				$rb = true;
			} else {
				$rb = false;
			}
		} else {
			$rb = false;
		}
		return $rb;
	}

	public static function getTextBetweenTwoTexts($string, $text1, $text2) {

		$string = '<div id="doc-contents"> THIS IS THE NEW CONTENT<br>THIS SHOULD APPEAR INSTEAD OF OLD CONTENT<br>updated 2010-05-02 03:44:06 GMT<br>updated 2010-05-02 03:46:06 GMT<br> <br clear="all"/> </div> <div id="google-view-footer">';

		//$string = utf8_encode($string);
		//$string = iconv("UTF-8", "ISO-8859-1", $string);
		$text1 = 'THIS IS';
		$text2 = 'clear=';
		echo 'text1:[' . htmlentities($text1) . ']';
		echo 'text2:[' . htmlentities($text2) . ']';


		$pattern = "/$text1(.*)$text2/";
		$matches = array();
		echo '//' . htmlentities($string) . '//';
		preg_match($pattern, $string, $matches);

		echo 'countmatches:[' . count($matches) . ']';
		echo 'countmatches:[<p style="color:red">' . htmlentities($matches[1]) . '</p>]';
		die;


		return $matches[1];
	}




}


?>