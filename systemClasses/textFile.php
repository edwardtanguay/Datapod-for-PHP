<?php
class TextFile {
	private $idCode;
	private $rawTemplateContentLines;
	private $contentLines = array();
	private $variableLineBlockCodes = array();
	private $variableLineBlocks = null;
	private $variableTextCodes = null;
	private $variableTexts = null;
	private $textBlock;

	private $pathAndFileName;
	public function getPathAndFileName() {
		return $this->pathAndFileName;
	}
	public function setPathAndFileName($value) {
		$this->pathAndFileName =$value;
	}

	private $template = '';
	public function getTemplate() {
		return $this->template;
	}
	public function setTemplate($value) {
		$this->template = $value;
	}
	
	private $templatePathAndFileName;
	private function getTemplatePathAndFileName() {
		return $this->templatePathAndFileName;
	}
	public function setTemplatePathAndFileName($value) {
		$this->templatePathAndFileName = $value;
	}

	function __construct($idCode = '') {
		$this->output = $output;
		$this->idCode = $idCode;
		if(!qstr::IsEmpty($idCode)) {
			$this->templatePathAndFileName = "fileTemplates/fileTemplate-" . $this->idCode . '.txt';
		}
	}

	public function addVariable($variableCode, $text) {
		$this->variableTextCodes[] = $variableCode;
		$this->variableTexts[] = $text;
	}

	public function addVariableLineBlock($variableCode, $variableLineBlock) {
		$this->variableLineBlockCodes[] = $variableCode;
		$this->variableLineBlocks[] = $variableLineBlock;
	}

	public function process() {
		$this->readInTemplateContent();
		foreach($this->rawTemplateContentLines as $contentLine) {
			$processedLine = $contentLine;
			for($x = 0; $x < count($this->variableLineBlockCodes) ; $x++) {
				$code = $this->variableLineBlockCodes[$x];
				$blockLines = $this->variableLineBlocks[$x];
				if(count($blockLines) > 0) {
					$block = '';
					$precedingTabs = qstr::getPrecendingTabs($contentLine);
					$countLineInBlock = 1;
					foreach($blockLines as $blockLine) {
						//for tab aligning
						if($countLineInBlock <= 1) {
							$prefix = '';
						} else {
							$prefix = $precedingTabs;
						}
						$block .= $prefix . $blockLine . qstr::NEW_LINE();
						$countLineInBlock++;
					}
				 $processedLine = str_replace($code,$block,$processedLine);
				 $processedLine = trim($processedLine, qstr::NEW_LINE());
				} else {
				 	$processedLine = str_replace($code,'',$processedLine);
				}
			}
			for($x = 0; $x < count($this->variableTextCodes) ; $x++) {
				$code = $this->variableTextCodes[$x];
				$content = $this->variableTexts[$x];
				$processedLine = str_replace($code,$content,$processedLine);
			}
			$this->contentLines[] = $processedLine;
		}
	}

	public function saveAsFile() {
		$this->process();
		qfil::saveLinesToFile($this->pathAndFileName,$this->contentLines);
	}
	
	public function renderAsTextBlock() {
		$r = '';
		$this->process();
		$this->createTextBlock();
		$r .= $this->textBlock;
		return $r;
	}
	
	public function renderAsHtmlBlock() {
		$r = '';
		$lines = $this->renderAsLines();
		if(count($lines) > 0) {
			foreach($lines as $line) {
				$r .= $line . qstr::BR();
			}
		}
		$r = qstr::convertNewlinesToBrs($r);
		return $r;
	}

	public function renderAsLines($extras = '') {
		$blankLines = qstr::getExtrasValue('blankLines', $extras);
		for($x=1; $x <= $blankLines; $x++) {
			$this->contentLines[] = '';
		}
		$this->process();
		return $this->contentLines;
	}

	private function createTextBlock() {
		$this->textBlock = qstr::convertLinesToStringBlock($this->contentLines);
	}

	private function readInTemplateContent() {
		$this->rawTemplateContentLines = qfil::getFileAsLines($this->getTemplatePathAndFileName());
	}
}
?>