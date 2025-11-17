<?php
// save-dictionary.php — UNIVERSAL VERSION

// -------------------------------------------------------
// 1. DETECT MODE: MODULE DEMO vs SITE INSTALL
// -------------------------------------------------------
$localDictionaryPath = __DIR__ . '/dictionary.json'; // módulo / demo
$rootDictionaryPath  = $_SERVER['DOCUMENT_ROOT'] . '/assets/data/dictionary.json'; // site real

$isModuleDemo = false;

if (file_exists($localDictionaryPath)) {
    // DEMO MODE
    $dictionaryPath = $localDictionaryPath;
    $isModuleDemo   = true;

    $backToEditor = "dictionary-editor.php";
    $backHome     = "index.php";
    $downloadUrl  = "dictionary.json";

} else {
    // SITE MODE
    $dictionaryPath = $rootDictionaryPath;
    $isModuleDemo   = false;

    $backToEditor = "dictionary-editor.php";
    $backHome     = "/"; // ajuste se quiser outra home
    $downloadUrl  = "/assets/data/dictionary.json";
}

// -------------------------------------------------------
// 2. VALIDATE POST
// -------------------------------------------------------
if (!isset($_POST['terms'], $_POST['definitions'])) {
    die("Invalid data.");
}

$terms       = $_POST['terms'];
$definitions = $_POST['definitions'];

$newDict = [];

// -------------------------------------------------------
// 3. BUILD NEW DICTIONARY ARRAY
// -------------------------------------------------------
for ($i = 0; $i < count($terms); $i++) {
    $term = trim($terms[$i]);
    $def  = trim($definitions[$i]);

    if ($term !== '') {
        $newDict[$term] = $def;
    }
}

// Sort alphabetically
ksort($newDict, SORT_NATURAL | SORT_FLAG_CASE);

// Encode as pretty JSON
$json = json_encode($newDict, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

// -------------------------------------------------------
// 4. SAVE
// -------------------------------------------------------
file_put_contents($dictionaryPath, $json);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dictionary Saved</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        button { padding: 8px 16px; cursor: pointer; }
        code { background: #f4f4f4; padding: 2px 4px; border-radius: 4px; }
        a { color: #0066cc; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <h2>Dictionary Saved Successfully!</h2>

</body>
</html>
