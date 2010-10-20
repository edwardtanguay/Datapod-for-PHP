<?php
class CssResources extends DatapodItems {

	protected function getNewItem($datapodDocumentBlock) {
		return new CssResource($datapodDocumentBlock, $this);
	}
	
	protected function fillDataTypeDefinitionLines() {
		$this->dataTypeDefinitionLines[] = "Title";
		$this->dataTypeDefinitionLines[] = "Url;url";
	}
	
	public function getListHtml() {
		$r = '';
		if(count($this->items) > 0) {
			$r .= '<ul>';
			$count = 1;
			foreach($this->items as $cssResource) {
				/* @var $cssResource CssResource */
				$r .=  '<li><a href="' . $cssResource->getUrl() . '">' . ucfirst($cssResource->getTitle()). '</a></li>';
				$count++;
			}
			$r .= '</ul>';
		}
		return $r;	
	}	
}
?>
