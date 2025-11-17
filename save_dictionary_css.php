<?php

$cssPath = __DIR__ . '/dictionary-custom.css';

$bg     = $_POST['bg_color'] ?? '#333333';
$text   = $_POST['text_color'] ?? '#ffffff';
$underline = $_POST['underline_color'] ?? '#555555';

$width  = intval($_POST['width'] ?? 280);
$padding = intval($_POST['padding'] ?? 12);
$radius = intval($_POST['radius'] ?? 6);
$font   = intval($_POST['font_size'] ?? 14);

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

// save file
file_put_contents($cssPath, $css);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CSS Saved</title>
</head>
<body style="font-family:Arial;padding:20px;">
    <h2>Custom CSS Saved Successfully</h2>

    <p>Your tooltip styles have been applied.</p>

    <p>
        <a href="dictionary-style-editor.php">← Back to CSS Editor</a>
    </p>

    <p>
        <a href="index.html">← Home</a>
    </p>
</body>
</html>
