<?php
class PageItems extends DatapodItems {

	protected function getNewItem($datapodDocumentBlock) {
		return new PageItem($datapodDocumentBlock, $this);
	}

	protected function fillDataTypeDefinitionLines() {
		$this->dataTypeDefinitionLines[] = "Id Code";
		$this->dataTypeDefinitionLines[] = "Menu";
		$this->dataTypeDefinitionLines[] = "Title";
		$this->dataTypeDefinitionLines[] = "Description;p";
		$this->dataTypeDefinitionLines[] = "Kind";
		$this->dataTypeDefinitionLines[] = "Access Groups";
		$this->dataTypeDefinitionLines[] = "Display Order;wn";
		$this->dataTypeDefinitionLines[] = "Extras";
	}

	public function getMenuHtmlDiv() {
		$r = '';
		if(count($this->items) > 0) {
			$r .= '<div id="nav">';
			$count = 1;
			foreach($this->items as $pageItem) {
				/* @var $pageItem PageItem */
				//if($pageItem->getIdCode() != 'home') {
				$fileName = qsys::getCurrentFileName();
				$r .=  '<a href="' . $fileName . '?page=' . $pageItem->getIdCode() . '">' . $pageItem->getTitle() . '</a>';
				if($count < count($this->items)) {
					$r .= '&nbsp;&nbsp;';
				}
				//}
				$count++;
			}
			$r .= '</div>';
		}
		return $r;

		//<div id="nav"><a href="#">Blog</a>&nbsp;&nbsp; <a href="#">About</a>&nbsp;&nbsp;
		//<a href="#">Archives</a>&nbsp;&nbsp; <a href="#">Contact</a></div>

	}

	public function getMenuHtml($selectedClassName = '', $lastItemClassName = '', $currentLastItemClassName = '') {
		$r = '';
		$currentPageIdCode = qsys::getCurrentPageItemIdCode();
		$selectedClassAttribute = qstr::isEmpty($selectedClassName) ? '' : ' class="' . $selectedClassName . '"';
		$lastItemClassAttribute = qstr::isEmpty($lastItemClassName) ? '' : ' class="' . $lastItemClassName . '"';
		$currentLastItemClassAttribute = qstr::isEmpty($currentLastItemClassName) ? '' : ' class="' . $currentLastItemClassName . '"';
		$currentItemSelected = false;
		if(count($this->items) > 0) {
			$r .= '<ul>';
			$count = 1;
			foreach($this->items as $pageItem) {
				/* @var $pageItem PageItem */
				$fileName = qsys::getCurrentFileName();
				if($pageItem->getIdCode() == $currentPageIdCode) {
					$liveSelectedClassAttribute = $selectedClassAttribute;
					$currentItemSelected = true;
				} else {
					$liveSelectedClassAttribute = '';
					$currentItemSelected = false;
				}
				if(count($this->items) == $count) {
					$liveLastItemClassAttribute = $lastItemClassAttribute;
					if($currentItemSelected) {
						$liveLastItemClassAttribute = $currentLastItemClassAttribute;
						$liveSelectedClassAttribute = '';
					}
				} else {
					$liveLastItemClassAttribute = '';
				}
				$r .=  '<li' . $liveSelectedClassAttribute . '><a href="' . $fileName . '?page=' . $pageItem->getIdCode() . '"' . $liveLastItemClassAttribute . '>' . $pageItem->getTitle() . '</a></li>';
				$count++;
			}
			$r .= '</ul>';
		}
		return $r;
	}

	/**
	 * Returns PageItems object with filtered items collection.
	 */
	public function dqlMainMenu() {
		$filteredItems = array();
		if(count($this->items) > 0) {
			foreach($this->items as $pageItem) {
				/* @var $pageItem PageItem */
				if($pageItem->getMenu() == 'main') {
					$filteredItems[] = $pageItem;
				}
			}
		}
		$pageItems = new PageItems();
		$pageItems->setItems($filteredItems);
		return $pageItems;
	}

}
?>