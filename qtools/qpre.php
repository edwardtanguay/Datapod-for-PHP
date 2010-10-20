<?php

/**
 * Presentation helper functions.
 *
 */
class qpre {

	/**
	 * Display (such as code) in a scrollable code box, also useful for any text that has to be copy and pasted.
	 *
	 * @param string $content
	 * @return string
	 */
	public static function displayInScrollBox($content, $extras = '') {
		$r = '';
		$width = qstr::GetExtrasValue('width', $extras, '900px');
		$height = qstr::GetExtrasValue('height', $extras, '200px');
		$r .= '<textarea style="width: ' . $width . '; height: ' . $height . '" wrap="yes" class="scrollBox" readonly="true">';
		$r .= qstr::htmlEncode($content);
		$r .= '</textarea>';
		return $r;
	}

	/**
	 * Display HTML in a scrollable code box, also useful for any text that has to be copy and pasted.
	 *
	 * @param string $content
	 * @return string
	 */
	public static function displayHtmlInScrollBox($content, $extras = '') {
		$r = '';
		$width = qstr::GetExtrasValue('width', $extras, '900px');
		$height = qstr::GetExtrasValue('height', $extras, '200px');
		$r .= '<div style="width: 600px; height: 100px; margin: 0 0 5px 0; padding:5px; overflow:auto; font-size:10pt; background-color: #ddd" readonly="true">';
		$r .= $content;
		$r .= '</div>';
		return $r;		
	}

	public static function getHtmlLine($line) {
		return $line . qstr::NEW_LINE();
	}

	public static function getTextWithNewline($line) {
		return $line . qstr::NEW_LINE();
	}

	/**
	 * Returns a hidden variable.
	 *
	 * @param string $fieldIdCode
	 * @param string $value
	 * @return string HTML
	 */
	public static function getHiddenVariable($fieldIdCode, $value) {
		return '<input type="hidden" name="' . $fieldIdCode . '" value="' . $value . '"/>';
	}

	public static function getSeparator() {
		return '<div class="separator"></div>';
	}
	
	/**
	 * $rank = 0 to 5, as in amazon stars, 0=bad, 5=good
	 */
	public static function getStarsHtml($rank) {
		$r = '';
		for($star = 1; $star <= 5; $star++) {
			if($rank > $star - 1 && $rank < $star && !qmat::isWholeNumber($rank)) {
				$r .= '<img class="star" src="images/system/starHalfFull.png"/>';
			} else if($rank >= $star) {
				$r .= '<img class="star" src="images/system/starFull.png"/>';
			} else {
				$r .= '<img class="star" src="images/system/starEmpty.png"/>';
			}
		}
		return $r;
	}

}