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

FAQ
---

Howto install JS vendors
^^^^^^^^^^^^^^^^^^^^^^^^

1. Download amd install node.js from http://nodejs.org/download/
2. Install bower

   .. code-block:: bash

      npm install bower

3. Install vendors from CLI

   .. code-block:: bash

      bower install

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


Run unit tests
^^^^^^^^^^^^^^

   .. code-block:: bash

      cd typoconf/ext/beautyofcode

      composer install

      ./vendor/bin/runtests


One could add a a php env node into the `phpunit.xml.dist` xml file:

   .. code-block:: xml

      <phpunit>
          <php>
              <env name="TYPO3_PATH_WEB" value="/var/www/path-to-typo3"/>
          </php>
      </phpunit>