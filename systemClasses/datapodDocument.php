<?php

class DatapodDocument {

	private $content;
	private $blockMarker;
	private $lines = array();
	private $datapodDocumentBlocks = array();
	private $itemTypeIdCode;
	private $remoteItemTypeIdCode;

	public function getDatapodDocumentBlocks($itemTypeIdCode = '', $remoteItemTypeIdCode = '') {
		$ra = array();
		$this->itemTypeIdCode = $itemTypeIdCode;
		$this->remoteItemTypeIdCode = qstr::isEmpty($remoteItemTypeIdCode) ? $this->itemTypeIdCode : $remoteItemTypeIdCode;
		$singularItemTypeIdCode = qstr::forceSingular($remoteItemTypeIdCode);
		foreach ($this->datapodDocumentBlocks as $datapodDocumentBlock) {
			if($datapodDocumentBlock->getIdText() == $singularItemTypeIdCode || $itemTypeIdCode == '') {
				$ra[] = $datapodDocumentBlock;
			}
		}
		return $ra;
	}

	function __construct($content, $blockMarker) {

		$this->content = $content;
		$this->blockMarker = $blockMarker;
		$this->lines = qstr::convertStringBlockToLines($this->content);

		$this->initialize();
	}


	private function initialize() {

		$recordingBlock = false;
		$currentIdText = "";
		$currentBlockLines = array();
		$recordingMultilineField = false;
		$currentMultilineFieldLines = array();

		if(count($this->lines) > 0) {
			$lineNumber = 0;
			foreach ($this->lines as $line) {
				$lineNumber++;

				$fieldIdCode = qstr::getPrecedingFieldIdCode($line);
				$fieldValue = qstr::removePrecedingFieldName($line);

				//if beginning of multiline field
				if(qstr::beginsWith($fieldValue, "[[")) {
					$recordingMultilineField = true;
					continue;
				}

				//if end of multiline field
				if(qstr::beginsWith($line, "]]")) {
					$block = qstr::convertLinesToStringBlock($currentMultilineFieldLines);
					$currentBlockLines[] = $fieldIdCode . "::" . $block;
					$recordingMultilineField = false;
					$currentMultilineFieldLines = array();
					continue;
				}

				//if in multiline field
				if($recordingMultilineField) {
					$currentMultilineFieldLines[] = $line;
					continue;
				}


				//if at beginning of block
				if(qstr::beginsWith($line, $this->blockMarker)) {
					$recordingBlock = true;
					$currentIdText = qstr::chopLeft($line, $this->blockMarker);
					continue;
				}

				//if end of a block, so make the object and add it to the collection
				if(qstr::isEmpty($line) && count($currentBlockLines) > 0)
				{
					$this->datapodDocumentBlocks[] = $this->saveCurrentBlock($currentIdText, $currentBlockLines);
					$currentBlockLines = array();
					$currentIdText = "";
					$recordingBlock = false;
					continue;
				}

				//if in block
				if($recordingBlock)
				{
					$currentBlockLines[] = $line;
				}

			}
		}

		//save last one
		if((qstr::isEmpty($line) && count($currentBlockLines) > 0) || $lineNumber == count($this->lines)) {
			$this->datapodDocumentBlocks[] = $this->saveCurrentBlock($currentIdText, $currentBlockLines);
		}

	}

	private function saveCurrentBlock($currentIdText, $currentBlockLines) {
		$ddb = new DatapodDocumentBlock();
		$ddb->setBlockMarker($this->blockMarker);
		$ddb->setIdText($currentIdText);
		$ddb->setBlockLines($currentBlockLines);
		$ddb->setStringBlock(qstr::convertLinesToStringBlock($currentBlockLines));
		return $ddb;
	}
	
	public function showDebugInformation() {
		$r = '';
		if(count($this->datapodSources) > 0) {
			foreach($this->datapodSources as $datapodSource) {
				$r .= $datapodSource->showDebugInformation();				
			}
		}	
		return $r;		
	}		

}
?>