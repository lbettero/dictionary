<?php
declare(strict_types=1);

require_once __DIR__ . '/security.php';
dictionary_require_secure_post();

function require_hex_color(string $field): string
{
    $value = $_POST[$field] ?? null;
    if (!is_string($value) || preg_match('/^#[0-9a-fA-F]{6}$/', $value) !== 1) {
        dictionary_fail(422, 'Invalid color value.');
    }
    return strtolower($value);
}

function require_integer_range(string $field, int $minimum, int $maximum): int
{
    $rawValue = $_POST[$field] ?? null;
    $value = is_string($rawValue) ? filter_var($rawValue, FILTER_VALIDATE_INT) : false;
    if ($value === false || $value < $minimum || $value > $maximum) {
        dictionary_fail(422, 'Invalid numeric value.');
    }
    return $value;
}

$background = require_hex_color('bg_color');
$text = require_hex_color('text_color');
$underline = require_hex_color('underline_color');
$width = require_integer_range('width', 120, 800);
$padding = require_integer_range('padding', 0, 64);
$radius = require_integer_range('radius', 0, 64);
$fontSize = require_integer_range('font_size', 8, 48);

$css = <<<CSS
/* Auto-generated custom CSS for Dictionary tooltip */

.dict-term {
    border-bottom: 1px dashed {$underline};
}

.dict-tooltip {
    background: {$background} !important;
    color: {$text} !important;
    max-width: {$width}px !important;
    padding: {$padding}px !important;
    border-radius: {$radius}px !important;
    font-size: {$fontSize}px !important;
}

CSS;

dictionary_atomic_write(__DIR__ . '/assets/css/dictionary-custom.css', $css);
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dictionary CSS Saved</title>
</head>
<body>
    <h1>Dictionary styles saved successfully.</h1>
    <p><a href="dictionary-style-editor.php">Return to the style editor</a></p>
</body>
</html>
