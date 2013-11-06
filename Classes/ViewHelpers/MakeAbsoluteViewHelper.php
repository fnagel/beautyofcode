<?php
namespace FNagel\Beautyofcode\ViewHelpers;

class MakeAbsoluteViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 *
	 * @param string $url
	 */
	public function render($url) {
		$siteUrl = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL');

		return $siteUrl . $this->makeAbsolutePath(trim($url));
	}

	/**
	 * Function to solve path with FILE: and EXT:
	 *
	 * @param	string	path to directory
	 * @return	string
	 */
	protected function makeAbsolutePath($dir) {
		if (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($dir, 'EXT:'))	{
			list($extKey, $script) = explode('/', substr($dir, 4), 2);

			if ($extKey && \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded($extKey)) {
				$extPath=\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extKey);

				return substr($extPath, strlen(PATH_site)) . $script;
			}
		} elseif (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($dir, 'FILE:')) {
				return substr($dir, 5);
		} else {
			return $dir;
		}
	}
}
?>