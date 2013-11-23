<?php
namespace TYPO3\Beautyofcode\Domain\Model;

/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Thomas Juhnke <tommy@van-tomas.de>
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
 * A copy is found in the textfile GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Domain model object for the flexform configuration of a plugin instance
 *
 * @author Thomas Juhnke <tommy@van-tomas.de>
 */
class Flexform extends \TYPO3\CMS\Extbase\DomainObject\AbstractValueObject {

	/**
	 *
	 * @var string
	 */
	protected $cLabel;

	/**
	 *
	 * @var string
	 */
	protected $cLang;

	/**
	 *
	 * @var string
	 */
	protected $cCode;

	/**
	 *
	 * @var string
	 */
	protected $cHighlight;

	/**
	 *
	 * @var string
	 */
	protected $cCollapse;

	/**
	 *
	 * @var string
	 */
	protected $cGutter;

	/**
	 *
	 * @var string
	 */
	protected $cToolbar;

	/**
	 *
	 * @var string
	 */
	protected $languageFallback = 'plain';

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Service\VersionAssetServiceInterface
	 */
	protected $versionAssetService;

	public function setCLabel($cLabel) {
		$this->cLabel = $cLabel;
	}

	public function getCLabel() {
		return $this->cLabel;
	}

	public function setCLang($cLang) {
		$this->cLang = $cLang;
	}

	public function getCLang() {
		return $this->CLang;
	}

	public function setCCode($cCode) {
		$this->cCode = $cCode;
	}

	public function getCCode() {
		return $this->cCode;
	}

	public function setCHighlight($cHighlight) {
		$this->cHighlight = $cHighlight;
	}

	public function getCHighlight() {
		return $this->cHighlight;
	}

	public function setCCollapse($cCollapse) {
		$this->cCollapse = $cCollapse;
	}

	public function getCCollapse() {
		return $this->cCollapse;
	}

	public function setCGutter($cGutter) {
		$this->cGutter = $cGutter;
	}

	public function getCGutter() {
		return $this->cGutter;
	}

	public function setCToolbar($cToolbar) {
		$this->cToolbar = $cToolbar;
	}

	public function getCToolbar() {
		return $this->cToolbar;
	}

	public function getLanguage() {
		return $this->cLang ? $this->cLang : $this->languageFallback;
	}

	/**
	 *
	 * @param \TYPO3\Beautyofcode\Service\VersionAssetServiceInterface $versionAssetServiceInterface
	 */
	public function setVersionAssetService(\TYPO3\Beautyofcode\Service\VersionAssetServiceInterface $versionAssetService) {
		$this->versionAssetService = $versionAssetService;
	}

	/**
	 *
	 * @return string
	 */
	public function getClassAttributeConfiguration() {
		$this->versionAssetService
			->pushClassAttributeConfiguration('highlight', \TYPO3\CMS\Core\Utility\GeneralUtility::expandList($this->cHighlight));

		$this->versionAssetService
			->pushClassAttributeConfiguration('gutter', $this->cGutter);

		$this->versionAssetService
			->pushClassAttributeConfiguration('toolbar', $this->cToolbar);

		$this->versionAssetService
			->pushClassAttributeConfiguration('collapse', $this->cCollapse);

		return $this->versionAssetService->getClassAttributeConfiguration();
	}
}
?>