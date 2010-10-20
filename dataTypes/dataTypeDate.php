<?php
class DataTypeDate extends DataType {

	public function displayRawDataNice() {
		if($this->isValid) {
			return ' <span class="rawData">' . $this->value . '</span> <span class="rawDataExtra">' . qdat::getNiceAmericanDayAndDate($this->value) . '</span></li>';
		} else {
			return ' <span class="rawData">' . $this->rawData . '</span> <span class="rawDataError">(invalid date)</span></li>';
		}
	}

	protected function initializeValue($rawData) {
		parent::initializeValue($rawData);
		if(qdat::isValidDate($this->rawData)) {
			$this->isValid = true;
			$this->value = $this->rawData;
		} else  {
			$this->isValid = false;
			$this->value = '';
		}
	}

}
?>