<?php

class TextParser {

	protected $idCode;
	public function getIdCode() { return $this->idCode; }

	protected $title;
	public function getTitle() { return $this->title; }

	protected $instructions;
	public function getInstructions() { return $this->instructions; }
	
	protected $textToParse;
	public function getTextToParse() { return $this->textToParse; }
	public function setTextToParse($value) { 
		$this->textToParse = $value; 
		$this->parseLines = qstr::convertStringBlockToLines($this->textToParse);
	}
	
	protected $parseLines = array();

	function __construct() {
		$this->idCode = 'undefined';
		$this->title = '(undefined text parser)';
		$this->instructions = 'Paste in text';
	}
	
	public function getTextToParseShow() {
		return qstr::convertBrsToNewlines($this->textToParse);
	}

	public static function instantiate($textParserIdCode) {
		switch ($textParserIdCode) {
			case 'codeWrapper':
				return new TextParserCodeWrapper();
			case 'createItemType':
				return new TextParserCreateItemType();
			case 'createPage':
				return new TextParserCreatePage();
			case 'parseV2bVideoTraining':
				return new TextParserParseV2bVideoTraining();
			default:
				return new TextParser();
		}
	}

	public function getRelativePermalink() {
		return 'index.php?page=textParser&idCode=' . $this->idCode;
	}

	public function getMenu() {
		$r = '';
		$textParserIdCodes = $this->getTextParserIdCodes();
		if(count($textParserIdCodes) > 0) {
			$separator = ' - ';
			foreach($textParserIdCodes as $textParserIdCode) {
				$textParser = TextParser::instantiate($textParserIdCode);
				if($textParserIdCode == $this->idCode) {
					$r .= '<span class="selected">' . $textParser->getTitle() . '</span>' . $separator;
				} else {
					$r .= '<a href="' . $textParser->getRelativePermalink() . '">' . $textParser->getTitle() . '</a>' . $separator;
				}
			}
			$r = qstr::chopRight($r, $separator);
		}
		return $r;
	}

	protected function getTextParserIdCodes() {
		$idCodes = array();
		//gets e.g. "textParsers/TextParserCodeWrapper.php"
		$relativePathAndFileNames = qfil::getRelativePathAndFileNamesInDirectoryRecursiveWithExtension('textParsers', 'php');
		if(count($relativePathAndFileNames) > 0) {
			foreach($relativePathAndFileNames as $relativePathAndFileName) {
				$trimmedName = qstr::chopLeft($relativePathAndFileName, 'textParsers/textParser');
				$trimmedName = qstr::chopRight($trimmedName, '.php');
				$idCode = qstr::forceCamelNotation($trimmedName);
				if(!qstr::isEmpty($idCode)) {
					$idCodes[] = $idCode;
				}
			}
		}
		return $idCodes;
	}

	public function getActionUrl() {
		$r = '';
		$currentFileName = qsys::getCurrentFileName();
		$actionUrl = new SmartUrl($currentFileName);
		$actionUrl->addQuerystringVariable('page', qsys::getCurrentPageItemIdCode());
		$actionUrl->addQuerystringVariable('idCode', $this->idCode);
		return $actionUrl->render();
	}
	
	public function renderParsedText() {
		$r = '';
		$r .= 'nnnnok';
		return $r;
	}

}

?>