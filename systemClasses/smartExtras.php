<?php
class SmartExtras {
	//variable: this is the extras line, e.g. "$freeLink=http://www.google.com;$source=mysqlproc/preface/xiii;$wallet"
	private $extras;

	//variable: the key/value array of extras
	private $extrasArray = array();

	//variable: used to mask text blocks as variable values
	private $maskingDelimiter = '^^^';

	function __construct($extras) {
		$this->extras = $extras;
		$this->initialize();
	}

	//method: receives e.g. "$locked=true"
	public function addExtra($extraToAdd) {
		$r = '';
		$parts = qstr::breakIntoParts($extraToAdd, '=');
		$extraIdCode = str_replace('$', '', $parts[0]);
		$extraValue = $parts[1];
		$this->extrasArray[$extraIdCode] = $extraValue;
		return $r;
	}

	//method: simply returns the value of a variable, e.g. for "source" returns "mysqlproc/preface/xiii" or empty string if it is not there
	public function getValue($variableName) {
		$r = '';
		$extrasArray = $this->extrasArray;
		if(key_exists($variableName, $extrasArray)) {
			$r .= $extrasArray[$variableName];
		} else {
			$r .= '';
		}
		return $r;
	}

	//method: returns true if it exists (and it is not "false" or "no"); returns false if it does not exist
	public function getBooleanValue($variableName) {
		$r = '';
		$extrasArray = $this->extrasArray;
		$value = $extrasArray[$variableName];
		if(qstr::isEmpty($value)) {
			$r .= 'false';
		} else {
			if(qstr::isAffirmative($value)) {
				$r .= 'true';
			} else {
				$r .= 'no';
			}
		}
		return $r;
	}

	//method: this is run everytime an object is created, handles initial variable assignments, etc.
	private function initialize() {
		$this->extrasArray = $this->createExtrasArray();
	}

	//method: creates array such as $ra['source']['mysqlproc/preface/xiii']
	private function createExtrasArray() {
		$ra= array();
		$extrasPairs = $this->processExtrasBlockIntoPairs();
		$numberOfExtrasPairs = count($extrasPairs);
		//go through each pair and break them down, e.g. "$source=mysqlproc/preface/xiii" or "$wallet"
		if($numberOfExtrasPairs > 0) {
			foreach($extrasPairs as $extrasPair) {
				//get the variable and value, e.g. "$source=mysqlproc/preface/xiii" or "$wallet"
				if(qstr::contains($extrasPair, "=")) {
					$parts = qstr::breakIntoParts($extrasPair, '=');
					$variableName = str_replace('$','',$parts[0]); // e.g. "$source"
					$value = trim(qstr::getTextAfterMarkerSoft($extrasPair,"="));
				} else {
					//it is a boolean e.g. "$backup" so assign it to true
					$variableName = str_replace('$','',$extrasPair); // e.g. "$source"
					$value = "true";
				}
				//we now have the variable and value so save it to the array
				$ra[$variableName] = $value;
			}
		}
		return $ra;
	}


	/**
	 * Breaks the extras text up into variables and values, accounting for mutlilines.
	 *
	 * e.g. "$freeLink=http://www.google.com;$source=mysqlproc/preface/xiii;$best
	 *
	 * @return array
	 */
	private function processExtrasBlockIntoPairs() {
		$ra = array();
		$rawExtras = qstr::breakIntoParts($this->extras, ';');
		//run through and take out those that do not have a dollar sign marking the key, e.g. "Title;$formStatus=readOnly" will throw out Title
		if(count($rawExtras) > 0) {
			foreach($rawExtras as $rawExtra) {
				if(qstr::beginsWith($rawExtra, '$')) {
					$ra[] = $rawExtra;
				}
			}
		}
		return $ra;
	}

	//method: masks all meaningful symbols (; $) between the {{ and }} lines so that the symbols are not processed
	private function maskedSingleLine($extras) {
		$r = '';
		$lines = qstr::convertStringBlockToLines($extras);
		$weAreMasking = false;
		if(count($lines) > 0) {
			//go through each line
			foreach($lines as $line) {
				if(!qstr::isEmpty($line)) {
					while(true) {
						//if marker to start masking
						if(qstr::areEqual($line,'{{')) {
							$weAreMasking = true;
							$lineToAdd = '';
							break;
						}
						//if marker to end masking
						if(qstr::areEqual($line,'}}')) {
							$weAreMasking = false;
							$lineToAdd = '';
							break;
						}
						//if we are masking and this is a normal line, then mask it
						if($weAreMasking) {
							$lineToAdd = $this->maskLine($line);
							break;
						}
						//otherwise it was a normal line and we are not masking
						$lineToAdd = $line;
						break;
					}
					//allow reserved characters
					$lineToAdd = $this->encodeLine($lineToAdd);
					//add it to the outgoing text if it is not blank
					if(!qstr::isEmpty($lineToAdd)) {
						$r .= $lineToAdd . ';';
					}
				}
			}
		}
		return $r;
	}

	//method: make changes to line so a e.g. code block can be processed as a simple variable value, e.g. semicolons and dollar signs encoded
	private function maskLine($line) {
		$r = '';
		$md = $this->maskingDelimiter;
		$r = $line;
		$r = str_replace(';', $md . 'SEMICOLON' . $md, $r);
		$r = str_replace('$', $md . 'DOLLARSIGN' . $md, $r);
		return $r;
	}

	//method: allow the use of reserved charaters such as semicolon and dollar through escaping
	private function encodeLine($line) {
		$r = '';
		$md = $this->maskingDelimiter;
		$r = $line;
		$r = str_replace('\$', $md . 'ESCAPEDDOLLARSIGN' . $md, $r);
		return $r;
	}

	public function softAddExtra($variableName, $value) {
		$r = '';
		$extrasArray = $this->extrasArray;
		$variableCurrentValue = $extrasArray[$variableName];
		//don't overwrite any values (soft add) but if it is not there yet, then add it
		if(qstr::isEmpty($variableCurrentValue)) {
			$this->extrasArray[$variableName] = $value;
		}
		return $r;
	}

	//method: builds the original lines again, e.g. "$description=111;$example=222;$datasource=333"
	public function renderAsExtrasLine() {
		$r = '';
		$extrasArray = $this->extrasArray;
		$keys = array_keys($extrasArray);
		$values = array_values($extrasArray);
		if(count($keys) > 0) {
			$index = 0;
			foreach($extrasArray as $extrasArrayItem) {
				$variableName = $keys[$index];
				$value = $values[$index];
				$r .= '$' . $variableName . '=' . $value;
				//if we are not on the last one then separator
				if($index < count($keys) - 1) {
					$r .= ';';
				}
				$index++;
			}
		}
		return $r;
	}

	/**
	 * Takes an extra line and converts it into a key/value array.
	 *
	 * e.g. "Title;$description=The title from the report." will return $ra['description'] = 'The title from the report.'
	 *
	 * @return array
	 */
	public function getKeyValueArrayOfAllValues() {
		return $this->extrasArray;
	}

}
?>