<?php

class DatapodItems {

	protected $datapodDocumentBlocks;
	protected $dataTypeDefinitionLines = array();
	protected $dataTypes = array();

	protected $itemTypeIdCode;
	public function getItemTypeIdCode() { return $this->itemTypeIdCode; }
	protected $singularCamelNotation;
	public function getSingularCamelNotation() { return $this->singularCamelNotation; }
	protected $singularPascalNotation;
	public function getSingularPascalNotation() { return $this->singularPascalNotation; }
	protected $singularTitleNotation;
	public function getSingularTitleNotation() { return $this->singularTitleNotation; }
	protected $singularTextNotation;
	public function getSingularTextNotation() { return $this->singularTextNotation; }
	protected $pluralCamelNotation;
	public function getPluralCamelNotation() { return $this->pluralCamelNotation; }
	protected $pluralPascalNotation;
	public function getPluralPascalNotation() { return $this->pluralPascalNotation; }
	protected $pluralTitleNotation;
	public function getPluralTitleNotation() { return $this->pluralTitleNotation; }
	protected $pluralTextNotation;
	public function getPluralTextNotation() { return $this->pluralTextNotation; }

	//
	//
	//        public string PluralCamelNotation { get { return _pluralCamelNotation; } }
	//        public string PluralPascalNotation { get { return _pluralPascalNotation; } }
	//        public string PluralTitleNotation { get { return _pluralTitleNotation; } }
	//        public string PluralTextNotation { get { return _pluralTextNotation; } }
	//        public string SingularCamelNotation { get { return _singularCamelNotation; } }
	//        public string SingularPascalNotation { get { return _singularPascalNotation; } }
	//        public string SingularTitleNotation { get { return _singularTitleNotation; } }
	//        public string SingularTextNotation { get { return _singularTextNotation; } }
	//
	//


	protected $items = array();
	public function getItems() { return $this->items; }
	public function setItems($value) { $this->items = $value; }


	public function getItemWithIdCode($desiredIdCode) {
		if(count($this->items) > 0) {
			foreach($this->items as $item) {
				if($item->getIdCode() == $desiredIdCode) {
					return $item;
				}
			}
		}
	}

	public function getDataTypes() {
		return $this->dataTypes;
	}

	public function __construct($datapodDocumentBlocks = null) {
		$this->fillItemTypeVariables();
		if($datapodDocumentBlocks != null) {
			$this->datapodDocumentBlocks = $datapodDocumentBlocks;
			$this->fillDataTypeDefinitionLines();
			$this->createDataTypes();
			if(count($this->datapodDocumentBlocks) > 0) {
				foreach($this->datapodDocumentBlocks as $datapodDocumentBlock) {
					$item = $this->getNewItem($datapodDocumentBlock);
					$this->items[] = $item;
				}
			}
		}
	}

	private function fillItemTypeVariables() {
		$this->itemTypeIdCode = qstr::forceCamelNotation(get_class($this));
		$this->pluralCamelNotation = $this->itemTypeIdCode;
		$this->pluralPascalNotation = qstr::forcePascalNotation($this->itemTypeIdCode);
		$this->pluralTitleNotation = qstr::forceTitleNotation($this->itemTypeIdCode);
		$this->pluralTextNotation = qstr::forceTextNotation($this->itemTypeIdCode);
		$this->singularCamelNotation = qstr::forceSingular($this->pluralCamelNotation);
		$this->singularPascalNotation = qstr::forceSingular($this->pluralPascalNotation);
		$this->singularTitleNotation = qstr::forceSingular($this->pluralTitleNotation);
		$this->singularTextNotation = qstr::forceSingular($this->pluralTextNotation);
	}

	private function createDataTypes() {
		if(count($this->dataTypeDefinitionLines) > 0) {
			foreach($this->dataTypeDefinitionLines as $dataTypeDefinitionLine) {
				$this->dataTypes[] = DataType::Create($dataTypeDefinitionLine);
			}
		}
	}


	public function getRawDataHtml() {
		$r = '';
		if(count($this->items) > 0) {
			foreach($this->items as $item) {
				$r .=  $item->getRawDataHtml();
			}
		}
		return $r;
	}

	public function getSummaryViewHtml() {
		$r = '';
		if(count($this->items) > 0) {
			foreach($this->items as $item) {
				$r .=  $item->getSummaryViewHtml();
			}
		}
		return $r;
	}
	
	public function getCount() {
		return count($this->items);
	}

	/**
	 * receives another full class and merges the contents into this one
	 */
	public function mergeItems($itemsToMerge) {
		$itemsToMergeCollection = $itemsToMerge->getItems();
		$this->items = qstr::smartArrayMerge($this->items, $itemsToMergeCollection);
		return $this;
	}
	
	
	public function showDebugInfo() {
		$r = '';
		$r .= '<div style="background-color:#eee; margin: 10px 0">';
		$r .= '<div style="background-color: #ddd;color: #999;font-weight:bold; font-size: 11pt"><span style="color:#000">' . $this->getPluralPascalNotation() . '</span> inherits from DatapodItems:</div>';
		//TODO: convert these to datatypes themselves and call on a nice presentation function to output datatypes in table, etc.
		$r .= $this->showFieldInfoRow('ItemTypeIdCode: ', $this->itemTypeIdCode);
		$r .= $this->showFieldInfoRow('Title: ', $this->pluralTitleNotation);
		$r .= $this->showFieldInfoRow('Number of Items: ', $this->getCount());
		
		$itemsHtmlContent = '';
		if(count($this->items) > 0) {
			foreach($this->items as $item) {
				$itemsHtmlContent .= $item->getRawDataHtml();
			}
		}
		$r .= qpre::displayHtmlInScrollBox($itemsHtmlContent);
		$r .= '</div>';
		return $r;
	}
	
	public function showFieldInfoRow($label, $value) {
		$r = '';
		$r .= '<div><span style="color:#333">' . $label . ': </span><span style="font-family:courier; color:navy">' . $value . '</span></div>';
		return $r;
	}

}
?>