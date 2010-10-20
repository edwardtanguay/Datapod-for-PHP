<?php
//class:
class ObjectSorter {
	private $objectCollection; //the collection of objects, e.g. custom items
	private $lengthOfRandomString = 10; //the length of the string at the end of the key to make it unique, e.g. "2007-05-01-4914579064"

	//method: take '2007-21-31' and make it '2007-21-31-2384729384734' so that it is unique so we can use it as an array key but still sort it
	private function makeUniqueSortKey($sortKey) {
		$r = '';
		$lengthOfRandomString = $this->lengthOfRandomString;
		$randomString = qmat::getRandomDigits($lengthOfRandomString);
		$r = $sortKey . '-' . $randomString;
		return $r;
	}

	//method: return '2007-21-31-2384729384734' to '2007-21-31'
	private function convertBackToSortKey($uniqueSortKey) {
		$r = '';
		$lengthOfRandomString = $this->lengthOfRandomString;
		$numberOfCharactersToChop = $lengthOfRandomString + 1;
		$r = qstr::chopPieceRight($uniqueSortKey, $numberOfCharactersToChop);
		return $r;
	}

	public function add($sortKey, $object) {
		$r = '';
		$uniqueSortKey = $this->makeUniqueSortKey($sortKey);
		$this->objectCollection[$uniqueSortKey] = $object;
		return $r;
	}

	public function sort() {
		ksort($this->objectCollection);
	}

	public function reverseSort() {
		if(count($this->objectCollection) > 0) {
			krsort($this->objectCollection);
		}
	}

	public function testShow() {
		$r = '';
		$objectCollection = $this->objectCollection;
		if(count($objectCollection) > 0) {
			foreach($objectCollection as $uniqueSortKey => $object) {
				$sortKey = $this->convertBackToSortKey($uniqueSortKey);
				$r .= $sortKey . ': ' . $object->getWhenCreated() . ' (' . $object->getItemTypeIdCode() . ')' . qstr::BR();
			}
		}
		return $r;
	}

	public function getTopObjects($howMany = 99999) {
		$ra = array();
		$objectCollection = $this->objectCollection;
		if(count($objectCollection) > 0) {
			$count = 1;
			foreach($objectCollection as $object) {
				if($count <= $howMany) {
					$ra[] = $object;
				} else {
					break;
				}
				$count++;
			}
		}
		return $ra;
	}
}
?>