<?php
namespace TYPO3\Beautyofcode\Domain\Model;

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

/**
 * This maps the beautyofcode plugin content element to a proper DO
 *
 * @package \TYPO3\Beautyofcode\Domain\Model
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ContentElement extends \TYPO3\CMS\Extbase\DomainObject\AbstractValueObject {

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Service\FlexformDataMapperService
	 */
	protected $flexformDataMapperService;

	/**
	 *
	 * @var string
	 */
	protected $header;

	/**
	 *
	 * @var string
	 */
	protected $flexform;

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Domain\Model\Flexform
	 */
	protected $flexformObject;

	/**
	 *
	 * @param \TYPO3\Beautyofcode\Service\FlexformDataMapperService $flexformDataMapperService
	 * @return void
	 */
	public function injectFlexformDataMapperService(\TYPO3\Beautyofcode\Service\FlexformDataMapperService $flexformDataMapperService) {
		$this->flexformDataMapperService = $flexformDataMapperService;
	}

	/**
	 *
	 * @param string $header
	 */
	public function setHeader($header) {
		$this->header = $header;
	}

	/**
	 *
	 * @return string
	 */
	public function getHeader() {
		return $this->header;
	}

	/**
	 *
	 * @param string $flexform
	 */
	public function setFlexform($flexform) {
		$this->flexform = $flexform;
	}

	/**
	 *
	 * @return string
	 */
	public function getFlexform() {
		return $this->flexform;
	}

	/**
	 *
	 * @return \TYPO3\Beautyofcode\Domain\Model\Flexform
	 */
	public function getFlexformObject() {
		if (NULL === $this->flexformObject) {
			$this->flexformObject = $this->flexformDataMapperService->map($this);
		}

		return $this->flexformObject;
	}
}
?>