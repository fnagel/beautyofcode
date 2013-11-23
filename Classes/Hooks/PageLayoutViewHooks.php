<?php
namespace TYPO3\Beautyofcode\Hooks;

/***************************************************************
 * Copyright notice
 *
 * (c) 2010-2013 Felix Nagel (info@felixnagel.com)
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Hook class for PageLayoutView hook `list_type_Info`
 *
 * @author Felix Nagel <info@felixnagel.com>
 * @package	\TYPO3\Beautyofcode\Hooks
 */
class PageLayoutViewHooks {

	/**
	 *
	 * @var array
	 */
	protected $flexformData = array();

	/**
	 * Returns information about this extension's pi1 plugin
	 *
	 * @param array $params	Parameters to the hook
	 * @param object $pObj A reference to calling object
	 * @return string Information about pi1 plugin
	 */
	public function getExtensionSummary($params, &$pObj) {
		$result = '';

		if ($params['row']['list_type'] == 'beautyofcode_contentrenderer') {
			$this->flexformData = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($params['row']['pi_flexform']);

			$uid = $params['row']['uid'];

			if (is_array($this->flexformData)) {
				$result = $this->buildLabelHeader();

				$result .= $this->buildCodeLanguageHeader();

				$result .= $this->buildCodePreview($uid);
			}
		}

		return $result;
	}

	/**
	 * builds a header by reading the label field, fall back to "no label" from l10n catalogue
	 *
	 * @return string
	 */
	protected function buildLabelHeader() {
		$header = sprintf("<em>%s</em>",
			$GLOBALS['LANG']->sL('LLL:EXT:beautyofcode/Resources/Private/Language/locallang_db.xml:cms_layout.no_label')
		);

		$label = $this->flexformData['data']['sDEF']['lDEF']['cLabel']['vDEF'];

		if (strlen(trim($label)) > 0) {
			$header = "<strong>" . htmlspecialchars($label) . "</strong>";
		}

		return $header;
	}

	/**
	 * builds the code language header
	 *
	 * @return string
	 */
	protected function buildCodeLanguageHeader() {
		return sprintf("<br /><br /><strong>%s</strong> (%s)<br />",
			$GLOBALS['LANG']->sL('LLL:EXT:beautyofcode/Resources/Private/Language/locallang_db.xml:code'),
			htmlspecialchars($this->flexformData['data']['sDEF']['lDEF']['cLang']['vDEF'])
		);
	}

	/**
	 * builds a textarea code preview field
	 *
	 * @param integer $uid The uid of the content record
	 * @return string
	 */
	protected function buildCodePreview($uid) {
		$code = $this->flexformData['data']['sDEF']['lDEF']['cCode']['vDEF'];

		$preview = sprintf("<em>%s</em>",
			$GLOBALS['LANG']->sL('LLL:EXT:beautyofcode/Resources/Private/Language/locallang_db.xml:cms_layout.no_code')
		);

		if (strlen($code) > 0) {
			// calculate height
			$proxyLines = sizeof(preg_split("/(\n)/", $code));
			$taHeight = ($proxyLines >= 15) ? "150px" : ($proxyLines * 20 + 5) . "px";
			// make textarea with code
			$preview = '<textarea id="ta_hidden' . $uid . '" style="display: none;" readonly="readonly">' . \TYPO3\CMS\Core\Utility\GeneralUtility::formatForTextarea($code) . "</textarea>";
			$preview .= '<textarea id="ta' . $uid . '" style="height: ' . $taHeight . '; width: 100%; cursor: pointer;" wrap="off" readonly="readonly"></textarea>';
			$preview .= '<script type="text/javascript">' .
						'var ta_hidden' . $uid . ' = document.getElementById("ta_hidden' . $uid . '");' .
						'var ta' . $uid . ' = document.getElementById("ta' . $uid . '");' .
						'window.setTimeout(function() { ta' . $uid . '.value = ta_hidden' . $uid . '.value; }, 500);' .
						'</script>';
		}

		return $preview;
	}
}
?>