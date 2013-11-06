plugin.tx_beautyofcode {
	settings {
		# jquery or standalone
		version = {$plugin.tx_beautyofcode_pi1.version}

		jquery {
			baseUrl = {$plugin.tx_beautyofcode_pi1.jquery.baseUrl}
			scripts = {$plugin.tx_beautyofcode_pi1.jquery.scripts}
			styles = {$plugin.tx_beautyofcode_pi1.jquery.styles}
			scriptUrl = {$plugin.tx_beautyofcode_pi1.jquery.scriptUrl}
			addjQuery = {$plugin.tx_beautyofcode_pi1.jquery.addjQuery}
			selector = {$plugin.tx_beautyofcode_pi1.jquery.selector}
		}

		templateFile = {$plugin.tx_beautyofcode_pi1.templateFile}
		jQueryNoConflict = {$plugin.tx_beautyofcode_pi1.jQueryNoConflict}
		jQueryOnReadyCallback = {$plugin.tx_beautyofcode_pi1.jQueryOnReadyCallback}

		showLabel = {$plugin.tx_beautyofcode_pi1.showLabel}
		theme = {$plugin.tx_beautyofcode_pi1.theme}
		brushes = {$plugin.tx_beautyofcode_pi1.brushes}

		defaults {
			tab-size = {$plugin.tx_beautyofcode_pi1.defaults.tab-size}
			gutter = {$plugin.tx_beautyofcode_pi1.defaults.gutter}
			collapse = {$plugin.tx_beautyofcode_pi1.defaults.collapse}

			# does not work in standalone mode as its removed in SyntaxHighlighter v3
			wrap-lines = {$plugin.tx_beautyofcode_pi1.defaults.wrap-lines}
			toolbar = {$plugin.tx_beautyofcode_pi1.defaults.toolbar}
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
			# config.strings.viewSource = custom string
		# [global]
	}

	_CSS_DEFAULT_STYLE (
		.tx_beautyofcode_pi1 pre { overflow: auto; }
	)
}

tx_beautyofcode_assetrenderer = PAGE
tx_beautyofcode_assetrenderer {
	typeNum = 1383777325
	config {
		disableAllHeaderCode = 1
		additionalHeaders = Content-Type: text/css
		xhtml_cleaning = 0
		admPanel = 0
	}
	10 = USER
	10 {
		userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
		extensionName = Beautyofcode
		pluginName = AssetRenderer
		vendorName = FNagel
		controller = JqueryAsset
		switchableControllerActions {
			JqueryAsset {
				1 = render
			}
			/*
			StandaloneAsset {
				1 = render
			}
			*/
		}
		view < plugin.tx_beautyofcode.view
		persistence < plugin.tx_beautyofcode.persistence
		settings < plugin.tx_beautyofcode.settings
	}
}