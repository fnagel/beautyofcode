<?php

namespace FelixNagel\Beautyofcode\Highlighter\Configuration;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FelixNagel\Beautyofcode\Domain\Model\Flexform;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Prism.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class Prism extends AbstractConfiguration
{
    /**
     * Failsafe brush alias map.
     *
     * Fallback from one highlighter engine to another.
     * Key is SH CSS class and value is Prism CSS class.
     *
     * @var array
     */
    protected $failSafeBrushAliasMap = [
        'SyntaxHighlighter' => [
            'actionscript3' => 'actionscript',
            'coldfusion' => 'markup',
            'delphi' => 'plain',
            'javafx' => 'java',
            'perl' => 'c',
            'vb' => 'plain',
            'xml' => 'markup',
        ],
    ];

    /**
     * A CSS class/label map for the select box.
     *
     * Key is the brush string from TS Setup; Value is an array with the CSS
     * class in key 0 and the label for the select box in key 1
     *
     * @var array
     */
    protected $brushIdentifierAliasLabelMap = [
        'actionscript' => ['actionscript', 'Actionscript 3'],
        'apacheconf' => ['apacheconf', 'Apache Config'],
        'applescript' => ['applescript', 'AppleScript'],
        'bash' => ['bash', 'Bash / Shell'],
        'c' => ['c', 'C / C++'],
        'clike' => ['clike', 'C-Like'],
        'coffeescript' => ['coffeescript', 'Coffeescript'],
        'cpp' => ['cpp', 'C / C++'],
        'csharp' => ['csharp', 'C#'],
        'css' => ['css', 'CSS'],
        'diff' => ['diff', 'Diff / Patch'],
        'erlang' => ['erlang', 'Erlang'],
        'gherkin' => ['gherkin', 'Gherkin'],
        'git' => ['git', 'Git'],
        'go' => ['go', 'Go'],
        'groovy' => ['groovy', 'Groovy'],
        'http' => ['http', 'HTTP'],
        'java' => ['java', 'Java'],
        'javascript' => ['javascript', 'JavaScript'],
        'less' => ['less', 'LESS'],
        'markdown' => ['markdown', 'Markdown'],
        'markup' => ['markup', 'XML / XSLT / XHTML / HTML'],
        'php' => ['php', 'PHP'],
        'powershell' => ['powershell', 'Power-Shell'],
        'python' => ['python', 'Python'],
        'ruby' => ['ruby', 'Ruby'],
        'sass' => ['sass', 'Sass'],
        'scala' => ['scala', 'Scala'],
        'scss' => ['scss', 'SCSS'],
        'sql' => ['sql', 'SQL'],
        'twig' => ['twig', 'Twig'],
        'typoscript' => ['typoscript', 'TypoScript'],
        'yaml' => ['yaml', 'YAML'],
    ];

    /**
     * GetAutoloaderBrushMap.
     *
     * The Prism highlighter doesn't have any autoloader, but as this method
     * needs to be implemented, it returns an empty array.
     *
     * @return array
     */
    public function getAutoloaderBrushMap()
    {
        return [];
    }

    /**
     * GetClassAttributeString.
     *
     * @param Flexform $flexform Flexform
     */
    public function getClassAttributeString(Flexform $flexform): string
    {
        $configurationItems = [];
        $classAttributeConfigurationStack = [
            'data-line' => GeneralUtility::expandList($flexform->getCHighlight()),
        ];

        foreach ($classAttributeConfigurationStack as $configurationKey => $configurationValue) {
            if (in_array($configurationValue, ['', 'auto'])) {
                continue;
            }

            $configurationItems[] = sprintf('%s="%s"', $configurationKey, $configurationValue);
        }

        return ' '.implode(' ', $configurationItems);
    }
}
