<?php
// save_dictionary.php  (root)

$dictionaryPath = __DIR__ . '/dictionary.json';

if (!isset($_POST['terms'], $_POST['definitions'])) {
    die("Invalid data.");
}

$terms = $_POST['terms'];
$definitions = $_POST['definitions'];

$newDict = [];

// Build associative array
for ($i = 0; $i < count($terms); $i++) {
    $term = trim($terms[$i]);
    $def = trim($definitions[$i]);

    if ($term !== '') {
        $newDict[$term] = $def;
    }
}

// Sort alphabetically
ksort($newDict, SORT_NATURAL | SORT_FLAG_CASE);

// Save JSON
file_put_contents($dictionaryPath, json_encode($newDict, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dictionary Saved</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        button { padding: 8px 16px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Dictionary Saved Successfully</h2>

    <p>The dictionary.json file has been updated.</p>

    <p>
        <a href="dictionary.json" download>
            <button>⬇ Download Updated JSON</button>
        </a>
    </p>

    <p>
        <a href="dictionary-editor.php">← Back to Editor</a>
    </p>

    <p>
        <a href="index.html">← Home</a>
    </p>
</body>
</html>
