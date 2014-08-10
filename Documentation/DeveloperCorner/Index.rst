.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

.. _developer:

Developer Corner
================

.. _generated-markup:

Generated markup
----------------

Syntax Highligther
^^^^^^^^^^^^^^^^^^

   .. code-block:: html
      :linenos:

      <pre class="php">
         CODE GOES HERE
      </pre>

Prism
^^^^^

   .. code-block:: html
      :linenos:

      <pre class="language-php">
         <code>
            CODE GOES HERE
         </code>
      </pre>

Adjusting the Fluid template output
-----------------------------------

Plugin & Brushloader rendering
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Create a TYPO3.CMS extension which should encapsulate the template adjustments
of the installed ext:beautyofcode extension in your TYPO3.CMS instance.

After that, create the necessary directories and copy the template files to the
created folders:

   .. code-block:: bash

      ~$ cd typo3conf/ext/
      ~$ mkdir -p [YOUR_EXTENSION]/Resources/Private/{Layouts,Partials,Templates}
      ~$ cp -R beautyofcode/Resources/Private/Layouts/* [YOUR_EXTENSION]/Resources/Private/Layouts/
      ~$ cp -R beautyofcode/Resources/Private/Partials/* [YOUR_EXTENSION]/Resources/Private/Partials/
      ~$ cp -R beautyofcode/Resources/Private/Templates/Content/ [YOUR_EXTENSION]/Resources/Private/Templates/

Now, you have to configure beautyofcode to use the new template paths:

   .. code-block: typoscript

      plugin.tx_beautyofcode.view {
        layoutRootPath = EXT:[YOUR_EXTENSION]/Resources/Private/Layouts/
        partialRootPath = EXT:[YOUR_EXTENSION]/Resources/Private/Partials/
        templateRootPath = EXT:[YOUR_EXTENSION]/Resources/Private/Templates/
      }

FAQ
---

Howto install prism
^^^^^^^^^^^^^^^^^^^

1. Download node.js binaries from http://nodejs.org/download/
2. extract tar.gz
3. edit ~/.bashrc and extend $PATH

   .. code-block:: bash

      export PATH=${PATH}:/path/to/extracted/targz/content/bin

4. install bower

   .. code-block:: bash

      /path/to/extracted/targz/content/bin/npm install bower

5. create .bowerrc to install package within Resources/Public/Javascript/vendor

   .. code-block:: js

      {
         "directory": "./Resources/Public/Javascript/vendor/"
      }

6. install prism from CLI

   .. code-block:: bash

      ./node_modules/.bin/bower install prism#gh-pages

Howto add BOM to all reStructuredText files
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

While editing the files in Eclipse, the BOM possibly gets removed, this script
will help you to re-add the UTF8 BOM again.

Simply put that into the extension root directory, add executable rights (`chmod u+x ...`).
If you are still experiencing issues, just comment out the last line and execute the script
multiple times until no output is displayed. Then uncomment the last line.

   .. code-block:: bash

      #!/bin/bash
      for F in $(ls -R Documentation/*.rst Documentation/*/*.rst Documentation/*.txt)
      do
        if [[ -f $F && `head -c 3 $F` == $'\xef\xbb\xbf' ]]; then
            # file exists and has UTF-8 BOM
            mv $F $F.bak
            tail -c +4 $F.bak > $F
            rm -f $F.bak
            echo "removed BOM from $F"
        fi
      done
      for file in $(ls -R Documentation/*.rst Documentation/*/*.rst Documentation/*.txt); do sed -i '1s/^/\xef\xbb\xbf/' "$file"; done

page.1407710024 - What is this?
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This special content object is necessary in order to render all beautyofcode assets
which depends on the plugins inserted on a certain page. For example it ensures
to only load the brush assets which are necessary for the specific page.

While developing the brush auto discovery + registry components for beautyofcode
we tested how it works within certain conditions. First, we added the additional
assets by hooking into the render-preProcess hook of the PageRenderer component.

But this doesn't worked in contexts where *_INT cObjects (USER_INT / COA_INT)
were used on the page. The reason for this is: the pageRenderer is serialized
for the *_INT cObject processing, staying in a state where assets added by plugins
from the content rendering context aren't available - saying very early in the
request lifecycle of TYPO3.CMS.

So we decided to add a special Extbase Controller for adding the page assets
during content rendering which works in cached environment as well as uncached
environment where *_INT cObjects are parsed.

If you are using a different page cObject naming or added other PAGE cObjects for
special rendering purposes which renders beautyofcode plugins, you have to make
sure, the PageAssets controller of beautyofcode is added as one of the last
content element definitions within your PAGE setup:

   .. code-block:: bash

      ~$ # generate a big integer (current unix time) for cObject array usage
      ~$ date +%s
      1407710024

   .. code-block:: typoscript

      myPage = PAGE
      myPage {
        // use the generated integer to ensure the beautyofcode page assets are
        // added after all inserted syntax highlighting content element plugins
        1407710024 < tt_content.20.list.beautyofcode_pageassets
      }