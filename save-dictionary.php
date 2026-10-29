<?php
declare(strict_types=1);

require_once __DIR__ . '/security.php';
dictionary_require_secure_post();

$terms = $_POST['terms'] ?? null;
$definitions = $_POST['definitions'] ?? null;
if (!is_array($terms) || !is_array($definitions) || count($terms) !== count($definitions)) {
    dictionary_fail(422, 'Terms and definitions must be matching arrays.');
}
if (count($terms) > DICTIONARY_MAX_ENTRIES) {
    dictionary_fail(422, 'The dictionary contains too many entries.');
}

$newDictionary = [];
foreach ($terms as $index => $rawTerm) {
    $rawDefinition = $definitions[$index] ?? null;
    if (!is_string($rawTerm) || !is_string($rawDefinition)) {
        dictionary_fail(422, 'Every term and definition must be text.');
    }

    $term = trim($rawTerm);
    $definition = trim($rawDefinition);
    if ($term === '') {
        continue;
    }
    if (dictionary_string_length($term) > DICTIONARY_MAX_TERM_LENGTH) {
        dictionary_fail(422, 'A term is too long.');
    }
    if (dictionary_string_length($definition) > DICTIONARY_MAX_DEFINITION_LENGTH) {
        dictionary_fail(422, 'A definition is too long.');
    }
    $newDictionary[$term] = $definition;
}

ksort($newDictionary, SORT_NATURAL | SORT_FLAG_CASE);
try {
    $json = json_encode($newDictionary, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
} catch (JsonException) {
    dictionary_fail(422, 'The dictionary contains invalid text encoding.');
}

dictionary_atomic_write(__DIR__ . '/assets/data/dictionary.json', $json . PHP_EOL);
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dictionary Saved</title>
</head>
<body>
    <h1>Dictionary saved successfully.</h1>
    <p><a href="dictionary-editor.php">Return to the editor</a></p>
</body>
</html>
