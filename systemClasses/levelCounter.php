<?php

class LevelCounter {

	private $levelCounts = array();
	private $lastLevel = 0;

	function __construct() {
		$this->levelCounts[1] = 0;
		$this->levelCounts[2] = 0;
		$this->levelCounts[3] = 0;
		$this->levelCounts[4] = 0;
	}

	public function increaseLevel($level) {
		//if we are going back down the outline, then reset all level till then to zero
		if($this->lastLevel > $level) {
			for($tempLevel = $this->lastLevel; $tempLevel > $level ; $tempLevel--) {
				$this->levelCounts[$tempLevel] = 0;
			}
			$this->levelCounts[$this->lastLevel] = 0;
		}
		$this->levelCounts[$level]++;
		$this->lastLevel = $level;
	}

	/**
	 * $levelCounts[1-4], returns e.g. "1.2.2.1"
	 * @param unknown_type $levelCounts
	 */
	public function renderOutlinePrefix() {
		if($this->levelCounts[4] == 0) {
			if($this->levelCounts[3] == 0) {
				if($this->levelCounts[2] == 0) {
					if($this->levelCounts[1] == 0) {
						return '';
					} else {
						return $this->levelCounts[1] . '. ';
					}
				} else {
					return $this->levelCounts[1] . '.' . $this->levelCounts[2] . '. ';
				}
			} else {
				return $this->levelCounts[1] . '.' . $this->levelCounts[2] . '.' . $this->levelCounts[3] . '. ';
			}
		} else {
			return $this->levelCounts[1] . '.' . $this->levelCounts[2] . '.' . $this->levelCounts[3] . '.' . $this->levelCounts[4] . '. ';
		}
	}
}
?>