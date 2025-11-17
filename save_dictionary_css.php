<?php
// save_dictionary_css.php — UNIVERSAL VERSION

// -----------------------------------------------------
// 1. AUTO-DETECT MODE (DEMO MODULE vs SITE ROOT)
// -----------------------------------------------------
$localCssPath = __DIR__ . '/dictionary-custom.css'; // module demo
$rootCssPath  = $_SERVER['DOCUMENT_ROOT'] . '/assets/css/dictionary-custom.css'; // site install

$isModuleDemo = false;

// Se existe um dictionary-custom.css local → estamos no módulo embutido (DEMO)
if (file_exists($localCssPath)) {
    $cssPath = $localCssPath;
    $isModuleDemo = true;
    $backToEditor = "dictionary-style-editor.php";
    $backHome     = "index.php";
} else {
    // Caso contrário → estamos no site real
    $cssPath = $rootCssPath;
    $isModuleDemo = false;
    $backToEditor = "dictionary-style-editor.php";
    $backHome     = "/"; // ou sua home real
}

// -----------------------------------------------------
// 2. CAPTURE POSTED VALUES
// -----------------------------------------------------
$bg        = $_POST['bg_color']        ?? '#333333';
$text      = $_POST['text_color']      ?? '#ffffff';
$underline = $_POST['underline_color'] ?? '#555555';

$width   = intval($_POST['width']      ?? 280);
$padding = intval($_POST['padding']    ?? 12);
$radius  = intval($_POST['radius']     ?? 6);
$font    = intval($_POST['font_size']  ?? 14);

// -----------------------------------------------------
// 3. BUILD CSS OUTPUT
// -----------------------------------------------------
$css = <<<CSS
/* Auto-generated custom CSS for Dictionary tooltip */

.dict-term {
    border-bottom: 1px dashed $underline;
}

.dict-tooltip {
    background: $bg !important;
    color: $text !important;
    max-width: {$width}px !important;
    padding: {$padding}px !important;
    border-radius: {$radius}px !important;
    font-size: {$font}px !important;
}

CSS;

// -----------------------------------------------------
// 4. SAVE FILE
// -----------------------------------------------------
file_put_contents($cssPath, $css);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CSS Saved</title>
    <style>
        body { font-family: Arial; padding: 30px; }
        code { background: #eee; padding: 4px 8px; border-radius: 4px; }
        a { color: #0066cc; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>

<body>

    <h2>Custom CSS Saved Successfully</h2>

    <p>Your tooltip styles have been applied and saved to:</p>

    <p><code><?= htmlspecialchars($cssPath) ?></code></p>

    <p>
        <a href="<?= htmlspecialchars($backToEditor) ?>">← Back to CSS Editor</a>
    </p>

    <p>
        <a href="<?= htmlspecialchars($backHome) ?>">← Home</a>
    </p>

</body>
</html>
