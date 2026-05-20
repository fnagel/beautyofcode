<?php

namespace FelixNagel\Beautyofcode\ContentSecurityPolicy;

use FelixNagel\Beautyofcode\Service\SettingsService;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Directive;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Event\PolicyMutatedEvent;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\SourceKeyword;
use TYPO3\CMS\Core\Utility\GeneralUtility;

#[AsEventListener(
    identifier: 'felixnagel-beautyofcode/mutate-csp',
)]
class EventListener
{
    public function __construct(protected ?SettingsService $settingsService)
    {
    }

    public function __invoke(PolicyMutatedEvent $event): void
    {
        if ($event->scope->type->isBackend()) {
            return;
        }

        $configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('beautyofcode');
        if (!isset($configuration['enable_csp']) || $configuration['enable_csp'] == 0) {
            return;
        }

        if ($this->settingsService->getTypoScriptByPath('library') === 'SyntaxHighlighter') {
            $event->getCurrentPolicy()->extend(
                Directive::ScriptSrc,
                SourceKeyword::unsafeEval,
            );
        }
    }
}
