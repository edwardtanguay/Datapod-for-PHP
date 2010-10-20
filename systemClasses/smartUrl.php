<?php
/**
 * Abstracts a URL line and makes it simple to add and subtract parameters, etc.
 *
 */
class SmartUrl {
	/*
	 * The file name in the URL, e.g. "categories.php".
	 */
	private $fileName = null;

	/*
	 * The collection of querystring variable pairs, e.g. "id=5".
	 */
	private $querystringVariables = null;

	/**
	 * The file name in the URL, e.g. "categories.php".
	 *
	 * @return string
	 */
	public function getFileName() {
		return $this->fileName;
	}

	/**
	 * The file name in the URL, e.g. "categories.php".
	 */
	public function setFileName($value) {
		$this->fileName =$value;
	}

	function __construct($fileName) {
		$this->fileName = $fileName;
	}

	/**
	 * Adds a parameter in the URL.
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function addQuerystringVariable($name, $value) {
		$querystringVariable = $name . '=' . $value;
		$this->querystringVariables[] = $querystringVariable;
	}

	/**
	 * Outputs the URL with all parameter variables.
	 *
	 * @return string
	 */
	public function render() {
		$r = '';
		$r .= $this->fileName . $this->buildQueryString();
		return $r;
	}

	/**
	 * Creates the querystring for the URL, e.g. "" or "?id=5" or "?id=5&status=on".
	 *
	 * @return string
	 */
	private function buildQuerystring() {
		$r = '';
		if(count($this->querystringVariables) == 0) {
			//do nothing
		} else {
			//go through each and build "id=5&count=66" string
			$count = 1;
			foreach($this->querystringVariables as $querystringVariable) {
				$r .= $querystringVariable;
				if($count != count($this->querystringVariables)) {
					$r .= '&amp;';
				}
				$count++;
			}
			//add the "?" (there is at least one variable here)
			$r = '?' . $r;
		}
		return $r;
	}
}

?>