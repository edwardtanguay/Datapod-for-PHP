<?php

/**
 * String helper functions.
 *
 */
class qstr {

	/**
	 * Converts a block (separated by new line characters).
	 *
	 * e.g. number of lines typed after each other in the browser, into an array of lines.
	 *
	 * @param string $block
	 * @return array
	 */
	public static function convertStringBlockToLines($block) {
		$ra = null;
		$trimmedBlock = trim($block);
		$trimmedBlock = qstr::chopRight($block, qstr::NEW_LINE());
		if(strlen($trimmedBlock) == 0) {
			$ra = null;
		} else {
			$blockLines = explode(qstr::NEW_LINE(),$trimmedBlock);
			$firstLine = $blockLines[0];
			foreach($blockLines as $blockLine) {
				$arrayItem = trim($blockLine, " ");
				$ra[] = $arrayItem;
			}
		}
		return $ra;
	}


	/**
	 * Chops off the end of a string if it equals a certain value, e.g. if you want the last comma off of 'one,two,three,four,'.
	 *
	 * TODO: This is old code, should be optimized at some point.
	 *
	 * @param string $stringToChop
	 * @param string $mainString
	 * @return string
	 */
	public static function chopRight($mainString, $stringToChop) {

		//define the portion of the main string which corresponds to the string to chop
		$lengthOfStringToChop = strlen($stringToChop);
		$startChopPosition = strlen($mainString) - $lengthOfStringToChop;
		$checkPart = substr($mainString, $startChopPosition, $lengthOfStringToChop);

		//check to see if they are the same
		if(qstr::areEqual($checkPart,$stringToChop)) {

			//they are equal, so chop it off
			$restLength = strlen($mainString)  - $lengthOfStringToChop;
			$choppedString = substr($mainString,0,$restLength);

		} else {

			//the string to chop is not on the left, so just return the main string
			$choppedString = $mainString;

		}

		return $choppedString;

	}

	/**
	 * The new line character for making text files or text inside textarea form elements.
	 *
	 * @param int $numberOfTimes
	 * @return string html
	 */
	public static function NEW_LINE($numberOfTimes = 1) {
		$r = '';
		for($x = 1; $x<=$numberOfTimes; $x++) {
			if(qsys::getServerType() == qsys::SERVERTYPE_LINUX) {
				$r .= "\n";
			} else {
				$r .= "\r\n";
			}
		}
		return $r;
	}


	function convertGoogleDocContentToText($rawData) {
		$r = '';
		$rawContent = qstr::getTextBetweenTwoTexts($rawData, '<div id="doc-contents">', '<br clear="all"');
		$rawContent = trim($rawContent);
		$rawContent = qstr::chopRight($rawContent, '<br>');
		$rawContent = str_replace('<br>', chr(13), $rawContent);
		$rawContent = str_replace('<div>', '', $rawContent);
		$rawContent = str_replace('</div>', '', $rawContent);
		$rawContent = str_replace('<p>', '', $rawContent);
		$rawContent = str_replace('</p>', '', $rawContent);
		return $rawContent;
		/*
		 $lines = preg_split("/<br>/", $rawContent);
		 $newLines = qstr::trimLines($lines);
		 $r .= qstr::convertLinesToStringBlock($newLines);
		 return $r;
		 */
	}

	/**
	 * Returns true if the given strings are equal. (case insensitive)
	 *
	 * @param string $term1
	 * @param string $term2
	 * @return bool
	 */
	public static function areEqual($term1, $term2) {
		return trim ( strtoupper ( $term1 ) ) == trim ( strtoupper ( $term2 ) );
	}

	public static function trimLines($lines) {
		$end = count($lines);
		for ($start=0; trim($lines[$start]) === ''; ++$start) {
			if ($start == $end) {
				return array();
			}
		}
		for (--$end; trim($lines[$end]) === ''; --$end) {}
		return array_slice($lines, $start, $end-$start+1);
	}

	/**
	 * Converts an array of lines, extras could be "$rebuildBrackets" which means to put [[ and ]] around blocks of newlines.
	 *
	 * @param array $lines
	 * @param string $extras
	 * @return string block
	 */
	public static function convertLinesToStringBlock($lines, $extras = '', $newlineOption = '') {

		$r = '';

		//variables
		$rebuildBrackets = qstr::getExtrasValueIsTrue('rebuildBrackets', $extras);

		//new line option
		if(!qstr::IsEmpty($newlineOption)) {
			$theNewLine = $newlineOption;
		} else {
			$theNewLine = qstr::NEW_LINE();
		}

		//go through all lines an create block
		if(count($lines) > 0) {
			foreach($lines as $line) {

				//define if this is a multiline item
				if (strpos($line, qstr::NEW_LINE()) === false) {
					$thisIsAMultiline = false;
				}	else {
					$thisIsAMultiline = true;
				}

				//if there are new lines then surround again with '[[' and ']]'
				if($rebuildBrackets && $thisIsAMultiline) {
					$r .= '[[' . $theNewLine;
				}

				//save line
				$r .= $line . $theNewLine;

				//if there are new lines then surround again with '[[' and ']]'
				if($rebuildBrackets && $thisIsAMultiline) {
					$r .= ']]' . $theNewLine;
				}

			}
		}

		//take off bottom
		$r = trim($r);

		return $r;

	}


	/**
	 * Returns whether or not an extras value is true or false.
	 *
	 * e.g. in "$status=on;$finished=false;$idCode=grails" the variable "finished" is false.
	 * e.g. in "$status=on;$finished=true;$idCode=grails" the variable "finished" is true.
	 * e.g. in "$status=on;$finished;$idCode=grails" the variable "finished" is true.
	 *
	 * @param string $variable
	 * @param string $extras
	 * @return boolean
	 */
	public static function getExtrasValueIsTrue($variable, $extras, $default = '') {

		$rb = false;

		//determine it
		$value = qstr::getExtrasValue($variable, $extras, $default);

		//assign it
		$rb = qstr::isAffirmative($value);

		return $rb;

	}


	/**
	 * Returns whether a string is empty (or null).
	 *
	 * This method ignores spaces.
	 *
	 * @param string $line
	 * @return bool
	 */
	public static function isEmpty($line) {
		return strlen ( trim ( $line ) ) == 0;
	}

	/**
	 * Returns whether a string is empty (or null) or has a "empty filler".
	 *
	 * @param string $line
	 * @return bool
	 */
	public static function smartIsEmpty($line) {
		if(qstr::isEmpty($line) || $line == 'nnnnnnnnnnnnnnnnnnn') {
			return true;
		} else {
			return false;
		}
	}



	/**
	 * Alias of breakIntoPieces.
	 *
	 * @param string $line
	 * @param string $separator character such as ','
	 * @param int $numberOfPieces
	 * @return array
	 */
	public static function breakIntoParts($line, $separator = ',', $numberOfPieces = 0) {
		return qstr::breakIntoPieces($line, $separator, $numberOfPieces);
	}

	/**
	 * Performs basic explode() functionatlity with extras, e.g. trimming.
	 *
	 *  Converts "on, off, unknown" into an array with these 3 strings trimmed.
	 *
	 * @param string $line
	 * @param string $separator character such as ','
	 * @param int $numberOfPieces
	 * @return array
	 */
	public static function breakIntoPieces($line, $separator = ',', $desiredNumberOfPieces = 0) {

		//fix separator, e.g. if it is a '|', then we need to escape it so that it is recognized as such by the regular expression engine
		if(qstr::areEqual($separator,'|')) {
			$niceSeparator = '|';
		} else {
			$niceSeparator = $separator;
		}

		//variables
		$pieces = explode($niceSeparator, $line);

		//now trim the pieces
		$cleanedPieces = array_map('trim', $pieces);
		$numberOfCleanedPieces = count($cleanedPieces);

		//if they e.g. want 3 parts and there is only 1, then pad the rest as blanks
		if($numberOfCleanedPieces != 0) {
			if($desiredNumberOfPieces > $numberOfCleanedPieces) {
				for($x = 1; $x <= $desiredNumberOfPieces-$numberOfCleanedPieces; $x++) {
					$cleanedPieces[] = '';
				}
			}
		}

		return $cleanedPieces;

	}

	/**
	 * Returns whether or not a string begins with another string.
	 *
	 * @param string $main
	 * @param string $part
	 * @return boolean
	 */
	public static function beginsWith($main, $part) {
		return strpos($main, $part) === 0;
	}

	/**
	 * Returns whether the string is something affirmative, e.g. "yes" or "true", etc.
	 *
	 * Used for parsing the meaning of variables e.g. from the URL.
	 *
	 * @param string $term
	 * @return boolean
	 */
	public static function isAffirmative($term) {

		$rb = false;

		//if it is boolean true
		if($term===true) {
			return true;
		}

		//if it is boolean false
		if($term===false) {
			return false;
		}

		//it was text so process it logically
		switch(strtoupper($term)) {
			case 'ON':
			case '1':
			case 'TRUE':
			case 'YES':
				$rb = true;
				break;
			default:
				$rb = false;
				break;
		}

		return $rb;

	}


	/**
	 * Returns the text up to the first marker in a string. If the marker is not
	 * there, then return the original string.
	 *
	 * @param string $line haystack
	 * @param string $marker needle
	 * @return string
	 */
	public static function getTextBeforeMarkerSoft($line, $marker) {
		return current ( explode ( $marker, $line ) );
	}



	/**
	 * Gets the text in a string after the last occurance of another string.
	 *
	 * @param string $line haystack
	 * @param string $marker needle
	 * @return string
	 */
	public static function getTextAfterLastMarkerSoft($line, $marker) {
		return end ( explode ( $marker, $line ) );
	}

	public static function getTextBetweenTwoTexts($string, $text1, $text2) {
		$preAndContent = qstr::getTextBeforeMarkerSoft($string, $text2);
		$content = qstr::getTextAfterLastMarkerSoft($preAndContent, $text1);
		return $content;
	}

	public static function cleanTextFromExternalUrl($text) {
		$r = $text;
		//		$r = str_replace('ö', '�', $r);
		//		$r = str_replace('ß', '�', $r);
		//		$r = str_replace('ü', '�', $r);
		//		$r = str_replace('ä', '�', $r);
		return $r;
	}

	/**
	 * Returns the safe version of tags, e.g. &lt; instead the brackets
	 *
	 * @param string $line
	 * @return string
	 */
	public static function htmlEncode($line) {
		return htmlspecialchars($line);
	}

	/**
	 * Returns a HTML BR tag.
	 *
	 * @param int $numberOfLines
	 * @return string html
	 */
	public static function BR($numberOfLines = 1) {
		$r = '';
		//build string with appropriate number of BR tags
		for($x=1; $x<=$numberOfLines; $x++) {
			$r .=  '<br/>';
		}
		return $r;
	}

	/**
	 * Takes the label off of a text datasource lind
	 *
	 * e.g. changes "title:: How to Install MySQL" to "How to Install MySQL"
	 *
	 * @param string $line
	 * @return string
	 */
	public static function removePrecedingFieldName($line) {

		$r = '';

		//it may not have the prefix so don't try to remove it if it doesn't exist
		if(qreg::matches('^([a-zA-Z0-9])*::', $line)) {

			//variables
			$pairs = explode('::', $line);

			//build it
			$r .= trim($pairs[1]);

		} else {

			//no field prefix so just return the line as the data
			$r = $line;

		}

		return $r;

	}

	/**
	 * Given a number and a singular noun, returns e.g. "0 hours" and "1 hour" and "2 hours"
	 *
	 * @param int $number
	 * @param string $plural
	 * @param string $singular
	 * @return string
	 */
	public static function smartPlural($number, $plural, $singular = '(default)') {

		$r = '';

		//variables
		$theSpace = '&nbsp;';

		//define singular if it was not given
		$singularDisplay = $singular;
		if($singularDisplay == '(default)') {
			$singularDisplay = qstr::chopRight($plural, 's');
		}

		//e.g. 0 seconds
		if($number == 0) {
			$r .= '0' . $theSpace . $plural;
		} else {

			//e.g. 1 second
			if($number == 1) {
				$r .= '1' . $theSpace . $singularDisplay;
			} else {

				//all else
				$r .= $number . $theSpace . $plural;

			}

		}

		return $r;

	}

	/**
	 *
	 * accepts "firstName:: Jim" and returns "firstName"
	 * @param $line
	 */
	public static function getPrecedingFieldIdCode($line) {
		$parts = qstr::breakIntoParts($line, "::");
		if(count($parts) > 0) {
			return $parts[0];
		} else {
			return "";
		}
	}

	/**
	 * Returns whether or not a string ends with another string.
	 *
	 * @param string $main
	 * @param string $part
	 * @return boolean
	 */
	public static function endsWith($main, $part) {

		$rb = false;

		//get length of part
		$partLength = strlen($part);

		//get position to start at in main to begin comparing
		$startPosition = strlen($main) - $partLength;

		//get that length of string from the main
		$mainPart = substr($main,$startPosition,$partLength);

		//see if they are the same
		if(qstr::areEqual($part,$mainPart)) {
			$rb = true;
		} else {
			$rb = false;
		}

		return $rb;

	}

	/**
	 * Forces a string to be in pascal notation, e.g. FirstName.
	 *
	 * @param string $term
	 * @return string
	 */
	public static function forcePascalNotation($term) {

		$r = '';

		//convert to "First Name"
		$r .= qstr::forceTitleNotation($term);

		//force EVERY word to be uppercase, as it may be here "Save and Close"
		$r = qstr::forceCapitalizeFirstCharacterOfEveryWord($r);

		//now simply take all spaces out
		$r = str_replace(" ","",$r);

		return $r;

	}

	/**
	 * Force to be a plural term, e.g. if "page" is sent, it returns "pages" (or if "pages" is sent it stays "pages").
	 *
	 * @param string $term
	 * @return string
	 */
	public static function forcePlural($term) {

		$r = '';

		//build it
		while(true) {

			//e.g. "property" becomes "properties"
			if(qstr::endsWith($term,"y")) {
				$r = qstr::chopRight($term,'y');
				$r = $r . "ies";
				break;
			}

			//e.g. "link" stays "links"
			if(!qstr::endsWith($term,"s")) {
				$r = $term . "s";
				break;
			}

			//e.g. "bus" stays "busses"
			if(qstr::endsWith($term,"us")) {
				$r = $term . "ses";
				break;
			}

			//nothing matched, just keep it as it is, since it probably already was plural, e.g. they sent "pages"
			$r = $term;
			break;

		}

		return $r;

	}

	/**
	 * Force to be a singular term, e.g. if "pageItems" is sent, it returns "pageItem".
	 *
	 * @param string $term
	 * @return string
	 */
	public static function forceSingular($term) {

		$r = '';

		//build it
		while(true) {

			//e.g. "properties" becomes "property"
			if(qstr::endsWith($term,"ies")) {
				$r = qstr::chopRight($term, 'ies');
				$r = $r . "y";
				break;
			}

			//e.g. "press" stays "press"
			if(qstr::endsWith($term,"ss")) {
				$r = $term;
				break;
			}

			//e.g. "bus" stays "bus"
			if(qstr::endsWith($term,"us")) {
				$r = $term;
				break;
			}

			//e.g. "busses" becomes "bus"
			if(qstr::endsWith($term,"sses")) {
				$r = qstr::chopRight($term, 'ses');
				break;
			}

			//e.g. "pages" becomes "page"
			if(qstr::endsWith($term,"es")) {
				$r = qstr::chopRight($term, 's');
				break;
			}

			//e.g. "links" becomes "link"
			if(qstr::endsWith($term,"s")) {
				$r = qstr::chopRight($term, 's');
				break;
			}

			//nothing matched, just keep it as it is, since it probably already was singular, e.g. they sent "page"
			$r = $term;
			break;

		}

		return $r;

	}

	/**
	 * Forces a string to be in camel notation, e.g. firstName.
	 *
	 * @param string $term
	 * @return string
	 */
	public static function forceCamelNotation($term) {

		$r = '';

		//variables
		$r = $term;

		//take out any characters that idcode cannot have, e.g. "start/Stop" to "startStop"
		$r = str_replace("/","",$r);
		$r = str_replace("+","",$r);
		$r = str_replace("=","",$r);
		$r = str_replace("(","",$r);
		$r = str_replace(")","",$r);
		$r = str_replace("_","",$r);
		$r = str_replace("*","",$r);
		$r = str_replace("'","",$r);
		$r = str_replace("!","",$r);
		$r = str_replace("?","",$r);
		$r = str_replace("-","",$r);
		$r = str_replace(",","",$r);
		$r = str_replace(".","",$r);
		$r = str_replace(":","",$r);
		$r = str_replace(";","",$r);
		$r = str_replace("<","",$r);
		$r = str_replace(">","",$r);

		//reduce all foreign charactgers to ascii
		$r = qstr::anglicize($r);

		//if it is all uppercase (e.g. FAQ) then we want all lower case (faq) and not (fAQ)
		if(qstr::isAllUppercase($r)) {

			//change e.g. FAQ to faq
			$r = strtolower($r);

		} else {

			//get the pascal notation first
			$pascalNotation = qstr::forcePascalNotation($r);

			//now lowercase the first character
			$r = qstr::makeFirstCharacterLowercase($pascalNotation);

		}

		return $r;

	}

	/**
	 * Forces a string to be in text notation, e.g. "first name".
	 *
	 * @param string $term
	 * @return string
	 */
	public static function forceTextNotation($term) {

		$r = '';

		//trim it
		$r = trim($r);

		//insert space before every uppercase, "thisIsTheVariable" becomes "this Is The Variable"
		$r .= qstr::insertSpaceBeforeEveryUppercaseCharacter($term);

		//now lowercase everything
		$r = strtolower($r);

		return $r;

	}

	/**
	 * Forces a string to be in title notation, e.g. First Name.
	 *
	 * @param string $term
	 * @return string
	 */
	public static function forceTitleNotation($term) {

		$r = '';

		//check if it is a known term that needs special
		//TODO: find a solution here which doesn't interfere with the fact that other "forceNotation" methods use this method.
		switch ($term) {

			case 'xid':
				//$r = 'ID';
				break;

			case 'xidCode':
			case 'xidcode':
				//$r = 'ID-Code';
				break;

			default:

				//it is a general string so build it manually

				//if he sent an acronym, then just return it
				if (qstr::isAllUppercase ( $term )) {

					//it is e.g. "UPS" so just keep it this way
					$r = $term;

				} else {

					//get the text notation, e.g. "first name"
					$textNotation = qstr::forceTextNotation ( $term );

					//now uppercase the first letter of each word
					$words = explode( " ", $textNotation );
					foreach ( $words as $word ) {
						$r .= trim ( ucfirst ( $word ) ) . " ";
					}

					//trim any spaces off
					$r = trim ( $r );

					//handle the punctuation rules for English, lowercase prepositions and articles under 7 letters
					$r = qstr::renderEnglishTitleCapitalization ( $r );

				}

				break;
		}

		$r = str_replace('- ', '-', $r);
		//@@update: qstr::forceTitleNotation() now renders hypenated words correctly

		return $r;

	}

	/**
	 * Returns whether a string is all uppercase.
	 */
	public static function isAllUppercase($line) {
		if(strtoupper($line) == $line) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Inserts a space before every uppercase characters.
	 *
	 * Used for making title notation.
	 *
	 * @param string $term
	 * @return string
	 */
	public static function insertSpaceBeforeEveryUppercaseCharacter($term) {
		$r = '';
		for($x=0; $x<strlen($term); $x++) {
			$character = $term[$x];
			if(qstr::characterIsUpper($character)) {
				$r .= " ";
			}
			$r .= $character;
		}
		$r = str_replace("  ", " ", $r);
		return $r;
	}

	/**
	 * Returns whether a string is all lowercase or not.
	 *
	 * @param string $line
	 * @return boolean
	 */
	public static function isAllLowercase($line) {
		$rb = false;
		if($line == strtolower($line)) {
			$rb = true;
		} else {
			$rb = false;
		}
		return $rb;
	}

	/**
	 * Determines whether a character is upper or lowercase.
	 *
	 * @param string $character
	 * @return string
	 */
	public static function characterIsUpper($character) {
		return ctype_upper($character);
	}

	/**
	 * Creates English title notation.
	 *
	 * @param string $term
	 * @return string
	 */
	public static function renderEnglishTitleCapitalization($line) {
		$r = '';
		$r = ucwords($line);
		$r = str_replace(" A "," a ",$r);
		$r = str_replace(" An "," an ",$r);
		$r = str_replace(" The "," the ",$r);
		$r = str_replace(" Or "," or ",$r);
		$r = str_replace(" And "," and ",$r);
		$r = str_replace(" Of "," of ",$r);
		$r = str_replace(" For "," for ",$r);
		$r = str_replace(" With "," with ",$r);
		//but if after colon ok
		$r = str_replace(": the ",": The ",$r);
		return $r;
	}

	/**
	 * Changes e.g. "Save and Close" to "Save And Close".
	 *
	 * @param string $line
	 * @return string
	 */
	public static function forceCapitalizeFirstCharacterOfEveryWord($line) {
		$r = '';
		$words = qstr::breakIntoPieces($line, ' ');
		if(count($words) > 0) {
			foreach($words as $word) {
				$r .= ucfirst($word) . " ";
			}
			$r = trim($r);
		}
		return $r;
	}

	/**
	 * Converts "�" to "e" for example, used when making file names etc where foreign characters should be avoided.
	 * TODO: fix the encoding here (happened during copying)
	 * @param string $line
	 * @return string
	 */
	public static function anglicize($line) {
		$r = $line;

		//French
		$r = str_replace('�','e', $r);
		$r = str_replace('�','e', $r);
		$r = str_replace('�','a', $r);
		$r = str_replace('�','a', $r);
		$r = str_replace('�','a', $r);
		$r = str_replace('�','c', $r);
		$r = str_replace('�','i', $r);
		$r = str_replace('�','A', $r);
		$r = str_replace('�','A', $r);
		$r = str_replace('�','E', $r);
		$r = str_replace('�','C', $r);

		//German
		$r = str_replace('�', 'ae', $r);
		$r = str_replace('�', 'oe', $r);
		$r = str_replace('�', 'ue', $r);
		$r = str_replace('�','AE', $r);
		$r = str_replace('�','OE', $r);
		$r = str_replace('�','UE', $r);
		$r = str_replace('�','ss', $r);

		//Spanish
		$r = str_replace("�","a",$r);
		$r = str_replace("�","e",$r);
		$r = str_replace("�","i",$r);
		$r = str_replace("�","o",$r);
		$r = str_replace("�","u",$r);
		$r = str_replace("�","u",$r);

		return $r;
	}

	/**
	 * Changes first character to lower case.
	 *
	 * @param string $line
	 * @return string
	 */
	public static function makeFirstCharacterLowercase($line) {
		$r = '';
		$firstCharacter = qstr::getSubstring($line,0,1);
		$restOfString = qstr::getRestOfString($line,0);
		$r = strtolower($firstCharacter) . $restOfString;
		return $r;
	}

	/**
	 * Wraps simple substring with additional checks.
	 *
	 * @param string $line
	 * @param int $start
	 * @param int $length
	 * @return string
	 */
	public static function getSubstring($line, $start, $length) {
		$r = '';
		$r = substr($line,$start,$length);
		if($start < 0) {
			$r = '';
		}
		return $r;
	}

	/**
	 * Returns the rest of the string starting at the position sent.
	 *
	 * @param string $line
	 * @param int $position
	 * @return string
	 */
	public static function getRestOfString($line,$position) {
		$r = '';
		$lengthOfRest = strlen($line - $position);
		$r = substr($line,$lengthOfRest);
		return $r;
	}

	/**
	 * Check if it is a valid e-mail address, e.g. "person@company.com".
	 *
	 * @param string $email
	 * @return boolean
	 */
	public static function isValidEmail($potentialEmail) {
		if(qreg::matches('^([0-9a-zA-Z]+[-._+&amp;])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,4}$', $potentialEmail)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Check if it is a valid url, e.g. "http://...".
	 *
	 * @param string $potentialUrl
	 * @return boolean
	 */
	public static function isValidUrl($potentialUrl) {
		if(qreg::matches('((mailto\:|(news|(ht|f)tp(s?))\:\/\/){1}\S+)', $potentialUrl)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 *
	 * Returns a line with a line break, for building text files.
	 * @param string $line
	 */
	public static function getLine($line = '') {
		if(!qstr::isEmpty($line))
		return '' . $line . '<br/>' . qstr::NEW_LINE();
		else
		return '<br/>' . qstr::NEW_LINE();
	}

	/**
	 *
	 * Returns a line with a line break, for building text files, but if blank, then nothing.
	 * @param string $line
	 */
	public static function getSmartLine($line = '') {
		if(!qstr::isEmpty($line))
		return $line . qstr::BR();
		else
		return '';
	}

	/**
	 * Chops off the beginning of a string it it equals a certain value, e.g. if you want the first comma off of ",one,two,three,four,".
	 *
	 * @param string $mainString
	 * @param string $stringToChop
	 * @return string
	 */
	public static function chopLeft($mainString, $stringToChop) {
		$lengthOfStringToChop = strlen($stringToChop);
		$checkPart = substr($mainString,0,$lengthOfStringToChop);
		if(qstr::areEqual($checkPart,$stringToChop)) {
			$restLength = strlen($mainString)  - $lengthOfStringToChop;
			$choppedString = substr($mainString,$lengthOfStringToChop,$restLength);
		} else {
			$choppedString = $mainString;
		}
		return $choppedString;
	}

	/**
	 * Returns a string with all BBCode tags parsed.
	 *
	 * //you can use the following BBCode for markup:
	 * //bold: this is [b]bold[/b] text
	 * //italic: this is [i]italic[/i] text
	 * //highlight: this is [h]highlighted[/h] text
	 * //code: press [c]CTRL-A[/c] and type in [c]ls -a[/c]
	 * //url: go to [url]www.dropbox.com[/url] and try it out
	 * //url: go to [url]http://www.dropbox.com[/url] and try it out]
	 * //url: check out [url=http://www.dropbox.com]dropbox[/url] for free syncing and storage
	 * //image: [img]http://dl.dropbox.com/u/13441293/images/test.png[/img]
	 */
	public static function parseBbcode($body) {
		$r = $body;
		$r = preg_replace("#\[b\](.+?)\[/b\]#is", "<b>\\1</b>", $r);
		$r = preg_replace("#\[i\](.+?)\[/i\]#is", "<i>\\1</i>", $r);
		$r = preg_replace("#\[h\](.+?)\[/h\]#is", '<span class="highlight">\\1</span>', $r);
		$r = preg_replace("#\[c\](.+?)\[/c\]#is", '<span class="code">\\1</span>', $r);
		$r = preg_replace("#\[url\]www\.(.+?)\[/url\]#is", "<a href=\"http://www.\\1\">www.\\1</a>", $r);
		$r = preg_replace("#\[url\](.+?)\[/url\]#is", "<a  href=\"\\1\">\\1</a>", $r);
		$r = preg_replace("#\[url=(.+?)\](.+?)\[/url\]#is", "<a target=\"_blank\" href=\"\\1\">\\2</a>", $r);
		$r = preg_replace("#\[img\](.+?)\[/img\]#is", "<img src=\"\\1\" alt=\"[image]\" style=\"margin: 5px 0px 5px 0px\" />", $r);
		return $r;
	}

	/**
	 *
	 * converts e.g. "1500" to "1.500,-"
	 * note: we assume that the integer is four characters long, e.g. between 1000 and 9999
	 */
	public static function convertIntegerToGermanMoneyFormat($line) {
		return substr($line, 0, 1) . '.' . substr($line, 1,3) . ',-';
	}

	/**
	 * Returns true if a string contains a search string.
	 *
	 * The case sensitivity can be switched either to insensitive or to sensitive.
	 *
	 * @param string $mainString haystack
	 * @param string $searchString needle
	 * @param string $caseSetting caseInsensitive
	 * @see strpos
	 * @see stripos
	 * @return bool
	 */
	public static function contains($mainString, $searchString, $caseSetting = "caseInsensitive") {
		$strFunction = $caseSetting == "caseInsensitive" ? "stripos" : "strpos";
		return $strFunction ( $mainString, $searchString ) !== false;
	}

	/**
	 * Gets the text in a string after the first occurance of a marker, if marker is not there, then return original string.
	 *
	 * @param string $line
	 * @param string $marker
	 * @return string
	 */
	public static function getTextAfterMarkerSoft($line, $marker) {
		$r = '';
		$markerPosition = strpos($line,$marker);
		if($markerPosition || qstr::beginsWith($line,$marker)) {
			$r = substr($line,$markerPosition+strlen($marker),strlen($line) - $markerPosition);
		} else {
			$r = $line;
		}
		return $r;
	}

	/**
	 * Gets text up until the last occurance of a character.
	 *
	 * @param string $line
	 * @param string $marker
	 * @return string
	 */
	public static function getTextBeforeLastMarker($line, $marker) {
		$r = '';
		$pieces = qstr::breakIntoPieces($line, $marker);
		for($x = 0; $x <= count($pieces) - 2; $x++) {
			$r .= $pieces[$x] . $marker;
		}
		$r = qstr::chopright($r, $marker);
		return $r;
	}

	/**
	 * Replaces all normal spaces with hard spaces so they don't break in HTML.
	 *
	 * @param string $line
	 * @return string
	 */
	public static function hardenSpaces($line) {
		return str_replace(' ', qstr::HARD_SPACE(),$line);
	}

	/**
	 * A space or spaces that don't break (e.g. for menus).
	 *
	 * @param int $howMany
	 * @return string
	 */
	public static function HARD_SPACE($howMany = 1) {
		$r = '';
		for($x=1; $x<=$howMany; $x++) {
			$r .= "&nbsp;";
		}
		return $r;
	}

	/**
	 * converts any 'http://....' to a hyperlink.
	 * @param unknown_type $line
	 */
	public static function convertUrlsToLinks($line) {
		return ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\">\\0</a>", $line);
	}

	/**
	 * Converts HTML tags to HTML encodings.
	 *
	 * @param string $line
	 * @return string
	 */
	public static function maskTags($line) {
		$r = $line;
		$r = str_replace('\t', '[TAB]', $r);
		return $r;
	}

	/**
	 * Gets "http://www.google.com" from "$freeLink=http://www.google.com;$walletList".
	 *
	 * @param string $variable
	 * @param string $extras semicolon-separated list
	 * @param string $default the default if it was not defined
	 * @return string  value of the extras variable
	 */
	public static function getExtrasValue($variable, $extras, $default = '') {
		$r = '';
		$smartExtras = new SmartExtras($extras);
		$r .= $smartExtras->getValue($variable);
		//assign the default if they sent one AND it is empty
		if(qstr::isEmpty($r) && !qstr::isEmpty($default)) {
			$r = $default;
		}
		return $r;
	}

	public static function convertBrsToNewlines($content) {
		return trim(str_replace('<br/>', qstr::NEW_LINE(), $content));
	}

	public static function convertNewlinesToBrs($content) {
		return trim(str_replace(qstr::NEW_LINE(), '<br/>', $content));
	}

	/**
	 * Merges two arrays and avoids a PHP warning if one or both of them are null
	 *
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 */
	public static function smartArrayMerge($array1, $array2) {
		$ra = null;
		switch ( true) {
			case is_null($array1) && is_null($array2):
				$ra = null;
				break;
			case is_null($array1) && !is_null($array2):
				$ra = $array2;
				break;
			case !is_null($array1) && is_null($array2):
				$ra = $array1;
				break;
			case !is_null($array1) && !is_null ($array2):
				$ra = array_merge($array1, $array2);
				break;
		}
		return $ra;
	}
	
	public static function forceWindowsNewlinesIfNecessary($content) {
		if(qsys::getServerType() == qsys::SERVERTYPE_LINUX) {
			return str_replace("\r\n", "\n", $content);
		} else {
			return $content;
		}
	}	

	/**
	 * Takes a line that has perhaps TAB characters on the front, counts them and returns.
	 *
	 * e.g. "qstr::TAB(2)", used in code generation mostly.
	 *
	 * @param string $line
	 * @return string
	 */
	public static function getPrecendingTabs($line) {
		$r = '';
		$numberOfPrecedingTabs = qstr::getNumberOfPrecedingTabsInLine($line);
		$r = qstr::TAB($numberOfPrecedingTabs);
		return $r;
	}	

	/**
	 * Gets the number of preceding tabs in a line.
	 *
	 * Used mostly for code generation.
	 *
	 * @param string $line
	 * @return int
	 */
	public static function getNumberOfPrecedingTabsInLine($line) {
		$ri = 0;
		$lastIndexNumberInLine = strlen($line) - 1;
		for($x=0; $x <= $lastIndexNumberInLine; $x++) {
			$character = substr($line, $x, 1);
			if($character == qstr::TAB()) {
				$ri++;
			} else {
				break;
			}
		}
		return $ri;
	}	

	
	/**
	 * Returns one or multiple tab characters.
	 *
	 * @param int $howMany
	 * @return string
	 */
	public static function TAB($howMany = 1) {
		$r = '';
		for($x=1; $x<=$howMany; $x++) {
			$r .= "\t";
		}
		return $r;
	}

	/**
	 * Alias of TAB.
	 *
	 * @param int $howMany
	 * @return string
	 */
	public static function TABS($howMany = 1) {
		return qstr::TAB($howMany);
	}

	public static function getSmartPart($line, $separator, $index) {
		$parts = qstr::breakIntoParts($line, $separator);
		if(count($parts) > $index) {
			return $parts[$index];
		} else {
			return '';	
	}
	}
	
}