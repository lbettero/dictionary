<?php
    // public/index.php


$localCssPath = __DIR__ . '/dictionary-custom.css';
$rootCssPath  = $_SERVER['DOCUMENT_ROOT'] . '/assets/css/dictionary-custom.css';

$isModuleDemo = false;

if (file_exists($localCssPath)) {
    $cssPath = $localCssPath;
    $isModuleDemo = true;
    $formAction = "save_dictionary_css.php";
} else {
    $cssPath = $rootCssPath;
    $isModuleDemo = false;
    $formAction = "save_dictionary_css.php";
}

// ---------------------------------------------
// DEFAULTS
// ---------------------------------------------
$defaults = [
    "bg_color"        => "#333333",
    "text_color"      => "#ffffff",
    "underline_color" => "#555555",
    "width"           => "280",
    "padding"         => "12",
    "radius"          => "6",
    "font_size"       => "14"
];

$values = $defaults;
$cssContent = "";

// ---------------------------------------------
// LOAD EXISTING CSS
// ---------------------------------------------
if (file_exists($cssPath)) {
    $cssContent = file_get_contents($cssPath);

    foreach ($defaults as $key => $default) {
        switch ($key) {
            case "bg_color":
                if (preg_match('/background:\s*([^;!]+)!?/', $cssContent, $m)) $values[$key] = trim($m[1]);
                break;

            case "text_color":
                if (preg_match('/color:\s*([^;!]+)!?/', $cssContent, $m)) $values[$key] = trim($m[1]);
                break;

            case "underline_color":
                if (preg_match('/border-bottom:\s*1px dashed ([^;!]+)!?/', $cssContent, $m)) $values[$key] = trim($m[1]);
                break;

            case "width":
                if (preg_match('/max-width:\s*([0-9]+)px/', $cssContent, $m)) $values[$key] = $m[1];
                break;

            case "padding":
                if (preg_match('/padding:\s*([0-9]+)px/', $cssContent, $m)) $values[$key] = $m[1];
                break;

            case "radius":
                if (preg_match('/border-radius:\s*([0-9]+)px/', $cssContent, $m)) $values[$key] = $m[1];
                break;

            case "font_size":
                if (preg_match('/font-size:\s*([0-9]+)px/', $cssContent, $m)) $values[$key] = $m[1];
                break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dictionary – CSS Style Editor</title>
</head>

<body>
<div class="p-4">
    <h2 class="text-xl font-bold mb-4">Dictionary CSS Style Editor</h2>

    <form action="<?= htmlspecialchars($formAction) ?>" method="POST">
        <p>
        <label>Tooltip Background Color</label>
        <input type="color" name="bg_color" value="<?= htmlspecialchars($values['bg_color']) ?>">
        </p>
        <p>
        <label>Tooltip Text Color</label>
        <input type="color" name="text_color" value="<?= htmlspecialchars($values['text_color']) ?>">
        </p>
        <p>
        <label>Underline Color (Term)</label>
        <input type="color" name="underline_color" value="<?= htmlspecialchars($values['underline_color']) ?>">
        </p>
        <p>
        <label>Tooltip Width (px)</label>
        <input type="number" name="width" value="<?= htmlspecialchars($values['width']) ?>">
        </p>
        <p>
        <label>Tooltip Padding (px)</label>
        <input type="number" name="padding" value="<?= htmlspecialchars($values['padding']) ?>">
        </p>
        <p>
        <label>Border Radius (px)</label>
        <input type="number" name="radius" value="<?= htmlspecialchars($values['radius']) ?>">
        </p>
        <p>
        <label>Font Size (px)</label>
        <input type="number" name="font_size" value="<?= htmlspecialchars($values['font_size']) ?>">
        </p>
        <p>
        <button 
                type="submit"
                class="mt-4 bg-green-600 text-white px-6 py-2 rounded"
            >
            Save Custom CSS</button>
        </p>
    </form>
    </body>
    </html>
</div>