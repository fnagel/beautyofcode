<?php
namespace TYPO3\Beautyofcode\Controller;

/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Thomas Juhnke <typo3@van-tomas.de>
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

use TYPO3\Beautyofcode\Service\HighlighterService;

/**
 * The frontend plugin controller for the syntaxhighlighter
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class ContentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Domain\Repository\ContentElementRepository
	 */
	protected $contentElementRepository;

	/**
	 *
	 * @var \TYPO3\Beautyofcode\Service\HighlighterService
	 */
	protected $highlighterService;

	/**
	 * injectContentElementRepository
	 *
	 * @param \TYPO3\Beautyofcode\Domain\Repository\ContentElementRepository $contentElementRepository
	 * @return void
	 */
	public function injectContentElementRepository(
		\TYPO3\Beautyofcode\Domain\Repository\ContentElementRepository $contentElementRepository
	) {
		$this->contentElementRepository = $contentElementRepository;
	}

	/**
	 * injectHighlighterService
	 *
	 * @param HighlighterService $highlighterService
	 * @return void
	 */
	public function injectHighlighterService(
		HighlighterService $highlighterService
	) {
		$this->highlighterService = $highlighterService;
	}

	/**
	 * renderAction
	 *
	 * @return void
	 */
	public function renderAction() {
		$contentElementRaw = $this->configurationManager->getContentObject();
		/* @var $contentElement \TYPO3\Beautyofcode\Domain\Model\ContentElement */
		$contentElement = $this->contentElementRepository
			->findByUid(
				$contentElementRaw->data['uid']
			);

		$this->highlighterService->registerBrushAlias($contentElement);
		$this->highlighterService->generateClassAttributeString($contentElement);

		$this->view->assign('contentElement', $contentElement);
		$this->view->assign('brushes', $this->highlighterService->getRegisteredBrushes());

		/* @deprecated Will be removed in ext:beautyofcode 3.0 */
		$this->view->assign('flexform', $contentElement->getFlexformObject());
	}
}