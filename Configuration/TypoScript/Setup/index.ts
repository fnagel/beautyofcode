plugin.tx_beautyofcode {
	settings {
		# jquery or standalone
		version = {$plugin.tx_beautyofcode.version}

		jquery {
			baseUrl = {$plugin.tx_beautyofcode.jquery.baseUrl}
			scripts = {$plugin.tx_beautyofcode.jquery.scripts}
			styles = {$plugin.tx_beautyofcode.jquery.styles}
			scriptUrl = {$plugin.tx_beautyofcode.jquery.scriptUrl}
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

			# example of how to edit JS language strings (german)
			# use globalVar conditions for multilanguage support
			# config {
				# strings {
					# expandSource = Quellcode anzeigen
					# viewSource = Quellcode im PopUp öffnen
					# copyToClipboard = In Zwischenablage kopieren
					# copyToClipboardConfirmation = Der Quellcode bedindet sich jetzt in der Zwischenablage
					# print = Quellcode drucken
				# }
			# }
			# For mulitlanguage installations please use globalVar conditons:
			# [globalVar = GP:L = 1]
				# common.config.strings.viewSource = custom string
			# [global]
		}
	}

	_CSS_DEFAULT_STYLE (
		.tx_beautyofcode pre { overflow: auto; }
	)
}