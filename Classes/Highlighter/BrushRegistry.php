<?php
namespace TYPO3\Beautyofcode\Highlighter;

/***************************************************************
 * Copyright notice
 *
 * (c) 2014 Thomas Juhnke <typo3@van-tomas.de>
 *
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * This registry object allows collection of used brushes on a certain
 * page during frontend renderings.
 *
 * @package \TYPO3\Beautyofcode\Highlighter
 * @author Thomas Juhnke <typo3@van-tomas.de>
 * @license http://www.gnu.org/licenses/gpl.html
 *          GNU General Public License, version 3 or later
 * @link http://www.van-tomas.de/
 */
class BrushRegistry implements \IteratorAggregate {

	/**
	 * @var ConfigurationInterface
	 */
	protected $configuration;

	/**
	 * @var array
	 */
	protected $dependencies = array();

	/**
	 * @var array
	 */
	protected $brushes = array();

	/**
	 * Constructor
	 *
	 * Expects the current Highlighter\Configuration and the brush identifier
	 * dependencies.
	 *
	 * @param ConfigurationInterface $configuration
	 * @param array $dependencies
	 * @return BrushRegistry
	 */
	public function __construct(ConfigurationInterface $configuration, array $dependencies) {
		$this->configuration = $configuration;
		$this->dependencies = $dependencies;
	}

	/**
	 * initializeObject
	 *
	 * @return void
	 */
	public function initializeObject() {
		if ($this->configuration->hasStaticBrushes()) {
			$this->initializeStaticBrushes();
		}
	}

	/**
	 * initializeStaticBrushes
	 *
	 * @return void
	 */
	protected function initializeStaticBrushes() {
		$identifiers = $this->configuration->getStaticBrushesWithPlainFallback();
		foreach ($identifiers as $identifier) {
			$alias = $this->configuration->getBrushAliasByIdentifier($identifier);

			$this->add($alias);
		}
	}

	/**
	 * addDependencies
	 *
	 * @param string $brushAlias
	 * @return void
	 */
	protected function addDependencies($brushAlias) {
		while (isset($this->dependencies[$brushAlias])) {
			$brushAlias = $this->dependencies[$brushAlias];

			$identifier = $this->configuration->getBrushIdentifierByAlias($brushAlias);

			$this->brushes = array_merge(
				array($brushAlias => $identifier),
				$this->brushes
			);
		}
	}

	/**
	 * add
	 *
	 * @param string $brushAlias
	 * @return void
	 */
	public function add($brushAlias) {
		$identifier = $this->configuration->getBrushIdentifierByAlias($brushAlias);

		if (FALSE === isset($this->brushes[$brushAlias])) {
			$this->brushes[$brushAlias] = $identifier;
		}

		$this->addDependencies($brushAlias);
	}

	/**
	 * getIterator
	 *
	 * @return \ArrayIterator
	 */
	public function getIterator() {
		return new \ArrayIterator($this->brushes);
	}
}