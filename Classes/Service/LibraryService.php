<?php
namespace FNagel\Beautyofcode\Service;

class LibraryService implements \FNagel\Beautyofcode\Service\LibraryServiceInterface {

	/**
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
	 */
	protected $objectManager;

	/**
	 *
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 *
	 * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
	 */
	public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}

	/**
	 * (non-PHPdoc)
	 * @see \FNagel\Beautyofcode\Service\LibraryServiceInterface::setConfigurationManager()
	 */
	public function setConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager) {
		$this->configurationManager = $configurationManager;
	}

	/**
	 * (non-PHPdoc)
	 * @see \FNagel\Beautyofcode\Service\LibraryServiceInterface::load()
	 */
	public function load($library) {
		/* @var $concreteLibraryService \FNagel\Beautyofcode\Service\AbstractLibraryService */
		$concreteLibraryService = $this->objectManager->get('FNagel\\Beautyofcode\\Service\\' . ucfirst($library) . 'LibraryService');

		$concreteLibraryService->setConfigurationManager($this->configurationManager);

		$concreteLibraryService->configure();
		$concreteLibraryService->load();
	}
}
?>