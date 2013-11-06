<?php
namespace FNagel\Beautyofcode\Controller;

class JqueryAssetController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	public function renderAction() {
		$this->view->assign('jQvar', $this->settings['jQueryNoConflict'] ? "jQuery" : "$");
		$this->view->assign('jQuerySelector', (strlen(trim($this->settings['jQuerySelector'])) > 0) ? trim($this->settings['jQuerySelector']) . ' ' : FALSE);
	}
}
?>