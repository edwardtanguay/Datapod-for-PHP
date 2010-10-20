<?php
class DatapodManager {

	private static $instance;

	private $datapodSources = array();
	private $itemTypes = array();
	private $currentPageIdCode;

	public function getItemTypes() { return $this->itemTypes; }
	public function getCurrentPageIdCode() { return $this->currentPageIdCode; }

	private function __construct() {
		$this->currentPageIdCode = qsys::getCurrentPageItemIdCode();
		Config::configureDatapodManager($this);
	}

	public static function getInstance(){
		if(!isset(self::$instance)) {
			self::$instance = new DatapodManager();
		}
		return self::$instance;
	}

	public function loadFromDatapodSource($itemTypeList, $sourceLine) {
		$datapodSource = new DatapodSource($itemTypeList, $sourceLine);
		$this->datapodSources[] = $datapodSource;
		if(count($datapodSource->getItemTypes()) > 0) {
			$index = 0;
			foreach($datapodSource->getItemTypes() as $itemType) {
				$keys = array_keys($datapodSource->getItemTypes());
				$values = array_values($datapodSource->getItemTypes());
				$items = $values[$index];
				$itemTypeIdCode = $keys[$index];

				$this->smartAddItems($itemTypeIdCode, $items);

				$index++;
			}
		}
	}

	/**
	 * if collection exists already, add to it, otherwise create it
	 */
	private function smartAddItems($itemTypeIdCode, $items) {
		if($items != null) {
			if($items->getCount() > 0) {
				if(!array_key_exists($itemTypeIdCode, $this->itemTypes)) {
					//qdev::showItemsObject('being added to collection', $items, __FILE__, __LINE__);
					$this->itemTypes[$itemTypeIdCode] = $items;
				} else {
					$currentItems = $this->itemTypes[$itemTypeIdCode];
					$this->itemTypes[$itemTypeIdCode] = $currentItems->mergeItems($items);
				}
			}
		}
	}

	public function loadFromDataDirectory($itemTypeList) {
		$dataRelativePathAndFileNames = qfil::getRelativePathAndFileNamesInDirectoryRecursiveWithExtension('data', 'txt');
		if(count($dataRelativePathAndFileNames) > 0) {
			foreach($dataRelativePathAndFileNames as $dataRelativePathAndFileName) {
				$this->loadFromDatapodSource($itemTypeList, $dataRelativePathAndFileName);
			}
		}
	}

	public function display($itemTypeIdCode, $methodName) {
		$items = $this->getItems($itemTypeIdCode);
		if($items != null)
		echo $items->$methodName();
	}

	public function getItems($itemTypeIdCode, $dql = '') {
		$items = $this->itemTypes[$itemTypeIdCode];
		if($items != null) {
			if(!qstr::isEmpty($dql)) {
				$items = $items->$dql();
				return $items;
			} else {
				return $items;
			}
		}
	}

	public function getItemWithIdCode($itemTypeIdCode, $idCode) {
		$items = $this->getItems($itemTypeIdCode);
		if(count($items) > 0) {
			foreach($items->getItems() as $item) {
				if($item->getIdCode() == $idCode) {
					return $item;
				}
			}
		}
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
	
	public function getResultFromItemMethodWithIdCode($itemTypeIdCode, $idCode, $methodName) {
		$item = $this->getItemWithIdCode($itemTypeIdCode, $idCode);
		if($item != null) {
			return $item->$methodName();
		} else {
			return '';
		}
	}

	public function callItemMethodWithIdCode($itemTypeIdCode, $idCode, $methodName) {
		$item = $this->getItemWithIdCode($itemTypeIdCode, $idCode);
		if($item != null) {
			return $item->$methodName();
		} else {
			return '';
		}
	}

}
?>