<?php

class TextParserCreateItemType extends TextParser {
	
	function __construct() {
		parent::__construct();
		$this->idCode = 'createItemType';
		$this->title = 'Create Item Type';
		$this->instructions = 'Paste in the item type description block';
	}
	


	public function renderParsedText() {
		$r = '';
		if(count($this->parseLines) > 0) {
			foreach($this->parseLines as $parseLine) {
				$r .= 'create item type: ' . $parseLine . qstr::BR();
			}
		}
		return $r;
	}

}

?>