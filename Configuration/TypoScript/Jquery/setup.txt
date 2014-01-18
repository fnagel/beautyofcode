plugin.tx_beautyofcode {
	settings {
		showLabel = {$plugin.tx_beautyofcode.settings.showLabel}

		library = {$plugin.tx_beautyofcode.settings.library}

		baseUrl = {$plugin.tx_beautyofcode.settings.baseUrl}
		scripts = {$plugin.tx_beautyofcode.settings.scripts}
		styles = {$plugin.tx_beautyofcode.settings.styles}

		addjQuery = {$plugin.tx_beautyofcode.settings.addjQuery}

		selector = {$plugin.tx_beautyofcode.settings.selector}

		onReadyCallback = {$plugin.tx_beautyofcode.settings.onReadyCallback}

		brushes = {$plugin.tx_beautyofcode.settings.brushes}
		theme = {$plugin.tx_beautyofcode.settings.theme}

		defaults {
			tab-size = {$plugin.tx_beautyofcode.settings.defaults.tab-size}
			toolbar = {$plugin.tx_beautyofcode.settings.defaults.toolbar}
			gutter = {$plugin.tx_beautyofcode.settings.defaults.gutter}
			collapse = {$plugin.tx_beautyofcode.settings.defaults.collapse}
			wrap-lines = {$plugin.tx_beautyofcode.settings.defaults.wrap-lines}
		}
	}

	_CSS_DEFAULT_STYLE (
		.tx_beautyofcode pre { overflow: auto; }
	)
}