<?php

class DatapodDocumentBlock {

	protected $blockMarker;
	protected $idText;
	protected $blockLines;
	protected $stringBlock;

	public function getBlockMarker() {
		return $this->blockMarker;
	}
	public function setBlockMarker($value) {
		$this->blockMarker = $value;
	}

	public function getIdText() {
		return $this->idText;
	}
	public function setIdText($value) {
		$this->idText = $value;
	}

	public function getBlockLines() {
		return $this->blockLines;
	}
	public function setBlockLines($value) {
		$this->blockLines = $value;
	}

	public function getStringBlock() {
		return $this->stringBlock;
	}
	public function setStringBlock($value) {
		$this->stringBlock = $value;
	}

}

?>