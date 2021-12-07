.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

.. _upgrade-guide:

Upgrade Guide
=============


If there's no upgrade guide, there' s no need to change anything.
This guide does not covering extending via PHP (aka XCLASS or Hooks) – you need to check changes for yourself.


4.x to 5.0
^^^^^^^^^^

What you need to know:

- Added support for TYPO3 10.5 LTS
- Support for PHP 8.0
- Removed support for TYPO3 9.x
- Code clean up and code improvements

How to upgrade
""""""""""""""

- Clear all caches



4.2 to 4.3
^^^^^^^^^^

What you need to know:

- Add YAML support for SyntaxHighlighter
- Removed support for PHP < 7.2
- Code clean up and improvements
- Bugfix for TCA issue when deleting a page
- Minor bugfixes

How to upgrade
""""""""""""""

- Clear all caches


4.1 to 4.2
^^^^^^^^^^

What you need to know:

- Added support for TYPO3 10.4 LTS
- Support for PHP 7.3 and 7.4
- Automated CGL and unit tests using Github Actions
- Fix TSconfig inclusion (thanks to @tritum and @tritumBj)
- Rename TS and TSconfig files to newer file extensions
- Move TSconfig file to correct folder
- Minor fixes and clean up

How to upgrade
""""""""""""""

- Adjust your TS / TSconfig file includes if needed
- Clear all caches


4.x to 4.1
^^^^^^^^^^

What you need to know:

- Added support for TYPO3 10.2
- New extension icon
- Minor fixes and clean up

How to upgrade
""""""""""""""

- Clear all caches



3.x to 4.0
^^^^^^^^^^

_Thanks to Sebastian Fischer for all the work on this release!_

What you need to know:

- Additional input field for URLs or reference files
- Added support for TYPO3 9.x
- Removed support for TYPO3 8.x
- Removed support for PHP 5.x
- Updated Prism to 1.17.1
- Changed namespace to `FelixNagel`
- Extension is now available on Packagist

How to upgrade
""""""""""""""

- Adjust your class auto loading or class overwrites to new namespace / composer package name (if needed for your setup)
- Use "Clear all caches including PHP opcode cache" and "Dump Autoload Information" in the install tool (if needed for your setup)
- Adjust VH namespace in your overwrite templates
- Clear all caches


3.2 to 3.3
^^^^^^^^^^

What you need to know:

- Now compatible with PHP 7.2
- Improved TS generation in backend context
   - it's now possible to use TS templates on sub pages
   - No more usage of :code:`module.tx_beautyofcode < plugin.tx_beautyofcode`
- Update Prism to 1.11.0
- Bugfix for TYPO3 >= 8.5


3.1 to 3.2
^^^^^^^^^^

What you need to know:

- Now compatible with TYPO3 8.7
- Show default header in BE preview
- Use FormEngine NodeFactory API instead if XCLASS
- Update Prism to 1.6.0


3.0 to 3.1
^^^^^^^^^^

What you need to know:

- Now compatible with TYPO3 8.4 (please make sure to use root template for adding static TS when using 8.x)
- Bugfix for t3editor EM configuration (thanks to Thomas Kieslich!)
- Bugfix for new content element wizard configuration
- Code is now PSR-2 standard compliant


2.x to 3.0
^^^^^^^^^^

In order to make this extension work best possible in TYPO3 7.x and to make maintaining
more easier (especially the t3editor implementation) this version introduces usage of the
default `tt_content` `bodytext` field.

What you need to know:

- Removed TYPO3 6.x support
- There is an update wizard available through Extension Manager to migrate your existing records
- Almost no visible changes in backend user interface
- Make sure to adopt your TypoScript (assigning your settings to `module.` for backend context)
- Updated PrimJs vendor to version 1.3.0
- Add some new PrimsJs languages (Actionscript, Applescript, Diff, Erlang, Git, Sass, Scala, Twig, Less, MarkDown, Powershell, Yaml)
- Use core icon for plugin and wizard
- Added a composer.json and a bower.json file
- Improved code style


1.0 to 2.0
^^^^^^^^^^

Rewritten for Extbase/Fluid.

What you need to know:

- Execute update script within extension manager to update adjusted plugin names
  in database
- You'll need to change your TypoScript code

  - SyntaxHighlighter v2 + beautyofcode (jQuery) abstraction layer was removed
  - include the correct static template
  - removed TypoScript constants / setup paths:

    - jquery.scriptUrl (adapt template if you need to adjust)
    - jQueryNoConflict (inline JS is wrapped in module pattern style code)
    - config.strings (<f:translate /> vhs in templates)
    - addjQuery (please make sure to include jQuery by yourself if you want to
      use the `includeAsDomReady` / `onReadyCallback` configuration settings
    - selector

Please see the changelog for all changes in detail.


0.6 to 0.7
^^^^^^^^^^

Big update: finally its possible to use this extension without jQuery. Massive
remanufacturing and code changes. Please consider this release as a beta
version as I did test every feature the best I can but not sure if everything
will work out on every TYPO3 installation.

What you need to know:

- You'll need to change your TypoScript code

   - paths for base, scripts and styles
   - addjQuery and selector have been moved to the jquery. Array
   - some variables have been slightly renamed

- Extension Manager settings has been removed, use TS configuration instead
- SyntaxHighlighter v3 changed some features (learn more: “Which version to choose”)

Please see the changelog for all changes in detail.


0.5 to 0.6
^^^^^^^^^^

This version introduces HTML templating. That's why some TS options are not
longer available or renamed:

- `plugin.tx_beautyofcode_pi1.label.wrap` is removed as it's now configured via template
- `plugin.tx_beautyofcode_pi1.label.show` is renamed to showLabel
- `plugin.tx_beautyofcode_pi1.wrap` is removed as it's now configured via template
