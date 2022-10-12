<?php

namespace FelixNagel\Beautyofcode\Controller;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use FelixNagel\Beautyofcode\Domain\Repository\FlexformRepository;
use TYPO3\CMS\Core\LinkHandling\LinkService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * The frontend plugin controller for the syntaxhighlighter.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class ContentController extends ActionController
{
    /**
     * FlexformRepository.
     *
     * @var FlexformRepository
     */
    protected $flexformRepository;

    /**
     * InjectFlexformRepository.
     *
     * @param FlexformRepository $flexformRepository FlexformRepository
     */
    public function injectFlexformRepository(
        FlexformRepository $flexformRepository
    ) {
        $this->flexformRepository = $flexformRepository;
    }

    /**
     * Render.
     */
    public function renderAction()
    {
        // @extensionScannerIgnoreLine
        $contentObject = $this->configurationManager->getContentObject();
        $contentElement = $contentObject->data;
        $flexform = $this->flexformRepository->reconstituteByContentObject($contentObject);
        $flexform->setTyposcriptDefaults($this->settings['defaults']);

        if (empty(trim($contentElement['bodytext'])) && !empty($flexform->getCFile())) {
            $content = '';

            $linkService = GeneralUtility::makeInstance(LinkService::class);
            $data = $linkService->resolveByStringRepresentation($flexform->getCFile());

            if ($data['type'] === 'url') {
                $content = file_get_contents($data['url']);

                if ($content === false) {
                    $content = '';
                }
            } elseif ($data['type'] === 'file') {
                /** @var File $fileObject */
                $fileObject = $data['file'];

                if ($fileObject !== null) {
                    $content = $fileObject->getContents();
                }
            }

            $contentElement['bodytext'] = $content;
        }

        $this->view->assign('ce', $contentElement);
        $this->view->assign('flexform', $flexform);
    }
}
