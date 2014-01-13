.. include:: ../Includes.txt

.. _developer:

Developer Corner
================

Target group: **Developers**

Use this section for providing code example or any useful information code wise.

.. _generated-markup:

Generated markup
----------------

jQuery Version (Syntax Highligther v2)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

   .. code-block:: html
      :linenos:

      <pre class="code">
         <code class="php">
            CODE GOES HERE
         </code>
      </pre>

Standalone Version (Syntax Highligther v3)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

   .. code-block:: html
      :linenos:

      <pre class="php">
         CODE GOES HERE
      </pre>

Prism Version
^^^^^^^^^^^^^

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
3. edit ~/.bashrc and extend $PATH:
   export PATH=${PATH}:/path/to/extracted/targz/content/bin
4. install bower
   /path/to/extracted/targz/content/bin/npm install bower
5. create .bowerrc to install package within Resources/Public/Javascript/vendor:
   {
     "directory": "./Resources/Public/Javascript/vendor/"
   }
6. install prism from CLI:
   ./node_modules/.bin/bower install prism#gh-pages