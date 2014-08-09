<?php
namespace TYPO3\Beautyofcode\Service;

/***************************************************************
 * Copyright notice
 *
 * (c) 2014 Thomas Juhnke <typo3@van-tomas.de>
 *
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
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

use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\Beautyofcode\Domain\Model\ContentElement;

/**
 * This service allows the storage of brushes into the system registry
 *
 * @package \TYPO3\Beautyofcode\Service
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class BrushRegistryService implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 *
	 * @var array
	 */
	protected $brushes = array();

	/**
	 * registerBrush
	 *
	 * @param ContentElement $contentElement
	 * @return void
	 */
	public function registerBrush(ContentElement $contentElement) {
		$brush = $contentElement->getFlexformObject()->getCLang();

		if (FALSE === in_array($brush, $this->brushes)) {
			$this->brushes[] = $brush;
		}
	}

	/**
	 * getBrushes
	 *
	 * @return array
	 */
	public function getBrushes() {
		return $this->brushes;
	}
}
?>