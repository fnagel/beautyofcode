<?php

namespace TYPO3\Beautyofcode\Highlighter\Configuration;

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
    protected $failSafeBrushAliasMap = array(
        'SyntaxHighlighter' => array(
            'actionscript3' => 'actionscript',
            'coldfusion' => 'markup',
            'delphi' => 'plain',
            'javafx' => 'java',
            'perl' => 'c',
            'vb' => 'plain',
            'xml' => 'markup',
        ),
    );

    /**
     * A CSS class/label map for the select box.
     *
     * Key is the brush string from TS Setup; Value is an array with the CSS
     * class in key 0 and the label for the select box in key 1
     *
     * @var array
     */
    protected $brushIdentifierAliasLabelMap = array(
        'applescript' => array('applescript', 'AppleScript'),
        'actionscript' => array('actionscript', 'Actionscript 3'),
        'bash' => array('bash', 'Bash / Shell'),
        'c' => array('c', 'C / C++'),
        'clike' => array('clike', 'C-Like'),
        'coffeescript' => array('coffeescript', 'Coffeescript'),
        'cpp' => array('cpp', 'C / C++'),
        'csharp' => array('csharp', 'C#'),
        'css' => array('css', 'CSS'),
        'diff' => array('diff', 'Diff / Patch'),
        'erlang' => array('erlang', 'Erlang'),
        'gherkin' => array('gherkin', 'Gherkin'),
        'git' => array('git', 'Git'),
        'go' => array('go', 'Go'),
        'groovy' => array('groovy', 'Groovy'),
        'http' => array('http', 'HTTP'),
        'java' => array('java', 'Java'),
        'javascript' => array('javascript', 'JavaScript'),
        'less' => array('less', 'LESS'),
        'markdown' => array('markdown', 'Markdown'),
        'markup' => array('markup', 'XML / XSLT / XHTML / HTML'),
        'php' => array('php', 'PHP'),
        'powershell' => array('powershell', 'Power-Shell'),
        'python' => array('python', 'Python'),
        'ruby' => array('ruby', 'Ruby'),
        'sass' => array('sass', 'Sass'),
        'scala' => array('scala', 'Scala'),
        'scss' => array('scss', 'SCSS'),
        'sql' => array('sql', 'SQL'),
        'twig' => array('twig', 'Twig'),
        'typoscript' => array('typoscript', 'TypoScript'),
        'yaml' => array('yaml', 'Yaml'),
    );

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
        return array();
    }

    /**
     * GetClassAttributeString.
     *
     * @param \TYPO3\Beautyofcode\Domain\Model\Flexform $flexform Flexform
     *
     * @return string
     */
    public function getClassAttributeString(\TYPO3\Beautyofcode\Domain\Model\Flexform $flexform)
    {
        $configurationItems = array();
        $classAttributeConfigurationStack = array(
            'data-line' => \TYPO3\CMS\Core\Utility\GeneralUtility::expandList($flexform->getCHighlight()),
        );

        foreach ($classAttributeConfigurationStack as $configurationKey => $configurationValue) {
            if (true === in_array($configurationValue, array('', 'auto'))) {
                continue;
            }

            $configurationItems[] = sprintf('%s="%s"', $configurationKey, $configurationValue);
        }

        return ' '.implode(' ', $configurationItems);
    }
}
