<?php
class CssResource extends DatapodItem {

	protected $title;
	public function getTitle() { return $this->title; }
	public function setTitle($value) { $this->title = $value; }

	protected $url;
	public function getUrl() { return $this->url; }
	public function setUrl($value) { $this->url = $value; }

}
?>
