<?php
$cssPath = __DIR__ . '/dictionary-custom.css';

// Default values
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

// Load and parse existing CSS
if (file_exists($cssPath)) {
    $cssContent = file_get_contents($cssPath);

    // Extract color and numerical values from the CSS
    foreach ($defaults as $key => $default) {
        switch ($key) {
            case "bg_color":
                if (preg_match('/background:\s*([^;!]+)!?/', $cssContent, $m)) {
                    $values[$key] = trim($m[1]);
                }
                break;

            case "text_color":
                if (preg_match('/color:\s*([^;!]+)!?/', $cssContent, $m)) {
                    $values[$key] = trim($m[1]);
                }
                break;

            case "underline_color":
                if (preg_match('/border-bottom:\s*1px dashed ([^;!]+)!?/', $cssContent, $m)) {
                    $values[$key] = trim($m[1]);
                }
                break;

            case "width":
                if (preg_match('/max-width:\s*([0-9]+)px/', $cssContent, $m)) {
                    $values[$key] = $m[1];
                }
                break;

            case "padding":
                if (preg_match('/padding:\s*([0-9]+)px/', $cssContent, $m)) {
                    $values[$key] = $m[1];
                }
                break;

            case "radius":
                if (preg_match('/border-radius:\s*([0-9]+)px/', $cssContent, $m)) {
                    $values[$key] = $m[1];
                }
                break;

            case "font_size":
                if (preg_match('/font-size:\s*([0-9]+)px/', $cssContent, $m)) {
                    $values[$key] = $m[1];
                }
                break;
        }
    }
} else {
    $cssContent = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dictionary – CSS Style Editor</title>

    <style>
        body {
            font-family: Arial;
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
        }
        h1 { margin-bottom: 20px; }
        label { font-weight: bold; margin-top: 10px; display: block; }
        input, select {
            width: 250px;
            padding: 6px;
            margin-top: 5px;
            margin-bottom: 10px;
        }
        textarea {
            width: 100%;
            height: 180px;
            padding: 10px;
            margin-top: 10px;
        }
        button {
            padding: 10px 18px;
            margin-top: 20px;
            cursor: pointer;
        }
        .preview-box {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-top: 20px;
        }
    </style>
</head>

<body>

<h1>Dictionary CSS Style Editor</h1>

<p>Customize the tooltip style for the Dictionary module. The generated CSS will be saved in <code>dictionary-custom.css</code>.</p>

<form action="save_dictionary_css.php" method="POST">

    <label>Tooltip Background Color</label>
    <input type="color" name="bg_color" value="<?= htmlspecialchars($values['bg_color']) ?>">

    <label>Tooltip Text Color</label>
    <input type="color" name="text_color" value="<?= htmlspecialchars($values['text_color']) ?>">

    <label>Underline Color (Term)</label>
    <input type="color" name="underline_color" value="<?= htmlspecialchars($values['underline_color']) ?>">

    <label>Tooltip Width (px)</label>
    <input type="number" name="width" value="<?= htmlspecialchars($values['width']) ?>">

    <label>Tooltip Padding (px)</label>
    <input type="number" name="padding" value="<?= htmlspecialchars($values['padding']) ?>">

    <label>Border Radius (px)</label>
    <input type="number" name="radius" value="<?= htmlspecialchars($values['radius']) ?>">

    <label>Font Size (px)</label>
    <input type="number" name="font_size" value="<?= htmlspecialchars($values['font_size']) ?>">

    <label>Current dictionary-custom.css content (read-only)</label>
    <textarea readonly><?= htmlspecialchars($cssContent) ?></textarea>

    <button type="submit">Save Custom CSS</button>
</form>

<div class="preview-box">
    <strong>Preview:</strong>
    <p>
        This is a preview of the tooltip styling.  
        Hover the term <span style="border-bottom:1px dashed #555;">example</span> in your actual pages to see the effect.
    </p>
</div>

<p style="margin-top: 25px;">
    <a href="index.html">← Back to main page</a>
</p>

</body>
</html>
