<?php

class TextParserCreatePage extends TextParser {
	
	function __construct() {
		parent::__construct();
		$this->idCode = 'createPage';
		$this->title = 'Create Page';
		$this->instructions = 'Paste in the page description block';
	}
	

	public function renderParsedText() {
		$r = '';
		if(count($this->parseLines) > 0) {
			foreach($this->parseLines as $parseLine) {
				$r .= 'create page: ' . $parseLine . qstr::BR();
			}
		}
		return $r;
	}

}

?>