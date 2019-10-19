<?php

namespace FelixNagel\Beautyofcode\Form\Element;

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
        $mode = $this->determineMode();

        if ($mode !== null) {
            $this->data['parameterArray']['fieldConf']['config']['format'] = $mode;
        }

        return parent::render();
    }

    /**
     * Dynamic update of the t3editor format.
     *
     * @return string|null
     */
    protected function determineMode()
    {
        // Fallback
        $mode = $this->data['parameterArray']['fieldConf']['config']['format'] ?: self::T3EDITOR_MODE_DEFAULT;

        if (!$this->isBeautyOfCodeElement()) {
            return $mode;
        }

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
