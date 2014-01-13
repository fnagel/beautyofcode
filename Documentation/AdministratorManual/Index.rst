.. include:: ../Includes.txt

.. _admin-manual:

Administrator Manual
====================

.. _admin-installation:

Installation
------------

- Install the extension via extension manager.
- Add the static template in your main typoscript (go to: list module, root 
  page, edit your root TS template, tab: includes, add one of the static 
  templates named “beautyOfCode Syntax Highlighter”).
- You could add the jQuery core file yourself or via this extension by enable 
  addjQuery configuration. Since version 2.0 support of the t3jquery extension 
  was removed. Version 1.3.x and 1.4.x of jQuery will work. Since version 
  0.7.0 it's possible to use SyntaxHighlighter standalone without using jQuery.

**Please note**: It's strongly recommended to add one line in your 
localconf.php to force CDATA escaping within the flexforms in TYPO3. Please see 
know FAQ section.

.. _admin-configuration:

Configuration
-------------

Enable syntax highlighting (install EXT: t3editor) in BE by using 
enable_t3editor option.

**Please note**: Some extension manager settings have been removed in version 
0.7.0. Use TypoScript instead.

.. _admin-upgrade-guide:

Upgrade Guide
-------------

If there's no upgrade guide, there' s no need to change anything. This guide 
does not covering extending via PHP (aka XCLASS or Hooks) – you need to check 
changes for yourself.

0.5 to 0.6
^^^^^^^^^^

This version introduces HTML templating. That's why some TS options are not 
longer available or renamed:

- plugin.tx_beautyofcode_pi1.label.wrap is removed as it's now configured via template
- plugin.tx_beautyofcode_pi1.label.show is renamed to showLabel
- plugin.tx_beautyofcode_pi1.wrap is removed as it's now configured via template

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

To 2.0
^^^^^^

Rewritten to fit into extbase/fluid development paradigms.

What you need to know:

- Execute update script within extension manager to update adjusted plugin names in database
- You'll need to change your TypoScript code

   - include the correct static template
   - jquery.scriptUrl was removed (adapt template if you need to adjust)
   - jQueryNoConflict was removed (inline JS is wrapped in module pattern style code)
   - config.strings were removed (<f:translate /> vhs in templates)

Please see the changelog for all changes in detail.

.. _admin-faq:

FAQ
---

Which version to choose?
^^^^^^^^^^^^^^^^^^^^^^^^

This extension has three versions of SyntaxHighlighter to choose. v2 & v3 
supports lazyloading of the necessary CSS and JS files after the DOM ready 
event has fired. Version 2 uses a jQuery plugin “beautyOfCode” by Lars 
Corneliussen and version 3 wich runs standalone using the autoloader feature 
introduced with version v3. Prism is also a standalone library without any 
dependencies. There are some benefits and disadvantages by using each version:

Version v2 (“jquery”)
"""""""""""""""""""""

- needs jQuery
- configurable toolbar
- runs within a domReady event (runs very smooth when merging scripts automatically)
- less code needs to be injected in your HTML
- wrap-lines feature (line break)
- more feature rich

Version v3 (“standalone”)
"""""""""""""""""""""""""

- runs without using a Java-Script framework
- no toolbar available as it has been removed in v3
- uses latest version of SyntaxHighlighter
- slim and less feature rich (but with almost the same  functionality)

Prism Version
"""""""""""""

- runs without using a Javascript framework
- no toolbar available
- based on regular expressions for syntax parsing
- syntax files has dependencies with each other (php → clike)
- lightweight

Input is encoded and saved without “<”,  “>”, etc.
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This is a server side problem within PHP 5 respective in the libxml module 
(1.6.32 and higher) with an existing work around.

**Solution:**

Add this line in your localconf.php in typo3conf directory.

   .. code-block:: php

      $TYPO3_CONF_VARS['BE']['flexformForceCDATA']  = '1';

This fix is non breaking and works only for new added elements. Older flexform 
values should / could be fixed manually. This is a recommended default setting 
for every TYPO3 installation (see mailing list why).

**Bug**:

http://bugs.typo3.org/view.php?id=9359

**Mailingslist Post**:

`[TYPO3-dev] A ticking ticking timebomb with libxml update to 2.7.1? <libxmlbug_>`_

I don't like my users to overwrite my default TS configuration
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Take a look at the extension sp_betterflex: “Exclude static flexform fields 
made by extensions like normal table fields in backend group configuration or 
via TSConfig.”

http://typo3.org/extensions/repository/view/sp_betterflex/current/

Install the extension and add these lines to your TSconfig:

   .. code-block:: ts

      TCEFORM.tt_content.beautyofcode_cGutter.disabled = 1
      TCEFORM.tt_content.beautyofcode_cToolbar.disabled = 1
      TCEFORM.tt_content.beautyofcode_cCollapse.disabled = 1

.. _libxmlbug: http://lists.netfielders.de/pipermail/typo3-dev/2009-August/036436.html

“Missing Ext. Manager configuration” error in FE
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

You forgot to save the extension manager configuration. Please go to Extension 
Manager → beautyOfCode → click Update

“t3lib_div::array_merge_recursive_overrule()” error in BE
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This error could look like this:

`PHP Catchable Fatal Error: Argument 1 passed to t3lib_div::array_merge_recursive_overrule() 
must be an array, null given, called in /html/typo3/typo3_src-4.4.2/typo3/sysext/rtehtmlarea/class.tx_rtehtmlareaapi.php 
on line 80 and defined in /html/typo3/typo3_src-4.4.2/t3lib/class.t3lib_div.php line 2059`

This issue was already patched but seems to be introduced again in specific 
TYPO3 version. Some kind of regression I guess. Sadly Im not able to reproduce 
the issue and it even seems to be an Core / RTE problem, so please take a look 
at these bugtracker issues which should help you to solve the problem:

- http://bugs.typo3.org/view.php?id=15864
- http://bugs.typo3.org/view.php?id=15893

Enable t3editor in BE
^^^^^^^^^^^^^^^^^^^^^

You need to enable the *enable_t3editor* option in Extension Manager and check 
your user settings.

Where to post improvements or bugs
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Please feel free add questions, bugs and improvements at 
http://forge.typo3.org/projects/extension-beautyofcode/issues