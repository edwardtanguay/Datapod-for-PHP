<?php
/* @var $pluralObject DatapodItems */

class DatapodItem {

	protected $pluralObject;

	function __construct($datapodDocumentBlock, $pluralObject) {
		$this->pluralObject = $pluralObject;
		$blockLines = $datapodDocumentBlock->getBlockLines();
		$index = 0;
		if(count($this->pluralObject->getDataTypes()) > 0) {
			foreach($this->pluralObject->getDataTypes() as $dataType) {
				/* @var $dataType DataType */
				$idCode = $dataType->getIdCode();
				$setMethod = 'set' . qstr::forcePascalNotation($idCode);
				$fieldValue = qdat::getDataFromFieldIndex($blockLines, $index);
				$parsedFieldValue = qstr::parseBbcode($fieldValue);
				$this->$setMethod($parsedFieldValue);
				$index++;
			}
		}
	}

	function getRawDataHtml() {
		$r = '';
		$r .= '<hr/>';
		$r .= '<ul>';
		if(count($this->pluralObject->getDataTypes()) > 0) {
			foreach($this->pluralObject->getDataTypes() as $dataType) {
				/* @var $dataType DataType */
				$r .= $dataType->displayAsHtmlListItem($this);
			}
		}
		$r .= '</ul>';
		return $r;
	}

	public function getValueOfProperty($propertyIdCode) {
		$getMethod = 'get' . qstr::forcePascalNotation($propertyIdCode);
		return $this->$getMethod();
	}

	function getSummaryViewHtml() {
		$r = '';
		$r .= '<div class="' . $this->pluralObject->getSingularCamelNotation() . '">';
		$r .= '<p class="title">' . $this->getPermalinkWithTitle() . '<p>';
		$r .= '</div>';
		return $r;
	}

	public function getPermalink() {
		return 'index.php?page=' . $this->pluralObject->getItemTypeIdCode() . '&idCode=' . $this->getIdCode();
	}
	
	public function getPermalinkWithTitle() {
		return '<a href="' . $this->getPermalink() . '">' . $this->getTitle() . '</a>';
	}

	function getSingleViewHtml() {
		$r = '';
		$singleItemCssClass = $this->pluralObject->getSingularCamelNotation() . 'ViewSingle';
		$r .= '<div class="' . $singleItemCssClass . '">';
		$r .= '<p class="title">' . $this->getTitle() . '</p>';
		$r .= '[TODO: override $datapodItem->getSingleViewHtml() into ' . $this->pluralObject->getSingularCamelNotation() . '.php]' . qstr::BR();
		$r .= '[TODO: CSS class: ' . $singleItemCssClass . ']';
		$r .= '</div>';
		return $r;
	}

}

?>