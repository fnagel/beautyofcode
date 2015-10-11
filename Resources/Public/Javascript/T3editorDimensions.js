/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Thomas Juhnke <typo3@van-tomas.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the text file GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/* Corrects dimenions of invisible T3editor instances
 */
;(function () {
	"use strict";

	var
		/**
		 * Flag if jQuery is used
		 *
		 * @type {boolean}
		 */
		usesJquery = !!window.TYPO3.jQuery,

		/**
		 * Textarea -> CodeMirror wrap width correction
		 * @type {integer}
		 */
		widthCorrection = 80,

		/**
		 * Textarea width factor
		 * @type {integer}
		 */
		textareaWidthFactor = 2,

		/**
		 * Returns relevant elements dimensions
		 * @param {T3editor} t3editor
		 */
		getRelevantElementsDimensions = function(t3editor) {
			var
				$textarea = usesJquery ? window.TYPO3.jQuery(t3editor.textareaHack) : t3editor.textarea,

				$outerdiv = usesJquery ? window.TYPO3.jQuery(t3editor.wrapping) : t3editor.outerdiv;

			return {
				textarea: usesJquery ? { width: $textarea.width(), height: $textarea.height() } : $textarea.getDimensions(),
				outerdiv: usesJquery ? { width: $outerdiv.width(), height: $outerdiv.height() } : $outerdiv.getDimensions()
			};
		},

		/**
		 * Flags if the given dimensions settings should lead to T3editor instance resizing
		 * @param {object} dimensions
		 * @return {boolean}
		 */
		shouldDimensionCorrectionBeApplied = function (dimensions) {
			var
				isOuterDivWidthTooSmall = dimensions.outerdiv.width < (dimensions.textarea.width / textareaWidthFactor),
				isOuterDivHeightTooSmall = dimensions.outerdiv.height < (dimensions.textarea.height / textareaWidthFactor);

			return isOuterDivWidthTooSmall || isOuterDivHeightTooSmall;
		},

		/**
		 * Resizes the given T3editor instance
		 * @param {T3editor} t3edito
		 */
		resizeT3editorInstance = function (t3editor) {
			var
				dimensions = usesJquery ? getRelevantElementsDimensions(this) : getRelevantElementsDimensions(t3editor);

			if (shouldDimensionCorrectionBeApplied(dimensions)) {
				t3editor.resize(
					dimensions.textarea.width - widthCorrection,
					dimensions.textarea.height
				);
			}
		},

		/**
		 * Event handler
		 * @param {Event} event
		 */
		t3EditorDimensionsCorrection = function(event) {
			if (usesJquery) {
				window.TYPO3.jQuery.each(TYPO3.T3editor.instances, resizeT3editorInstance);
			} else {
				Object.values(T3editor.instances).each(resizeT3editorInstance);
			}
		};

	// bind the click event to correct dimensions of invisible T3editor instances
	if (usesJquery) {
		window.TYPO3.jQuery(document).on('click', t3EditorDimensionsCorrection);
	} else {
		document.observe('click', t3EditorDimensionsCorrection);
	}
})();