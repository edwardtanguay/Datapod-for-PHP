<?php
class DataType {

	protected $idCode;
	protected $label;
	protected $dataTypeIdCode;
	protected $rawData; //allows e.g. for wholeNumber, rawData="n/a" but value=0, and isValid = false
	protected $value;
	protected $isValid = true;

	public function getIdCode() { return $this->idCode;	}
	public function getDataTypeIdCode() { return $this->dataTypeIdCode;	}

	public function __construct($idCode, $label, $dataTypeIdCode) {
		$this->idCode = $idCode;
		$this->label = $label;
		$this->dataTypeIdCode = $dataTypeIdCode;
	}

	public static function Create($dataTypeDefinitionLine) {
		$idCode = '';
		$label = '';
		$dataTypeIdCode = '';
		$parts = qstr::breakIntoParts($dataTypeDefinitionLine, ';');
		if(count($parts) == 0) {
			$idCode = '';
			$label = 'UNDEFINED';
			$dataTypeIdCode = '';
		} else if(count($parts) == 1) {
			$idCode = qstr::forceCamelNotation($parts[0]);
			$label = $parts[0];
			$dataTypeIdCode = 'line';
		} else {
			$idCode = qstr::forceCamelNotation($parts[0]);
			$label = $parts[0];
			$dataTypeIdCode = $parts[1];
		}
		switch ($dataTypeIdCode) {
			case 'date':
				return new DataTypeDate($idCode, $label, $dataTypeIdCode);
			case 'url':
				return new DataTypeUrl($idCode, $label, $dataTypeIdCode);
			case 'email':
				return new DataTypeEmail($idCode, $label, $dataTypeIdCode);
			default:
				//line
				return new DataType($idCode, $label, $dataTypeIdCode);
		}
	}

	public function displayRawDataNice() {
		return ' <span class="rawData">' . $this->rawData . '</span></li>';
	}

	protected function initializeValue($rawData)
	{
		$this->rawData = $rawData;
	}

	public function displayAsHtmlListItem($datapodItem) {
		/* @var $datapodItem DatapodItem */
		$r = '';
		$this->initializeValue($datapodItem->getValueOfProperty($this->idCode));
		$propertyLabel = $this->label;
		$lines = qstr::convertStringBlockToLines($this->rawData);
		$numberOfLines = count($lines);
		$prefix = '<li>' . $propertyLabel . ' <span class="rawDataDataType">[' . $this->dataTypeIdCode . ']</span> =';
		if($numberOfLines == 0) {
			$r .= $prefix . ' <span class="rawDataEmpty">(EMPTY)</span></li>';
		} else if(count($lines) == 1) {
			$r .= $prefix . $this->displayRawDataNice();
		} else {
			if(count($lines) > 0) {
				$r .= $prefix . ' <div class="rawData">';
				foreach($lines as $line) {
					$r .= $line . '<br/>';
				}
				$r .= '</div></li>';
			}
		}
		return $r;
	}

}
?>