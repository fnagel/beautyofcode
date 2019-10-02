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

/**
 * t3editor FormEngine widget.
 *
 * @author Felix Nagel <info@felixnagel.com>
 */
class T3editorElement extends \TYPO3\CMS\T3editor\Form\Element\T3editorElement
{
    /**
     * Map ext:t3editor modes on boc brush aliases.
     *
     * @var array
     */
    protected $brushModeMap = [
        'markup' => self::MODE_XML,
        'css' => self::MODE_CSS,
        'javascript' => self::MODE_JAVASCRIPT,
        'php' => self::MODE_PHP,
        'typoscript' => self::MODE_TYPOSCRIPT,
    ];

    /**
     * Sets the type of code to edit, use one of the predefined constants.
     *
     * @param string $mode Expects one of the predefined constants
     *
     * @throws \InvalidArgumentException
     */
    public function setMode($mode)
    {
        $mode = $this->setModeDynamic($mode);

        parent::setMode($mode);
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

        $mode = self::MODE_MIXED;
        // Get current flexform language value
        $flexformLanguageKey = current($this->data['databaseRow']['pi_flexform']['data']['sDEF']['lDEF']['cLang']['vDEF']);

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
     * Flags if the current element is a boc plugin.
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
