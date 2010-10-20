<?php

/**
 * System functions
 *
 */
class qsys {

	/**
	 * Constant for the linux server
	 *
	 * @var int
	 */
	const SERVERTYPE_LINUX = 1;

	/**
	 * Constant for the linux server
	 *
	 * @var int
	 */
	const SERVERTYPE_WINDOWS = 2;


	/**
	 * Returns the sanitized content of a GET variable.
	 *
	 * Requires PHP 5.2.
	 *
	 * @param string $variableName
	 * @return string
	 */
	public static function getUrlVariable($variableName, $defaultValue = '') {
		$r = '';
		$urlValue = filter_input ( INPUT_GET, $variableName, FILTER_SANITIZE_STRING );
		if(qstr::IsEmpty($urlValue) && !qstr::isEmpty($defaultValue)) {
			$r = $defaultValue;
		} else {
			$r = $urlValue;
		}
		return $r;
	}

	/**
	 *
	 * @param $sourceLine
	 *   googledoc = "dc7gj86r_99gh7mkwvp"
	 *   url = "http://tanguay.info/runs/data/itemType-runs2009.txt"
	 *   file = "data/itemType-podcasts.txt"
	 * @param itemTypeList
	 *   e.g. "podcasts, podcastSources, links"
	 */
	public static function getSourceLineTypeIdCode($sourceLine) {
		if(qstr::Contains($sourceLine,'dl.dropbox.com')) {
			return 'dropbox';
		} else if(qstr::beginsWith($sourceLine, 'http://')) {
			return 'url';
		} else if(qstr::endsWith($sourceLine, '.txt')) {
			return 'file';
		} else {
			return 'googleDoc';
		}
	}



	/**
	 * Gets text from a web-shared Google Doc via screen scraper utility.
	 * @param unknown_type $idCode
	 */
	public static function getTextFromGoogleDoc($idCode) {
		$r = qsys::getTextFromUrl("http://www.tanguay.info/web/gettext/index.php?url=https://docs.google.com/View?id=" . $idCode);
		$r = str_replace(chr(13), "\r\n", $r);
		return $r;
	}


	public static function getTextFromUrl($url) {
		$redirectoryUrl = 'http://www.tanguay.info/web/gettext/index.php?url=' . $url;
		return qsys::getTextFromRawUrl($redirectoryUrl);
	}

	/**
	 * Gets text from a URL without a login.
	 *
	 * @param string $url
	 * @return string
	 */
	public static function getTextFromRawUrl($url) {
		$r = '';
		$user_agent = 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/cookie");
		curl_setopt($ch, CURLOPT_COOKIEFILE, "/tmp/cookie");
		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$r = curl_exec($ch);
		$r = qstr::cleanTextFromExternalUrl($r);
		$r = htmlspecialchars($r); //protect
		return $r;
	}

	/**
	 * Shows human readable, mysql-formatted time stamp.
	 *
	 * @return string timestamp
	 */
	public static function dateTimeStamp() {
		return date('Y-m-d H:i:s', mktime(date('H'), date('i'),date('s')));
	}

	/**
	 * Returns whether the site is running "offline" (locally) or "online" (e.g. at your hosting provider).
	 *
	 * Used e.g. to determine the date/time if your provider is in a different time zone (adjust time only when site is online), we assume that if the site is "localhost" then it is a development site and offline, otherwise it is "online".
	 *
	 * @return string
	 */
	public static function getSiteLocation() {
		$r = '';
		$httpReferer = $_SERVER['HTTP_REFERER'];
		$httpHost = $_SERVER['HTTP_HOST'];
		$serverName = $_SERVER['SERVER_NAME'];
		$identifyingText = 'localhost';
		if(qstr::contains($httpReferer, $identifyingText) || qstr::contains($httpHost, $identifyingText) || qstr::contains($serverName, $identifyingText)) {
			$r .= 'offline';
		} else {
			$r .= 'online';
		}
		return $r;
	}

	/**
	 *
	 * returns e.g. "http://localhost/mainsite" or "http://www.company.com/mainsite"
	 */
	public static function getCurrentBaseUrl() {
		$serverName = $_SERVER['SERVER_NAME']; // e.g. "localhost" or "company.com"
		$siteAndFileName = $_SERVER['PHP_SELF']; // e.g. "/mainsite/index.php"
		$siteName = qstr::getTextBeforeLastMarker($siteAndFileName, '/') . '/';
		return 'http://' . $serverName . $siteName;
	}

	/**
	 *
	 * returns e.g. "http://localhost/mainsite/" or "http://www.company.com/mainsite/"
	 */
	public static function getCurrentBaseUrlPath() {
		return qsys::getCurrentBaseUrl() . '/';
	}

	/**
	 *
	 * Returns whether or not the server is Linux or Windows so as to alter the e.g. NEWLINE character.
	 */
	public static function getServerType() {
		$serverPath = $_SERVER['PATH'];
		if(qstr::contains($serverPath, ':\\Windows')) {
			return qsys::SERVERTYPE_WINDOWS;
		} else {
			return qsys::SERVERTYPE_LINUX;
		}
	}

	/**
	 * Converts e.g. "pageItems" to 'C:\Users\Edward\Documents\webs\dpphpBUILD\data\private\dataItem_pageItems.txt'
	 * Converts e.g. "notes.txt" to 'C:\Users\Edward\Documents\webs\dpphpBUILD\data\private\notes.txt'
	 * also looks for the file in both data\private and data\public
	 * @param string $sourceLine
	 */
	public static function getAbsolutePathAndFileNameFromSourceLine($sourceLine) {
		$fileName = '';
		if(qstr::endsWith($sourceLine, '.txt')) {
			$fileName = $sourceLine;
		} else {
			$fileName = 'dataItem-' . $sourceLine . '.txt';
		}
		$siteFileBasePath = qsys::getCurrentSiteFileBasePath();
		$absolutePathAndFileNamePrivate = $siteFileBasePath . 'data/private/' . $fileName;
		$absolutePathAndFileNamePublic = $siteFileBasePath . 'data/public/' . $fileName;
		$absolutePathAndFileName = qfil::getAbsolutePathAndFileNameThatExists($absolutePathAndFileNamePrivate, $absolutePathAndFileNamePublic);
		return $absolutePathAndFileName;
	}
	
	public static function getAbsolutePathAndFileNameFromRelativePathAndFileName($relativePathAndFileName) {
		$siteFileBasePath = qsys::getCurrentSiteFileBasePath();
		return $siteFileBasePath . $relativePathAndFileName;
	}

	public static function convertRelativePathAndFileNameToUrl($relativePathAndFileName) {
		return qsys::getCurrentBaseUrlPath() . $relativePathAndFileName;
	}

	public static function convertRelativePathAndFileNameToAbsolutePathAndFileName($relativePathAndFileName) {
		return qsys::getCurrentSiteFileBasePath() . $relativePathAndFileName;
	}

	/**
	 * returns on windows e.g. 'C:/Users/Edward/Documents/webs/dpphpBUILD/'
	 * return on linux e.g. '/home/tanguay2/public_html/dpphpBUILD/'
	 * NOTE: we assume there is only one file in the website: index.php
	 */
	public static function getCurrentSiteFileBasePath() {
		$scriptFileName = $_SERVER['SCRIPT_FILENAME'];
		$baseFilePath = qstr::getTextBeforeLastMarker($scriptFileName, '/') . '/';
		return $baseFilePath;
	}

	/**
	 * Converts e.g."dpphp-05012" to "Datapod for PHP - Version 0.50.12".
	 *
	 * @param string $versionIdCode
	 * @return string
	 */
	public static function convertDatapodVersionIdCodeToDatapodVersionTitle($datapodVersionIdCode) {
		$r = '';
		$datapodVersionNumberNumberIdCode = qsys::getDatapodVersionNumberIdCodeFromDatapodVersionIdCode($datapodVersionIdCode);
		$datapodVersionNumberText = qsys::convertDatapodVersionNumberIdCodeToDatapodVersionNumberText($datapodVersionNumberNumberIdCode); // e.g. "0.50.01"
		$r = 'Version&nbsp;' . $datapodVersionNumberText;
		return $r;
	}

	/**
	 * Gets the raw number portion out of the verion ID code.
	 *
	 * e.g. gets "05001" from "dpphp-05001"
	 *
	 * @param string $datapodVersionIdCode
	 * @return string
	 */
	public static function getDatapodVersionNumberIdCodeFromDatapodVersionIdCode($datapodVersionIdCode) {
		$r = '';
		$r = qstr::getTextAfterLastMarkerSoft($datapodVersionIdCode, '-'); // e.g. "05001"
		return $r;
	}

	/**
	 * Converts e.g. "03009" to "0.30.09".
	 *
	 * @param string $versionNumberIdCode
	 * @return string
	 */
	public static function convertDatapodVersionNumberIdCodeToDatapodVersionNumberText($versionNumberIdCode) {
		$r = '';
		$mainVersionNumber = substr($versionNumberIdCode,0,1);
		$subVersionNumber = substr($versionNumberIdCode,1,2);
		$changeNumber = substr($versionNumberIdCode,3,2);
		$r .= $mainVersionNumber . '.' . $subVersionNumber . '.' . $changeNumber;
		return $r;
	}

	public static function getVersionName() {
		$currentDatapodVersionIdCode = Config::datapodVersionIdCode();
		return 'Datapod for PHP ' . qsys::convertDatapodVersionIdCodeToDatapodVersionTitle($currentDatapodVersionIdCode);
	}

	public static function getCurrentFileName() {
		$scriptName = $_SERVER['SCRIPT_NAME'];
		$fileName = qstr::getTextAfterLastMarkerSoft($scriptName, '/');
		return $fileName;
	}

	public static function getCurrentPageItemIdCode() {
		return qsys::getUrlVariable('page', 'home');
	}

	/**
	 * Returns the sanitized content of a POST variable.
	 *
	 * Requires PHP 5.2.
	 *
	 * @param string $variableName
	 * @return string
	 */
	public static function getFormVariable($variableName, $defaultValue = '') {
		$r = '';
		$formValue = filter_input ( INPUT_POST, $variableName, FILTER_SANITIZE_STRING );
		if(qstr::IsEmpty($formValue) && !qstr::isEmpty($defaultValue)) {
			$r = $defaultValue;
		} else {
			$r = $formValue;
		}
		$r = trim($r);
		return $r;
	}
	
	/**
	 * This sends the user to another page, write it out fully, for example "formProcessor.php".
	 *
	 * @param string $page
	 */
	public static function gotoPage($page) {
		header("Location: " . $page);
		exit();
	}

	/**
	 * This sends the user to the page specified by the page idCode.
	 *
	 * @param string $pageIdCode
	 */
	public static function gotoPageWithIdCode($pageIdCode) {
		qsys::gotoPage('index.php?pg=' . $pageIdCode);
	}
	

}
?>