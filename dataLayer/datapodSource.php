<?php

class DatapodSource {

	private $content;
	private $datapodDocument;
	private $sourceLine;
	private $lines;
	private $sourceLineTypeIdCode;
	private $itemTypeList;
	private $itemTypeIdCodes = array(); //how items are referred to in this site, e.g. "photoSiteWebTexts"
	private $remoteItemTypeIdCodes = array(); //how items are referred to in remote file, e.g. "webTexts"
	private $itemTypes = array();


	public function getItemTypes() { return $this->itemTypes; }

	/**
	 *
	 * @param $sourceLine
	 *   googleDoc = "dc7gj86r_99gh7mkwvp"
	 *   url = "http://tanguay.info/runs/data/itemType-runs2009.txt"
	 *   file = "data/public/itemType-podcasts.txt"
	 * @param itemTypeList
	 *   e.g. "podcasts, podcastSources, links"
	 */
	function __construct($itemTypeList, $sourceLine) {
		$this->itemTypeList = $itemTypeList;
		$this->sourceLine = $sourceLine;
		$this->sourceLineTypeIdCode = qsys::getSourceLineTypeIdCode($sourceLine);
		switch ($this->sourceLineTypeIdCode) {
			case 'googleDoc':
				$this->content = qsys::getTextFromGoogleDoc($sourceLine);
				$this->content = qstr::forceWindowsNewlinesIfNecessary($this->content);
				break;
			case 'dropbox':
				$this->content = qsys::getTextFromUrl($sourceLine);
				$this->content = qstr::forceWindowsNewlinesIfNecessary($this->content);
				break;
			case 'url':
				$this->content = qsys::getTextFromUrl($sourceLine);
				break;
			case 'file':
				$absolutePathAndFileName = qsys::getAbsolutePathAndFileNameFromRelativePathAndFileName($sourceLine);
				$this->content = qfil::getFileAsStringBlockWithAbsolutePathAndFileName($absolutePathAndFileName);
				break;
			default:
				$this->content = qsys::getTextFromUrl($sourceLine);
				break;
		}
		$this->datapodDocument = new DatapodDocument($this->content, "==");
		$this->lines = qstr::convertStringBlockToLines($this->content);
		$this->instantiateItemTypes();
	}
	private function instantiateItemTypes() {
		$this->processItemTypeIdCodes();
		if(count($this->itemTypeIdCodes) > 0) {
			$index = 0;
			foreach($this->itemTypeIdCodes as $itemTypeIdCode) {
				$remoteItemTypeIdCode = $this->remoteItemTypeIdCodes[$index];
				$datapodDocumentBlocks = $this->datapodDocument->getDatapodDocumentBlocks($itemTypeIdCode, $remoteItemTypeIdCode);
				$items = Config::getItemsForCustomItemType($itemTypeIdCode, $datapodDocumentBlocks);
				$this->itemTypes[$itemTypeIdCode] = $items;
				$index++;
			}
		}
	}

	/**
	 * e.g. "reportSiteWebTexts(webTexts), versions, workshops"
	 */
	private function processItemTypeIdCodes() {
		$rawItemTypeIdCodes = qstr::breakIntoParts($this->itemTypeList, ',');
		if(count($rawItemTypeIdCodes) > 0) {
			foreach($rawItemTypeIdCodes as $rawItemTypeIdCode) {
				if(qstr::contains($rawItemTypeIdCode, '(')) {
					$trimmedRawItemTypeIdCode = qstr::chopRight($rawItemTypeIdCode, ')');
					$parts = qstr::breakIntoParts($trimmedRawItemTypeIdCode, '(');
					$this->itemTypeIdCodes[] = $parts[0];
					$this->remoteItemTypeIdCodes[] = $parts[1];
				} else {
					$this->itemTypeIdCodes[] = $rawItemTypeIdCode;
					$this->remoteItemTypeIdCodes[] = $rawItemTypeIdCode;
						
				}
			}
		}

	}

	public function showDebugInformation() {
		$r = '';
		$r .= '<div class="debug">';
		$r .= qdev::showVariable('itemTypeList', $this->itemTypeList);
		$r .= qdev::showVariable('sourceLine', $this->sourceLine);
		$r .= qdev::showVariable('sourceLineTypeIdCode', $this->sourceLineTypeIdCode);
		$r .= qdev::showVariable('count(itemTypeIdCodes)', count($this->itemTypeIdCodes));
		$r .= qdev::showVariable('count(lines)', count($this->lines));
		$r .= qpre::displayHtmlInScrollBox($this->content);
		$r .= qpre::displayHtmlInScrollBox(qdev::getAsciiCharacters($this->content));
		if(count($this->itemTypes) > 0) {
			foreach($this->itemTypes as $itemTypeIdCode => $items) {
				if($items != null) {
					$r .= $items->showDebugInfo();
				}
			}
		}
		$r .= '</div>';
		return $r;
	}

}
?>