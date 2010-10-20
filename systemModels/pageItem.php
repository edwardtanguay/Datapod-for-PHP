<?php
class PageItem extends DatapodItem {

	protected $idCode;
	public function getIdCode() { return $this->idCode; }
	public function setIdCode($value) { $this->idCode = $value; }

	protected $menu;
	public function getMenu() { return $this->menu; }
	public function setMenu($value) { $this->menu = $value; }

	protected $title;
	public function getTitle() { return $this->title; }
	public function setTitle($value) { $this->title = $value; }

	protected $description;
	public function getDescription() { return $this->description; }
	public function setDescription($value) { $this->description = $value; }

	protected $kind;
	public function getKind() { return $this->kind; }
	public function setKind($value) { $this->kind = $value; }

	protected $accessGroups;
	public function getAccessGroups() { return $this->accessGroups; }
	public function setAccessGroups($value) { $this->accessGroups = $value; }

	protected $displayOrder;
	public function getDisplayOrder() { return $this->displayOrder; }
	public function setDisplayOrder($value) { $this->displayOrder = $value; }

	protected $extras;
	public function getExtras() { return $this->extras; }
	public function setExtras($value) { $this->extras = $value; }

}
?>
