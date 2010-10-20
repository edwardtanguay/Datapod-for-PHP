<?php

class TextParserCodeWrapper extends TextParser {

	public function __construct() {
		parent::__construct();
		$this->idCode = 'codeWrapper';
		$this->title = 'Code Wrapper';
		$this->instructions = 'Paste in code and press the button';
	}

	public function renderParsedText() {
		$r = '';
		if(count($this->parseLines) > 0) {
			foreach($this->parseLines as $parseLine) {
				$r .= '$r .= qpre::getTextWithNewline(\'' . $parseLine . '\');' . qstr::BR();
			}
		}
		return $r;
	}

}

?>