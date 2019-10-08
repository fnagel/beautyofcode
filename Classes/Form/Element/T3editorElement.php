<?php

namespace TYPO3\Beautyofcode\Form\Element;

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

use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\T3editor\Mode;
use TYPO3\CMS\T3editor\Registry\ModeRegistry;

/**
 * t3editor FormEngine widget.
 *
 * @author Felix Nagel <info@felixnagel.com>
 */
class T3editorElement extends \TYPO3\CMS\T3editor\Form\Element\T3editorElement
{
    const T3EDITOR_MODE_DEFAULT = 'xml';

    /**
     * Map ext:t3editor modes on beautyofcode brush aliases.
     *
     * @var array
     */
    protected $brushModeMap = [
        'markup' => 'xml',
        'css' => 'css',
        'javascript' => 'javascript',
        'php' => 'php',
        'typoscript' => 'typoscript',
    ];

    protected $allowedModes = [
        'xml',
        'css',
        'javascript',
        'php',
        'typoscript'
    ];

    /**
     * @inheritDoc
     */
    public function render(): array
    {
        $mode = $this->setModeDynamic($this->data['parameterArray']['fieldConf']['config']['format']);
        $this->data['parameterArray']['fieldConf']['config']['format'] = $mode;

        return parent::render();
    }

    /**
     * Sets the type of code to edit, use one of the predefined constants.
     *
     * @todo Remove as no longer existing in extended class.
     * @deprecated
     *
     * @param string $mode Expects one of the predefined constants
     *
     * @return void
     */
    public function setMode($mode)
    {
        $this->mode = $this->setModeDynamic($mode);
    }

    /**
     * Dynamic update of the t3editor format.
     *
     * @param string $mode Expects one of the predefined constants
     *
     * @return string
     */
    protected function setModeDynamic($mode)
    {
        if (!$this->isBeautyOfCodeElement()) {
            return $mode;
        }

        // Fallback
        $mode = self::T3EDITOR_MODE_DEFAULT;

        // Get current flexform language value
        $flexformLanguageKey = current(
            $this->data['databaseRow']['pi_flexform']['data']['sDEF']['lDEF']['cLang']['vDEF']
        );

        if (empty($flexformLanguageKey)) {
            return $mode;
        }

        if (array_search($flexformLanguageKey, $this->allowedModes) !== false) {
            return $flexformLanguageKey;
        }

        if (array_key_exists($flexformLanguageKey, $this->brushModeMap)) {
            return $this->brushModeMap[$flexformLanguageKey];
        }

        return $mode;
    }

    /**
     * Flags if the current element is a beautyofcode plugin.
     *
     * @return bool
     */
    protected function isBeautyOfCodeElement()
    {
        return
            $this->data['tableName'] === 'tt_content' &&
            current($this->data['databaseRow']['CType']) === 'beautyofcode_contentrenderer'
        ;
    }
}
