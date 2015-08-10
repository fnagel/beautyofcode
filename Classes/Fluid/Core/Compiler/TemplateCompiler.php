<?php

namespace TYPO3\Beautyofcode\Fluid\Core\Compiler;

/*                                                                        *
 * This script is backported from the TYPO3 Flow package "TYPO3.Fluid".   *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

class TemplateCompiler extends \TYPO3\CMS\Fluid\Core\Compiler\TemplateCompiler {

	/**
	 * @param string $identifier
	 * @param \TYPO3\CMS\Fluid\Core\Parser\ParsingState $parsingState
	 * @return void
	 */
	public function store($identifier, \TYPO3\CMS\Fluid\Core\Parser\ParsingState $parsingState) {
		$identifier = $this->sanitizeIdentifier($identifier);
		$this->variableCounter = 0;
		$generatedRenderFunctions = '';

		if ($parsingState->getVariableContainer()->exists('sections')) {
			$sections = $parsingState->getVariableContainer()->get('sections');
			// @todo refactor to $parsedTemplate->getSections()
			foreach ($sections as $sectionName => $sectionRootNode) {
				$generatedRenderFunctions .= $this->generateCodeForSection($this->convertListOfSubNodes($sectionRootNode), 'section_' . sha1($sectionName), 'section ' . $sectionName);
			}
		}
		$generatedRenderFunctions .= $this->generateCodeForSection($this->convertListOfSubNodes($parsingState->getRootNode()), 'render', 'Main Render function');
		$convertedLayoutNameNode = $parsingState->hasLayout() ? $this->convert($parsingState->getLayoutNameNode()) : array('initialization' => '', 'execution' => 'NULL');

		$classDefinition = 'class FluidCache_' . $identifier . ' extends \\TYPO3\\CMS\\Fluid\\Core\\Compiler\\AbstractCompiledTemplate';

		$templateCode = <<<EOD
%s {

public function getVariableContainer() {
	// @todo
	return new \TYPO3\CMS\Fluid\Core\ViewHelper\TemplateVariableContainer();
}
public function getLayoutName(\TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface \$renderingContext) {
\$currentVariableContainer = \$renderingContext->getTemplateVariableContainer();
%s
return %s;
}
public function hasLayout() {
return %s;
}

%s

}
EOD;
		$templateCode = sprintf($templateCode,
			$classDefinition,
			$convertedLayoutNameNode['initialization'],
			$convertedLayoutNameNode['execution'],
			($parsingState->hasLayout() ? 'TRUE' : 'FALSE'),
			$generatedRenderFunctions);
		$this->templateCache->set($identifier, $templateCode);
	}
}
