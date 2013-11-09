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
	 * @var \FNagel\Beautyofcode\Service\AbstractLibraryService
	 */
	protected $concreteLibraryService;

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
	public function setConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
	}

	/**
	 * (non-PHPdoc)
	 * @see \FNagel\Beautyofcode\Service\LibraryServiceInterface::load()
	 */
	public function load($library) {
		$this->concreteLibraryService = $this->objectManager->get('FNagel\\Beautyofcode\\Service\\' . ucfirst($library) . 'LibraryService');

		$this->concreteLibraryService->setConfigurationManager($this->configurationManager);

		$this->concreteLibraryService->configure();
		$this->concreteLibraryService->load();
	}

	/**
	 * (non-PHPdoc)
	 * @see \FNagel\Beautyofcode\Service\LibraryServiceInterface::getCssConfig()
	 */
	public function getClassAttributeConfiguration($config = array()) {
		return $this->concreteLibraryService->getClassAttributeConfiguration($config);
	}
}
?>