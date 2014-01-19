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

   .. code-block: bash

      ./node_modules/.bin/bower install prism#gh-pages

Howto add BOM to all reStructuredText files
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

While editing the files in Eclipse, the BOM possibly gets removed, this command 
will help you to re-add the UTF8 BOM again:

   .. code-block: bash

      for file in $(ls -R Documentation/*.rst Documentation/*/*.rst); do sed -i '1s/^/\xef\xbb\xbf/' "$file"; done