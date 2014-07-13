﻿.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

.. _user-manual:

Users Manual
============

Target group: **Editors**

Add the beautyofcode frontend plugin via the FCE wizard or manually via a 
generic plugin. After adding the plugin there are 2 tabs available.

Tab: beautyOfCode
-----------------

These fields should be self explanatory.

- Description (not necessary, if empty no description is displayed)
- Programming language (available languages configured by your admin)
- Code

Tab: Options
------------

Within this tab its possible:

- to highlight some specific lines. Use a syntax like: *1,2,3,4,5 or 1-5*
- to overwrite the default settings configured by your admin.

   - Gutter (show line numbers)
   - Collapse (displays code element collapsed)

Please note: Configuration overwrite via the Option tab could be disabled by 
your admin.

.. _user-faq:

FAQ
---

No highlighting in FE
^^^^^^^^^^^^^^^^^^^^^

If you're using the SyntaxHighlighter library, and decided to make usage of the 
includeAsDomReady setting set to "jquery", you must ensure to either install the 
extension t3jquery or include a jQuery core file by yourself? You either need to 
add the jQuery Core file manually or activate the addjQuery option.

I get a Java-Script error in FE
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

See above.

Do you use other JavaScript frameworks? Please see down below.

I'm using MooTools, ExtJS or similar
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

No problem, the generated inline javascript code uses the module pattern and 
therefore doesn't need the noConflict option to be set.

Please note and keep in mind if jQuery must be loaded (minified about 56KB) 
this will lead to more traffic and less performance for your users.

How to change language strings
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Adjust the templates according to your needs. Please follow the Fluid 
templating guides for how to use your own templates for a specific extension.

Also have a look at the following article:

http://xavier.perseguers.ch/tutoriels/typo3/articles/managing-localization-files.html

Where to post improvements or bugs
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Please feel free add questions, bugs and improvements at 
http://forge.typo3.org/projects/extension-beautyofcode/issues