<?php
include_once('system/systemIncludeFiles.php');

class Config {

	/**
	 * Do not change, this identifies this version of Datapod to help with question, updating, etc.
	 */
	public static function datapodVersionIdCode() {
		return '00003';
	}

	public static function siteUrlPath() {
		return qsys::getCurrentBaseUrlPath();
	}
	
	/**
	 * e.g. if times on site are in Berlin time, and the server is in New York, then enter -6, i.e. 6 hours back.
	 */
	public static function getServerOffsetInHours() {
		return -6;
	}	

	public static function configureDatapodManager($datapodManager) {
		//CONFIG TODO: add each datasource here
		//first parameter = source file of data, can be one of these three:
		//	url = 'http://www.somewebsite.com/data/podcasts.txt'
		//	local file path under website directory = 'data/podcasts.txt'
		//	code of published google document = 'dc7gj86r_22pv9qqffp'
		//second parameter = comma-separated list of itemtype codes, e.g.
		//	'pocasts, podcastSources, ideas'
		
		$datapodManager->loadFromDataDirectory('pageItems');
		$datapodManager->loadFromDatapodSource('cssResources', 'dc7gj86r_99ghpmkwvp');
	}

	public static function getItemsForCustomItemType($itemTypeIdCode, $datapodDocumentBlocks) {
		switch ($itemTypeIdCode) {
			case 'pageItems':
				return new PageItems($datapodDocumentBlocks);
			//CONFIG TODO: add case statement for each custom itemtype added:
			case 'cssResources':
				return new CssResources($datapodDocumentBlocks);
			default:
				return null;
		}
	}


	/**
	 * The site title, appears in TITLE and top of page.
	 */
	public static function getSiteTitle() {
		return 'Web Application';
	}

	/**
	 * The site subtitle, appears in TITLE and top of page.
	 */
	public static function getSiteSubtitle() {
		return 'Welcome to this web application';
	}

}

//CONFIG TODO: add files for each itemtype added:
include_once('customModels/cssResource.php');
include_once('customModels/cssResources.php');

$datapodManager = DatapodManager::getInstance();
?>