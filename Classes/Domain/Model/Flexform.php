<?php

namespace FelixNagel\Beautyofcode\Domain\Model;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use FelixNagel\Beautyofcode\Highlighter\ConfigurationInterface;
use TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;

/**
 * Domain model object for the flexform configuration of a plugin instance.
 *
 * @author Thomas Juhnke <typo3@van-tomas.de>
 */
class Flexform extends AbstractValueObject
{
    /**
     * Code label.
     */
    protected string $cLabel = '';

    /**
     * Code language / brush.
     */
    protected string $cLang = '';

    /**
     * Code URL / File.
     */
    protected string $cFile = '';

    /**
     * Code highlight lines.
     */
    protected string $cHighlight = '';

    /**
     * Code block collapse flag.
     */
    protected string $cCollapse = '';

    /**
     * Code block show gutter flag.
     */
    protected string $cGutter = '';

    /**
     * Default settings from settings.defaults.
     */
    protected array $typoscriptDefaults = [];

    /**
     * Language / brush fallback.
     */
    protected string $languageFallback = 'plain';

    public function __construct(protected ConfigurationInterface $highlighterConfiguration)
    {
    }

    /**
     * Initialize object from the flexForm datas
     *
     * @param array $flexformValues Parsed flexform values
     */
    public function initializeObject(array $flexformValues = []): void
    {
        $this->cLabel = $flexformValues['cLabel'] ?? $this->cLabel;
        $this->cLang = $flexformValues['cLang'] ?? $this->cLang;
        $this->cFile = $flexformValues['cFile'] ?? $this->cFile;
        $this->cHighlight = $flexformValues['cHighlight'] ?? $this->cHighlight;
        $this->cCollapse = $flexformValues['cCollapse'] ?? $this->cCollapse;
        $this->cGutter = $flexformValues['cGutter'] ?? $this->cGutter;
        $this->languageFallback = $flexformValues['languageFallback'] ?? $this->languageFallback;
    }

    public function setCLabel(string $cLabel): void
    {
        $this->cLabel = $cLabel;
    }

    public function getCLabel(): string
    {
        return $this->cLabel;
    }

    /**
     * @param string $cLang The language / brush
     */
    public function setCLang(string $cLang): void
    {
        $this->cLang = $cLang;
    }

    public function getCLang(): string
    {
        return $this->cLang;
    }

    public function setCFile(string $cFile): void
    {
        $this->cFile = $cFile;
    }

    public function getCFile(): string
    {
        return $this->cFile;
    }

    /**
     * @param string $cHighlight The highlight-lines configuration string
     */
    public function setCHighlight(string $cHighlight): void
    {
        $this->cHighlight = $cHighlight;
    }

    public function getCHighlight(): string
    {
        return $this->cHighlight;
    }

    /**
     * @param string $cCollapse The code block collapse flag
     */
    public function setCCollapse(string $cCollapse): void
    {
        $this->cCollapse = $cCollapse;
    }

    public function getCCollapse(): string
    {
        return $this->cCollapse;
    }

    public function setCGutter(string $cGutter): void
    {
        $this->cGutter = $cGutter;
    }

    public function getCGutter(): string
    {
        return $this->cGutter;
    }

    public function getIsGutterActive(): bool
    {
        $isOffForInstance = '0' === $this->cGutter;
        $isOnForInstance = '1' === $this->cGutter;
        $useDefault = 'auto' === $this->cGutter;
        $isDefaultSet = isset($this->typoscriptDefaults['gutter']);

        if ($isOffForInstance) {
            return false;
        } elseif ($isOnForInstance) {
            return true;
        } elseif ($useDefault && $isDefaultSet) {
            return (bool) $this->typoscriptDefaults['gutter'];
        }

        return false;
    }

    public function setTyposcriptDefaults(array $typoscriptDefaults = []): void
    {
        $this->typoscriptDefaults = $typoscriptDefaults;
    }

    public function getLanguage(): string
    {
        $language = $this->cLang ?: $this->languageFallback;

        return $this->highlighterConfiguration->getFailSafeBrushAlias($language);
    }

    public function getClassAttributeString(): string
    {
        return $this->highlighterConfiguration->getClassAttributeString($this);
    }

    /**
     * Returns an array of brush CSS name + ressource file name.
     */
    public function getAutoloaderBrushMap(): array
    {
        return $this->highlighterConfiguration->getAutoloaderBrushMap();
    }
}
