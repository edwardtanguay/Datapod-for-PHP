<?php
class DataTypeUrl extends DataType {

	public function displayRawDataNice() {
		if($this->isValid) {
			return ' <span class="rawData">' . $this->value . '</span> <span class="rawDataExtra"><a href="' . $this->value . '">go to URL</a></span></li>';
		} else {
			return ' <span class="rawData">' . $this->rawData . '</span> <span class="rawDataError">(invalid URL)</span></li>';
		}
	}

	protected function initializeValue($rawData) {
		parent::initializeValue($rawData);
		if(qstr::isValidUrl($this->rawData)) {
			$this->isValid = true;
			$this->value = $this->rawData;
		} else  {
			$this->isValid = false;
			$this->value = '';
		}
	}
}
?>