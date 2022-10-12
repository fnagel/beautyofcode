<?php

namespace FelixNagel\Beautyofcode\Hooks;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\LinkHandling\LinkService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook class for PageLayoutView hook `list_type_Info`.
 *
 * @author Felix Nagel <info@felixnagel.com>
 */
class PageLayoutViewHooks implements PageLayoutViewDrawItemHookInterface
{
    /**
     * Reference to translation catalogue.
     *
     * @var string
     */
    public const TRANSLATION_CATALOGUE = 'LLL:EXT:beautyofcode/Resources/Private/Language/locallang_db.xlf';

    /**
     * Maximum textarea lines.
     *
     * @var int
     */
    public const MAX_TEXTAREA_LINES = 15;

    /**
     * Maximum textarea height.
     *
     * @var int
     */
    public const MAX_TEXTAREA_HEIGHT = 150;

    /**
     * Small textarea factor.
     *
     * @var int
     */
    public const SMALL_TEXTAREA_FACTOR = 20;

    /**
     * Small textarea addition.
     *
     * @var int
     */
    public const SMALL_TEXTAREA_ADDITION = 5;

    /**
     * Flexform data.
     *
     * @var array
     */
    protected $flexformData = [];

    /**
     * @var string
     */
    protected $textareaHeight = '';

    /**
     * Preprocesses the preview rendering of a content element.
     *
     * @param PageLayoutView $parentObject  Calling parent object
     * @param bool           $drawItem      Whether to draw the item using the default functionalities
     * @param string         $headerContent Header content
     * @param string         $itemContent   Item content
     * @param array          $row           Record row of tt_content
     */
    public function preProcess(PageLayoutView &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row)
    {
        if ($row['CType'] === 'beautyofcode_contentrenderer') {
            $drawItem = false;

            $this->flexformData = GeneralUtility::xml2array($row['pi_flexform']);

            $uid = $row['uid'];

            if (is_array($this->flexformData)) {
                $this->buildHeaderContent($headerContent, $uid);

                $itemContent = $this->buildCodeLanguageHeader();

                $itemContent .= $this->buildCodePreview($uid, $row['bodytext']);
            }
        }
    }

    /**
     * Builds the header content
     *
     * @param string $headerContent
     * @param int $uid
     */
    protected function buildHeaderContent(&$headerContent, $uid)
    {
        $label = $this->buildLabelHeader();

        if (empty($headerContent)) {
            $editLink = $this->getBackendUrl($uid);
            $headerContent = sprintf('<strong><a href="%s">%s</strong></a>', $editLink, $label);
        } else {
            $headerContent .= $label;
        }
    }

    /**
     * @param int $uid
     * @param string $table
     * @return string
     */
    protected function getBackendUrl($uid, $table = 'tt_content')
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);

        return (string)$uriBuilder->buildUriFromRoute('record_edit', [
            'edit' => [$table => [$uid => 'edit']],
            'returnNewPageId' => 1,
            'returnUrl' => GeneralUtility::getIndpEnv('REQUEST_URI'),
        ]);
    }

    /**
     * Builds a header by reading the label field.
     *
     * Falls back to "no label" from l10n catalogue
     *
     * @return string
     */
    protected function buildLabelHeader()
    {
        $header = sprintf(
            '<em>%s</em>',
            $GLOBALS['LANG']->sL(self::TRANSLATION_CATALOGUE.':cms_layout.no_label')
        );

        $label = $this->flexformData['data']['sDEF']['lDEF']['cLabel']['vDEF'];

        if (strlen(trim($label)) > 0) {
            $header = htmlspecialchars($label);
        }

        return $header;
    }

    /**
     * Builds the code language header.
     *
     * @return string
     */
    protected function buildCodeLanguageHeader()
    {
        return sprintf(
            '<br /><br /><strong>%s</strong> (%s)<br />',
            $GLOBALS['LANG']->sL(self::TRANSLATION_CATALOGUE.':code'),
            htmlspecialchars($this->flexformData['data']['sDEF']['lDEF']['cLang']['vDEF'])
        );
    }

    /**
     * Builds a textarea code preview field.
     *
     * @param int    $uid       The uid of the content record
     * @param string $code The code block
     *
     * @return string
     */
    protected function buildCodePreview($uid, $code)
    {
        $preview = sprintf(
            '<em>%s</em>',
            $GLOBALS['LANG']->sL(self::TRANSLATION_CATALOGUE.':cms_layout.no_code')
        );

        if (strlen($code) > 0) {
            $this->calculateTextareaHeight($code);

            $preview = sprintf(
                '<textarea id="ta_hidden%s" style="display: none;" readonly="readonly">%s</textarea>',
                $uid,
                chr(10).htmlspecialchars($code)
            );
            $preview .= sprintf(
                '<textarea id="ta%s" style="height: %s; width: 98%%; padding: 1%%; margin: 0;"
                          wrap="off" readonly="readonly"></textarea>',
                $uid,
                $this->textareaHeight
            );
            $preview .= sprintf(
                '
				<script type="text/javascript">
					var
						ta_hidden%s = document.getElementById("ta_hidden%s"),
						ta%s = document.getElementById("ta%s");

					window.setTimeout(function() {
						ta%s.value = ta_hidden%s.value;
					}, 500);
				</script>',
                $uid,
                $uid,
                $uid,
                $uid,
                $uid,
                $uid
            );
        } else {
            $file = $this->flexformData['data']['sDEF']['lDEF']['cFile']['vDEF'];

            if (strlen($file) > 0) {
                $linkService = GeneralUtility::makeInstance(LinkService::class);
                $data = $linkService->resolveByStringRepresentation($file);

                if ($data['type'] === 'file') {
                    /** @var File $fileObject */
                    $fileObject = $data['file'];

                    if ($fileObject !== null) {
                        $file = $fileObject->getPublicUrl();
                    }
                }

                $preview = sprintf(
                    '<em>%s</em>',
                    $file
                );
            }
        }

        return $preview;
    }

    /**
     * Calculates the height for the textarea field.
     *
     * Newlines in $content be counted and then used to calculate the textarea
     * height.
     *
     * @param string $content The content
     * @param string $unit    CSS unit
     */
    protected function calculateTextareaHeight($content, $unit = 'px')
    {
        $lines = preg_split("/(\n)/", $content);
        $proxyLines = is_countable($lines) ? count($lines) : 0;

        if ($proxyLines > self::MAX_TEXTAREA_LINES) {
            $textareaHeight = self::MAX_TEXTAREA_HEIGHT;
        } else {
            $textareaHeight = $proxyLines * self::SMALL_TEXTAREA_FACTOR;
            $textareaHeight += self::SMALL_TEXTAREA_ADDITION;
        }

        $this->textareaHeight = sprintf('%s%s', $textareaHeight, $unit);
    }
}
