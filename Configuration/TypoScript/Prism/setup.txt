plugin.tx_beautyofcode {
	settings {
		showLabel = {$plugin.tx_beautyofcode.settings.showLabel}

		library = {$plugin.tx_beautyofcode.settings.library}

		useMinimizedResources = {$plugin.tx_beautyofcode.settings.useMinimizedResources}

		brushes = {$plugin.tx_beautyofcode.settings.brushes}
		theme = {$plugin.tx_beautyofcode.settings.theme}

		defaults {
			gutter = {$plugin.tx_beautyofcode.settings.defaults.gutter}
		}
	}

	_CSS_DEFAULT_STYLE (
		.tx_beautyofcode pre { overflow: auto; }
	)
}