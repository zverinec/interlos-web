<?php
class InfoListComponent extends BaseComponent
{
	private $infoPage;

	public function setInfoPageUrl($url) {
		$this->infoPage = $url;
	}

	public function beforeRender() {
		parent::beforeRender();
		$this->template->url = $this->infoPage;
	}

}
