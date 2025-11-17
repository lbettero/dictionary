<?php
// ==============================
//  DICTIONARY MODULE CONFIGURATION
// ==============================

// Caminho físico desta pasta
define('DICT_BASE_PATH', __DIR__ . '/');

// URL base desta pasta
define('DICT_BASE_URL', rtrim(dirname($_SERVER['PHP_SELF']), '/') . '/');

// Modo demo sempre ativo aqui
define('DICT_DEMO_MODE', true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dictionary Module – v1.0.0</title>

    <!-- Load ONLY the module CSS -->
    <link rel="stylesheet" href="<?= DICT_BASE_URL ?>assets/css/dictionary.css">
    <link rel="stylesheet" href="<?= DICT_BASE_URL ?>assets/css/dictionary-custom.css">

    <!-- JS globals -->
    <script>
        window.DICT_DEMO_MODE = true;
        window.DICT_BASE_URL  = "<?= DICT_BASE_URL ?>";
        window.DICT_SCOPE_SELECTOR = '[data-dictionary-scope]';
    </script>
</head>

<body>

    <h1>Dictionary Module – v1.0.0</h1>

    <p>
        This module highlights predefined terms inside any webpage and shows tooltips with definitions stored in
        <code>assets/data/dictionary.json</code>.
    </p>

    <h2>Demo</h2>

    <p>
        Below is a demonstration of the tooltip functionality as it works inside any website.
    </p>

    <!-- ESCOP0 DO DICIONÁRIO -->
    <div data-dictionary-scope="true">
        <p>
            The field of Archaeology provides essential insights into ancient cultures.
            Many projects require attention to Interoperability when integrating systems.
            Cultural Heritage plays an important role in preserving identity and collective memory.
        </p>

        <p>Hover over the highlighted terms to see the tooltip.</p>
    </div>

    <h2>Dictionary JSON</h2>

    <p>
        All dictionary terms are defined in:
        <code>assets/data/dictionary.json</code>
    </p>

    <p>
        <a href="<?= DICT_BASE_URL ?>assets/data/dictionary.json" download target="_blank">Download dictionary.json</a>
    </p>

    <h2>Styles & Customization</h2>

    <p>
        The dictionary module uses two CSS files:
    </p>

<pre>
assets/css/dictionary.css        ← Core tooltip engine
assets/css/dictionary-custom.css ← Default visual style
</pre>

    <p>
        In real websites, the module automatically inherits styling from the main site theme.
    </p>

    <p>
        You can override all visual styles using your own CSS, as long as it is placed here:
    </p>

<pre>
public/assets/css/main.css
</pre>
    <p>or</p>
<pre>
public/style.css
</pre>

    <p>
        If either file exists, the dictionary will adopt its colors, spacing and typography automatically.
    </p>

    <h2>Example Previews</h2>

    <p>These images illustrate how the dictionary content and styles were edited in earlier versions:</p>

    <h3>Content Editor (old version – now removed)</h3>
    <img src="<?= DICT_BASE_URL ?>content_editor.png" alt="Content Editor Example">

    <h3>CSS Style Editor (old version – now removed)</h3>
    <img src="<?= DICT_BASE_URL ?>style_editor.png" alt="CSS Style Editor Example">

    <p>
        These tools were intentionally removed from the final release for security and simplicity.
    </p>

    <h2>Installation</h2>

<pre>
<link rel="stylesheet" href="/UITools/dictionary/assets/css/dictionary.css">
<link rel="stylesheet" href="/UITools/dictionary/assets/css/dictionary-custom.css">
<script src="/UITools/dictionary/dictionary-loader.js" defer></script>
</pre>

    <script src="<?= DICT_BASE_URL ?>dictionary-loader.js" defer></script>

</body>
</html>
