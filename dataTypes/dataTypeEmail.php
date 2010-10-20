<?php
class DataTypeEmail extends DataType {

	public function displayRawDataNice() {
		if($this->isValid) {
			return ' <span class="rawData">' . $this->value . '</span> <span class="rawDataExtra"><a href="mailto:' . $this->value . '">write e-mail</a></span></li>';
		} else {
			return ' <span class="rawData">' . $this->rawData . '</span> <span class="rawDataError">(invalid e-mail)</span></li>';
		}
	}

	protected function initializeValue($rawData) {
		parent::initializeValue($rawData);
		if(qstr::isValidEmail($this->rawData)) {
			$this->isValid = true;
			$this->value = $this->rawData;
		} else  {
			$this->isValid = false;
			$this->value = '';
		}
	}
}
?>