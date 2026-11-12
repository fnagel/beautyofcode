<?php

declare(strict_types=1);

namespace FelixNagel\Beautyofcode\Index\Indexing;

/**
 * This file is part of the "beautyofcode" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Lochmueller\Index\Indexing\Database\ContentType\ContentTypeInterface;
use Lochmueller\Index\Indexing\Database\ContentType\HeaderContentType;
use Lochmueller\Index\Indexing\Database\DatabaseIndexingDto;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use TYPO3\CMS\Core\Domain\Record;

/**
 * Content type for the database indexing of EXT:index.
 *
 * Adds the header and the code of the content element. Code loaded from a file or URL is not indexed.
 */
#[AutoconfigureTag(name: 'index.content_type')]
class CodeContentType implements ContentTypeInterface
{
    public function __construct(
        protected HeaderContentType $headerContentType,
    ) {
    }

    public function canHandle(Record $record): bool
    {
        return $record->getRecordType() === 'beautyofcode_contentrenderer';
    }

    public function addContent(Record $record, DatabaseIndexingDto $dto): void
    {
        $this->headerContentType->addContent($record, $dto);

        $code = trim((string)$record->get('bodytext'));
        if ($code !== '') {
            // @extensionScannerIgnoreLine
            $dto->content .= '<pre><code>' . htmlspecialchars($code) . '</code></pre>';
        }
    }

    /**
     * @param \SplQueue<DatabaseIndexingDto> $queue
     */
    public function addVariants(Record $record, \SplQueue &$queue): void
    {
    }
}
