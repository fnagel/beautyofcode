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
	 * @var array
	 */
	protected $configuration;

	/**
	 *
	 * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
	 */
	public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}

	public function setConfiguration($configuration) {
		$this->configuration = $configuration;
	}

	/**
	 * (non-PHPdoc)
	 * @see \FNagel\Beautyofcode\Service\LibraryServiceInterface::load()
	 */
	public function load($library) {
		try {
			/* @var $concreteLibraryService \FNagel\Beautyofcode\Service\AbstractLibraryService */
			$concreteLibraryService = $this->objectManager->get('FNagel\\Beautyofcode\\Service\\' . ucfirst($library) . 'LibraryService');
			$concreteLibraryService->setConfiguration($this->configuration);
			$concreteLibraryService->load();
		} catch (\FNagel\Beautyofcode\Service\LibraryServiceAlreadyLoadedException $e) {
		}
	}
}
?>