plugin.tx_beautyofcode {
	settings {
		# jquery or standalone
		library = {$plugin.tx_beautyofcode.library}

		showLabel = {$plugin.tx_beautyofcode.showLabel}

		deactivateLibraryService = {$plugin.tx_beautyofcode.deactivateLibraryService}

		common {
			theme = {$plugin.tx_beautyofcode.common.theme}
			brushes = {$plugin.tx_beautyofcode.common.brushes}

			defaults {
				tab-size = {$plugin.tx_beautyofcode.common.defaults.tab-size}
				gutter = {$plugin.tx_beautyofcode.common.defaults.gutter}
				collapse = {$plugin.tx_beautyofcode.common.defaults.collapse}

				# does not work in standalone mode as its removed in SyntaxHighlighter v3
				wrap-lines = {$plugin.tx_beautyofcode.common.defaults.wrap-lines}
				toolbar = {$plugin.tx_beautyofcode.common.defaults.toolbar}
			}
		}

		jquery {
			baseUrl = {$plugin.tx_beautyofcode.jquery.baseUrl}
			scripts = {$plugin.tx_beautyofcode.jquery.scripts}
			styles = {$plugin.tx_beautyofcode.jquery.styles}

			addjQuery = {$plugin.tx_beautyofcode.jquery.addjQuery}

			selector = {$plugin.tx_beautyofcode.jquery.selector}

			onReadyCallback = {$plugin.tx_beautyofcode.jquery.onReadyCallback}
		}

		standalone {
			baseUrl = {$plugin.tx_beautyofcode.standalone.baseUrl}
			scripts = {$plugin.tx_beautyofcode.standalone.scripts}
			styles = {$plugin.tx_beautyofcode.standalone.styles}
	
			# if enabled JS code is fired with a domReady event (not recomended)
			# always enabled if TYPO3 version < 4.3
			# possible options: false, native, jquery
			includeAsDomReady = {$plugin.tx_beautyofcode.standalone.includeAsDomReady}
			onReadyCallback = {$plugin.tx_beautyofcode.standalone.onReadyCallback}
		}
	}

	_CSS_DEFAULT_STYLE (
		.tx_beautyofcode pre { overflow: auto; }
	)
}