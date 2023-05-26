<?php

namespace FelixNagel\Beautyofcode\Form\Element;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * t3editor FormEngine widget.
 *
 * @author Felix Nagel <info@felixnagel.com>
 */
class T3editorElement extends \TYPO3\CMS\T3editor\Form\Element\T3editorElement
{
    /**
     * @var string
     */
    public const T3EDITOR_MODE_DEFAULT = 'xml';

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
     */
    protected function determineMode(): ?string
    {
        // Fallback
        $mode = $this->data['parameterArray']['fieldConf']['config']['format'] ?? self::T3EDITOR_MODE_DEFAULT;

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

        if (in_array($flexformLanguageKey, $this->allowedModes)) {
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
