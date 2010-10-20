<?php

/**
 * File I/O Functions.
 *
 */
class qfil {

	/**
	 * Gets the contents of a text file and puts it in an array (lines of the file).
	 *
	 * @param file $relativePathAndFileName
	 * @return array @@lines
	 */
	public static function getFileAsLinesWithAbsolutePathAndFileName($absoluatePathAndFileName) {
		$stringBlock = qfil::getFileAsStringBlockWithAbsolutePathAndFileName($absoluatePathAndFileName);
		return qstr::convertStringBlockToLines($stringBlock);
	}

	/**
	 * Gets the contents of a text file (one long string with appropriate newline characters).
	 *
	 * @param string $absolutePathAndFileName
	 * @return string block
	 */
	public static function getFileAsStringBlockWithAbsolutePathAndFileName($absolutePathAndFileName) {
		return file_get_contents($absolutePathAndFileName);
	}

	/**
	 * Takes e.g. "C:\Documents and Settings\Default\My Documents\webs\dp\systemBaseItems\items.php" and returns "items.php".
	 *
	 * @param string $pathAndFileName
	 * @return string fileName
	 */
	public static function getFileNameFromAbsolutePathAndFileName($absolutePathAndFileName) {
		$r = '';
		$textAfterLastSlash = qstr::getTextAfterLastMarkerSoft($absolutePathAndFileName, '\\');
		$r .= $textAfterLastSlash;
		return $r;
	}

	/**
	 * Returns whether or not a file exists.
	 *
	 * @param string $pathAndFileName
	 * @return boolean
	 */
	public static function fileExists($absolutePathAndFileName) {
		return file_exists($absolutePathAndFileName);
	}

	/**
	 *
	 * Returns the absolute path and file name that actually exists.
	 * @param $absolutePathAndFileName1
	 * @param $absolutePathAndFileName2
	 */
	public static function getAbsolutePathAndFileNameThatExists($absolutePathAndFileName1, $absolutePathAndFileName2) {
		if(qfil::fileExists($absolutePathAndFileName1)) {
			return $absolutePathAndFileName1;
		} else if(qfil::fileExists($absolutePathAndFileName2)) {
			return $absolutePathAndFileName2;
		} else {
			return '';
		}
	}

	/**
	 * Recursive function which returns an array of files.
	 *
	 * @param string $location
	 * @param string $fileregex
	 * @return array
	 */
	public static function getRelativePathAndFileNamesInDirectoryRecursive($relativeDirectory) {
		$relativePathAndFileNames = array();
		qfil::forceRelativeDirectoryToExist($relativeDirectory);
		$all = opendir($relativeDirectory);
		while ($file = readdir($all)) {
			if (is_dir($relativeDirectory . '/' . $file) and $file <> ".." and $file <> ".") {
			 $subdir_matches = qfil::getRelativePathAndFileNamesInDirectoryRecursive($relativeDirectory . '/' . $file, $fileregex);
			 $relativePathAndFileNames = array_merge($relativePathAndFileNames, $subdir_matches);
			 unset($file);
			}
			elseif (!is_dir($relativeDirectory . '/' . $file)) {
				array_push($relativePathAndFileNames, $relativeDirectory . '/' . $file);
			}
		}
		closedir($all);
		unset($all);
		return $relativePathAndFileNames;
	}

	public static function getRelativePathAndFileNamesInDirectoryRecursiveWithExtension($relativeDirectory, $extension = '') {
		$allRelativePathAndFileNames = qfil::getRelativePathAndFileNamesInDirectoryRecursive($relativeDirectory);
		$relativePathAndFileNames = array();
		if(!qstr::isEmpty($extension)) {
			if(count($allRelativePathAndFileNames) > 0) {
				foreach($allRelativePathAndFileNames as $relativePathAndFileName) {
					if(qstr::endsWith($relativePathAndFileName, '.' . $extension)) {
						$relativePathAndFileNames[] = $relativePathAndFileName;
					}
				}
			}
		}
		return $relativePathAndFileNames;
	}


	/**
	 * Forces a directory to exist.
	 *
	 * @param string $directory
	 */
	public static function forceRelativeDirectoryToExist($directory) {
		qfil::createRelativeDirectory($directory);
	}

	/**
	 * Takes a path such as "out/letter1/mail2343.txt" and creates the folders "out" and "out/letter1" if they do not exist.
	 *
	 * @param string $pathAndFileName
	 */
	public static function createRelativeDirectory($relativeDirectory) {
		$parts = explode("/",$relativeDirectory);
		$numberOfParts = count($parts);
		$collectiveDirectoryString = "";
		for($x = 0; $x<= $numberOfParts - 1; $x++) {
			$part = $parts[$x];
			$collectiveDirectoryString .= $part . "/";
			if(!qfil::relativeDirectoryExists($collectiveDirectoryString)) {
				mkdir($collectiveDirectoryString, 0777);
			}
		}
	}


	/**
	 * Returns whether or not a directory exists.
	 *
	 * @param string $pathAndFileName
	 * @return boolean
	 */
	public static function relativeDirectoryExists($relativeDirectory) {
		//what works for file works for directory
		return qfil::fileExists($relativeDirectory);
	}
	
	public static function getFileAsLines($relativePathAndFileName) {
		return array_map('rtrim',file($relativePathAndFileName));
	}	
}

?>