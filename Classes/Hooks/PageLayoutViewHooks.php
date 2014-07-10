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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook class for PageLayoutView hook `list_type_Info`
 *
 * @author Felix Nagel <info@felixnagel.com>
 * @package \TYPO3\Beautyofcode\Hooks
 */
class PageLayoutViewHooks {

	/**
	 * Reference to translation catalogue
	 *
	 * @var string
	 */
	const TRANSLATION_CATALOGUE = 'LLL:EXT:beautyofcode/Resources/Private/Language/locallang_db.xml';

	/**
	 *
	 * @var integer
	 */
	const MAX_TEXTAREA_LINES = 15;

	/**
	 *
	 * @var integer
	 */
	const MAX_TEXTAREA_HEIGHT = 150;

	/**
	 *
	 * @var integer
	 */
	const SMALL_TEXTAREA_FACTOR = 20;

	/**
	 *
	 * @var integer
	 */
	const SMALL_TEXTAREA_ADDITION = 5;

	/**
	 *
	 * @var array
	 */
	protected $flexformData = array();

	/**
	 *
	 * @var string
	 */
	protected $textareaHeight = '';

	/**
	 * Returns information about this extension's pi1 plugin
	 *
	 * @param array $params Parameters to the hook
	 * @param object &$pObj A reference to calling object
	 * @return string Information about pi1 plugin
	 */
	public function getExtensionSummary($params, &$pObj) {
		$result = '';

		if ($params['row']['list_type'] == 'beautyofcode_contentrenderer') {
			$this->flexformData = GeneralUtility::xml2array($params['row']['pi_flexform']);

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
	 * builds a header by reading the label field
	 *
	 * Falls back to "no label" from l10n catalogue
	 *
	 * @return string
	 */
	protected function buildLabelHeader() {
		$header = sprintf("<em>%s</em>",
			$GLOBALS['LANG']->sL(
				self::TRANSLATION_CATALOGUE . ':cms_layout.no_label'
			)
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
			$GLOBALS['LANG']->sL(self::TRANSLATION_CATALOGUE . ':code'),
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
			$GLOBALS['LANG']->sL(self::TRANSLATION_CATALOGUE . ':cms_layout.no_code')
		);

		if (strlen($code) > 0) {
			$this->calculateTextareaHeight($code);

			$preview = sprintf(
				'<textarea id="ta_hidden%s" style="display: none;" readonly="readonly">%s</textarea>',
				$uid,
				GeneralUtility::formatForTextarea($code)
			);
			$preview .= sprintf(
				'<textarea id="ta%s" style="height: %s; width: 98%%; padding: 1%%; margin: 0;" wrap="off" readonly="readonly"></textarea>',
				$uid,
				$this->textareaHeight
			);
			$preview .= sprintf('
				<script type="text/javascript">
					var
						ta_hidden%s = document.getElementById("ta_hidden%s"),
						ta%s = document.getElementById("ta%s");

					window.setTimeout(function() {
						ta%s.value = ta_hidden%s.value;
					}, 500);
				</script>',
				$uid,
				$uid,
				$uid,
				$uid,
				$uid,
				$uid
			);
		}

		return $preview;
	}

	/**
	 * Calculates the height for the textarea field
	 *
	 * Newlines in $content be counted and then used to calculate the textarea
	 * height.
	 *
	 * @param string $content
	 * @param string $unit
	 * @return void
	 */
	protected function calculateTextareaHeight($content, $unit = 'px') {
		$lines = preg_split("/(\n)/", $content);
		$proxyLines = sizeof($lines);

		if ($proxyLines > self::MAX_TEXTAREA_LINES) {
			$textareaHeight = self::MAX_TEXTAREA_HEIGHT;
		} else {
			$textareaHeight = $proxyLines * self::SMALL_TEXTAREA_FACTOR;
			$textareaHeight += self::SMALL_TEXTAREA_ADDITION;
		}

		$this->textareaHeight = sprintf('%s%s', $textareaHeight, $unit);
	}
}
?>