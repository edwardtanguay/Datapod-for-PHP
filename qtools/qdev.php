<?php

/**
 * Debugging/Developer functions.
 *
 */
class qdev {

	/**
	 * Simply displays a value at a particular location for debugging.
	 *
	 * @param string $line
	 * @param string $absolutePathAndFileName
	 * @param string $lineNumber
	 * @param string $extras
	 */
	public static function show($line = '', $absolutePathAndFileName = '', $lineNumber = 0, $extras = '') {

		//variables
		$action = qstr::getExtrasValue('action', $extras);
		$fileName = qfil::getFileNameFromAbsolutePathAndFileName($absolutePathAndFileName);

		//define preprefix
		if(qstr::areEqual($action, 'stop')) {
			$prePrefix = ': <span style="color:red">STOPPED</span>';
		} else {
			$prePrefix = '';
		}

		//define prefix
		if(!qstr::isEmpty($fileName) || !qstr::isEmpty($lineNumber)) {
			$prefix = $prePrefix . ': <span style="color:blue"><strong>' . $fileName . '</strong>, line ' . $lineNumber. '</span>';
		} else {
			$prefix = $prePrefix . '';
		}

		//brackets
		$bracketStyleAttribute = 'style="color:#aaa"';
		$bracketOpen = '<span ' . $bracketStyleAttribute . '>[</span>';
		$bracketClose = '<span ' . $bracketStyleAttribute . '>]</span>';

		//show
		echo '<div>' . qsys::dateTimeStamp() . $prefix . ': ' . $bracketOpen . qstr::htmlEncode($line) . $bracketClose . '</div>';

	}

	/**
	 * Shows a variable and then stops execution.
	 *
	 * @param string $line
	 * @param string $absolutePathAndFileName
	 * @param int $lineNumber
	 */
	public static function stop($line = '', $absolutePathAndFileName = '', $lineNumber = 0) {

		//variables
		$fileName = qfil::getFileNameFromAbsolutePathAndFileName($absolutePathAndFileName);

		//show
		qdev::show($line, $absolutePathAndFileName, $lineNumber, '$action=stop');
		die;
	}


	/**
	 * Writes a message out to error log for an array in a clear and uniform fashion.
	 *
	 * @param array $array the array to be written
	 */
	public static function debugArray($array, $pathAndFileName='', $line='') {

		//variables
		$arrayText = var_export($array, true);

		//output it
		qdev::show($arrayText, $pathAndFileName, $line, true); ; // ######################### DEBUGGING LINE

	}


	/**
	 * Returns HTML of all session variables and their values.
	 *
	 */
	public static function showSessionVariables($pathAndFileName='', $line='') {

		//title
		echo '<div style="font-size: 18pt">SESSION VARIABLES:</div>';

		//show them
		if(count($_SESSION) > 0) {
			foreach($_SESSION as $key => $value) {

				//variables
				$variableType = gettype($value);

				//show it
				switch(strtolower($variableType)) {
					case 'object':
						echo '<b>' . $key . ':</b>' . qstr::BR();
						qdev::showItem($value, $pathAndFileName, $line); ; // ######################### DEBUGGING LINE
						break;
					case 'array':
						echo '<b>' . $key . ':</b>' . qstr::BR();
						qdev::showArray($value, $pathAndFileName, $line); ; // ######################### DEBUGGING LINE
						break;
					default:
						echo '<b>' . $key . ': </b>' . $value . qstr::BR();
						break;
				}
			}
		}

	}


	/**
	 * Returns HTML of all session variable keys, used if there are objects too large to show in full.
	 *
	 */
	public static function showSessionVariableKeys($pathAndFileName='', $line='') {

		//title
		echo '<div style="font-size: 18pt">SESSION VARIABLE KEYS:</div>';

		//show them
		echo qdev::debugArray(array_keys($_SESSION), $pathAndFileName, $line);

	}

	/**
	 * Returns HTML of all session variables and their values.
	 *
	 */
	public static function showFormVariables($pathAndFileName='', $line='') {

		//title
		echo '<div style="font-size: 18pt">FORM VARIABLES:</div>';

		//show them
		echo qdev::debugArray($_POST, $pathAndFileName, $line);

	}

	/**
	 * Shows the call stack at a certain point.
	 *
	 * e.g. Which classes/methods have previously called the current class/method. Includes files and line numbers as well.
	 *
	 */
	public static function showCallStack() {

		$vDebug = debug_backtrace();

		echo '<table cellpadding="3" border="1"><tr>';
		echo "<td><B>Depth</b></td>";
		echo "<td><B>File</b></td>";
		echo "<td><B>Method</b></td>";
		echo "</tr>";

		for ($i=0;$i<count($vDebug);$i++) {

			// skip the first one, since it's always this func
			if ($i==0) { continue; }

			$t = count($vDebug) - $i;
			$num = sprintf("[%03d] : ", $t);
			$aFile = $vDebug[$i];

			$f = $aFile['function'];
			$f2 = strtoupper($f);
			if (substr($f2,0,7) == "REQUIRE" || substr($f2,0,7) == "INCLUDE") {
				$args = basename($args);
			}

			echo "<TR>";
			echo "<td>";
			printf("%03d", $t);
			echo "</td>";
			echo "<td> " . basename($aFile['file']) . ', line ' . $aFile['line'] . "</td>";
			echo "<td> " . $aFile['class'] . $aFile['type'] . $aFile['function'] . "() </td>";
			echo "</tr>";

		}

		echo "</table>";
	}

	/**
	 * Writes a message out to error log for an array in a clear and uniform fashion.
	 *
	 * @param array $array the array to be written
	 */
	public static function showArray($array, $pathAndFileName='', $line='', $objectDisplayMethod = null) {

		//variables
		$arrayText = qstr::NEW_LINE();

		if(count($array) > 0) {
			foreach($array as $key => $value) {

				//variables
				$type = gettype($value);

				//smart value
				if(qstr::AreEqual($type, 'object')) {

					//if they want to show an object property then show it
					if(!qstr::IsEmpty($objectDisplayMethod)) {
						$smartValue = $value->$objectDisplayMethod();
					} else {
						$smartValue = '(object)';
					}
				} else {
					$smartValue = $value;
				}

				$arrayText .= $key . ': ' . $smartValue . qstr::NEW_LINE();
			}
		}

		//output it
		echo '<pre>';
		qdev::show($arrayText, $pathAndFileName, $line, true); ; // ######################### DEBUGGING LINE
		echo '</pre>';

	}

	/**
	 * Shows the value of a boolean.
	 *
	 * @param string $booleanLabel
	 * @param boolean $booleanValue
	 * @param string $pathAndFileName
	 * @param string $line
	 * @return string
	 */
	public static function showBoolean($booleanLabel, $booleanValue, $pathAndFileName, $line) {
			
		$r = '';

		//build it
		if($booleanValue) {
			$r = $booleanLabel . ' is true';
		} else {
			$r = $booleanLabel . ' is false';
		}

		return qdev::show($r, $pathAndFileName, $line);

	}

	/**
	 * Shows if the value is null or not.
	 *
	 * @param string $labelForPotentiallyNull
	 * @param mixed $potentiallyNullValue
	 * @param string $pathAndFileName
	 * @param string $line
	 * @return boolean
	 */
	public static function showNull($labelForPotentiallyNull, $potentiallyNullValue, $pathAndFileName, $line) {
			
		$r = '';

		//build it
		if(is_null($potentiallyNullValue)) {
			$r = $labelForPotentiallyNull . ' is null';
		} else {
			$r = $labelForPotentiallyNull . ' is NOT null';
		}

		return qdev::show($r, $pathAndFileName, $line);

	}

	/**
	 * Returns HTML of all server variables and their values.
	 *
	 */
	public static function showServerVariables() {

		$r = '';

		//title
		$r .= 'SERVER VARIABLES:' . qstr::BR ();

		foreach ( $_SERVER as $key => $value ) {
			$r .= '<b>' . $key . '</b> = ' . $value . qstr::BR ();
		}

		echo $r;

	}


	public static function wrapInDisplayBox($content) {
		$r = '';

		return $r;
	}

	/**
	 * Shows the internals of an object in readable form.
	 *
	 * @param obj $object
	 */
	public static function showObject($object) {
		echo "<pre>";
		print_r ( $object );
		echo "</pre>";
	}


	/**
	 * Shows the property contents of an item.
	 *
	 * @param array $item the item to be shown
	 */
	public static function showItemsObject($message, $items, $pathAndFileName='', $line='') {
		if(is_null($items)) {
			$r .= 'Cannot show item, it is null.';
		} else {
			qdev::show($message, $pathAndFileName, $line);
			$r .= $items->showDebugInfo();
		}

		echo $r;

	}


	/**
	 * Simply displays a value at a particular location for debugging in the error log.
	 *
	 * @param string $line
	 * @param string $absolutePathAndFileName
	 * @param string $lineNumber
	 * @param string $extras
	 */
	public static function showInErrorLog($line = '', $absolutePathAndFileName = '', $lineNumber = 0, $extras = '') {

		//variables
		$fileName = qfil::getFileNameFromAbsolutePathAndFileName($absolutePathAndFileName);

		//show
		error_log('###' . $fileName . '/line ' . $lineNumber . '###' . qstr::htmlEncode($line) . '###   ');

	}


	/**
	 * Show values of a simple array in the error log.
	 *
	 * @param array $array
	 * @param string $absolutePathAndFileName
	 * @param int $lineNumber
	 * @param int $extras
	 */
	public static function showArrayInErrorLog($array, $absolutePathAndFileName = '', $lineNumber = 0, $extras = '') {
		if(count($array) > 0) {
			foreach($array as $key => $value) {
				qdev::showInErrorLog($key . '=[' . $value . ']', $absolutePathAndFileName, $lineNumber); // ######################### DEBUGGING LINE
			}
		}
	}


	/**
	 * Returns server variables and their values in error log.
	 *
	 */
	public static function showServerVariablesInErrorLog() {

		$r = '';

		//title
		error_log('------------SERVER VARIABLES BEGIN:');

		foreach($_SERVER as $k => $v) {
			$key = $k;
			$val = $v;
			error_log($k . ' = ' . $v);
		}

		error_log('------------SERVER VARIABLES END:');

	}

	/**
	 * Returns session variables and their values in error log.
	 *
	 */
	public static function showSessionVariablesInErrorLog() {

		$r = '';

		//title
		error_log('------------SESSION VARIABLES BEGIN:');

		foreach($_SESSION as $k => $v) {
			$key = $k;
			$val = $v;
			error_log($k . ' = ' . $v);
		}

		error_log('------------SESSION VARIABLES END:');

	}

	/**
	 * Returns HTML of all session variable keys, used if there are objects too large to show in full.
	 *
	 */
	public static function showSessionVariableKeysInErrorLog() {

		$r = '';

		//title
		error_log('------------SESSION VARIABLE KEYS BEGIN:');

		foreach($_SESSION as $k => $v) {
			$key = $k;
			error_log($k);
		}

		error_log('------------SESSION VARIABLE KEYS END:');

	}



	/**
	 * Helps debug the SmartForm by showing the status of all SmartFormObjects (e.g. DataTypes and buttons) loaded at that point.
	 *
	 */
	public function showSmartFormObjects($smartForm) {
			
		$r = '';

		//variables
		$smartFormObjects = $smartForm->getSmartFormObjects();

		//build it
		if(count($smartFormObjects) > 0) {
			$r .= '<div style="border-bottom: 1px solid #555;width: 600px">SmartFormObjects:</div>';
			$r .= '<div style="margin: 0 0 10px 20px">';
			foreach($smartFormObjects as $smartFormObject) {
				$r .= '<div>';
				//$r .= $smartFormObject->showDebug();
				$r .= $smartFormObject->getLabel() . ', formStatus=' . $smartFormObject->getFormStatus();
				$r .= '</div>';
			}
			$r .= '</div>';
		}

		echo $r;

	}

	public function showCharactersInString($line) {
		$array = preg_split('//', $line, -1, PREG_SPLIT_NO_EMPTY);
		foreach($array as $char) {
			$asciiValue = ord($char);
			if($asciiValue < 48) {
				echo '<p style="margin:0; padding:0; color:red">' . $char . ' [' . $asciiValue . ']</p>';
			} else {
				echo '<p style="margin:0; padding:0">' . $char . ' [' . $asciiValue . ']</p>';
			}
		}
	}

	/**
	 * Outputs the ASCII codes of all the characters in the string, e.g. if you are looking for the ascii number for a particular character.
	 *
	 * @param string $line
	 * @return string HTML
	 */
	public static function showAsciiCharacters($line) {
		echo qdev::getAsciiCharacters($line);
	}
	
	/**
	 * Outputs the ASCII codes of all the characters in the string, e.g. if you are looking for the ascii number for a particular character.
	 *
	 * @param string $line
	 * @return string HTML
	 */
	public static function getAsciiCharacters($line) {
		$r = '';
		for($index = 0; $index <= strlen($line) - 1; $index++) {
			$character = substr($line, $index, 1);
			$asciiNumber = ord($character);
			if($asciiNumber == 10 || $asciiNumber == 13) {
				$attribute = ' style="color:red"'; 
			} else {
				$attribute = '';
			}
				$r .= '<div' . $attribute . '>' . $character . '=' . $asciiNumber . '</div>';
		}
		return $r;
	}	

	public function showVariable($label, $value) {
		$r = '';
		$r .= '<p><span class="label">' . $label . ':[</span><span class="value">' . $value . '</span><span class="label">]</span></p>';
		return $r;
	}	

}

?>