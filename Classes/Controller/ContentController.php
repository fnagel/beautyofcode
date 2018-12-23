<?php

namespace TYPO3\Beautyofcode\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * The frontend plugin controller for the syntaxhighlighter.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class ContentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * FlexformRepository.
     *
     * @var \TYPO3\Beautyofcode\Domain\Repository\FlexformRepository
     */
    protected $flexformRepository;

    /**
     * InjectFlexformRepository.
     *
     * @param \TYPO3\Beautyofcode\Domain\Repository\FlexformRepository $flexformRepository FlexformRepository
     */
    public function injectFlexformRepository(
        \TYPO3\Beautyofcode\Domain\Repository\FlexformRepository $flexformRepository
    ) {
        $this->flexformRepository = $flexformRepository;
    }

    /**
     * Render.
     */
    public function renderAction()
    {
        $contentElement = $this->configurationManager->getContentObject()->data;
        $flexform = $this
            ->flexformRepository
            ->reconstituteByContentObject(
                $this->configurationManager->getContentObject()
            );
        $flexform->setTyposcriptDefaults($this->settings['defaults']);

        $this->view->assign('ce', $contentElement);
        $this->view->assign('flexform', $flexform);
    }
}
