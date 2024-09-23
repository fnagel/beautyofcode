<?php

namespace FelixNagel\Beautyofcode\Form\Element;

use TYPO3\CMS\Backend\Form\Element\CodeEditorElement;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Code editor FormEngine widget.
 *
 * See https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/CodeEditor/Index.html
 *
 * @author Felix Nagel <info@felixnagel.com>
 */
class T3editorElement extends CodeEditorElement
{
    /**
     * @var string
     */
    public const T3EDITOR_MODE_DEFAULT = 'xml';

    /**
     * Map code editor modes on our brush aliases.
     */
    protected array $brushModeMap = [
        'markup' => 'xml',
        'css' => 'css',
        'javascript' => 'javascript',
        'php' => 'php',
        'typoscript' => 'typoscript',
        'sql' => 'sql',
    ];

    protected array $allowedModes = [
        'xml',
        'css',
        'javascript',
        'php',
        'typoscript',
        'sql',
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
     * Dynamic update of the code editor format.
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
     */
    protected function isBeautyOfCodeElement(): bool
    {
        return $this->data['tableName'] === 'tt_content' &&
            current($this->data['databaseRow']['CType']) === 'beautyofcode_contentrenderer';
    }
}
