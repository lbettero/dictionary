<?php
// dictionary-editor.php

// Detecta automaticamente se estamos no módulo (demo) ou no root
$localDictionaryPath = __DIR__ . '/dictionary.json';           // caso 1: módulo embutível
$rootDictionaryPath  = __DIR__ . '/assets/data/dictionary.json'; // caso 2: root do site

$isModuleDemo = false;

// Se existir um dictionary.json na MESMA pasta, usamos ele (modo módulo/demo)
if (file_exists($localDictionaryPath)) {
    $dictionaryPath = $localDictionaryPath;
    $isModuleDemo   = true;
    // No módulo, o script de gravação costuma ficar com underscore:
    $formAction = 'save_dictionary.php';
} else {
    // Senão, caímos no dicionário global do site
    $dictionaryPath = $rootDictionaryPath;
    $isModuleDemo   = false;
    // No root, você usava save-dictionary.php (dash)
    $formAction = 'save-dictionary.php';
}

$dictionaryData = [];
$error = null;

// Load dictionary.json
if (file_exists($dictionaryPath)) {
    $json = file_get_contents($dictionaryPath);
    $dictionaryData = json_decode($json, true);

    if (!is_array($dictionaryData)) {
        $error = "Error decoding dictionary.json.";
        $dictionaryData = [];
    }
} else {
    $error = "dictionary.json file not found.";
}

?>

<div class="p-4">

    <h2 class="text-xl font-bold mb-4">Dictionary Editor</h2>

    <?php if ($error): ?>
        <p class="text-red-600 font-semibold mb-4"><?= htmlspecialchars($error) ?></p>
    <?php else: ?>
        <p class="text-sm text-gray-600 mb-2">
            Editing file:
            <code class="text-xs break-all"><?= htmlspecialchars($dictionaryPath) ?></code>
        </p>
    <?php endif; ?>

    <form action="<?= htmlspecialchars($formAction) ?>" method="POST">

        <div id="dictionary-list" class="space-y-4">

            <?php if (!empty($dictionaryData)): ?>
                <?php foreach ($dictionaryData as $term => $definition): ?>
                    <div class="p-4 border rounded bg-gray-50 flex flex-col gap-2 dictionary-item">

                        <div>
                            <label class="block text-sm font-medium">Term</label>
                            <input 
                                type="text" 
                                name="terms[]" 
                                class="w-full p-2 border rounded" 
                                value="<?= htmlspecialchars($term) ?>"
                                required
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Definition</label>
                            <textarea 
                                name="definitions[]" 
                                class="w-full p-2 border rounded h-24"
                                required
                            ><?= htmlspecialchars($definition) ?></textarea>
                        </div>

                        <button 
                            type="button"
                            class="remove-item bg-red-600 text-white px-3 py-1 rounded text-sm self-start"
                        >
                            Remove
                        </button>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>

        <button 
            type="button"
            id="add-item"
            class="mt-4 bg-blue-600 text-white px-4 py-2 rounded"
        >
            Add New Term
        </button>

        <button 
            type="submit"
            class="mt-4 bg-green-600 text-white px-6 py-2 rounded"
        >
            Save Dictionary
        </button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const list = document.getElementById("dictionary-list");
    const addBtn = document.getElementById("add-item");

    addBtn.addEventListener("click", () => {
        const block = document.createElement("div");
        block.className = "p-4 border rounded bg-gray-50 flex flex-col gap-2 dictionary-item";

        block.innerHTML = `
            <div>
                <label class="block text-sm font-medium">Term</label>
                <input type="text" name="terms[]" class="w-full p-2 border rounded" placeholder="New term" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Definition</label>
                <textarea name="definitions[]" class="w-full p-2 border rounded h-24" placeholder="Definition" required></textarea>
            </div>

            <button type="button" class="remove-item bg-red-600 text-white px-3 py-1 rounded text-sm self-start">
                Remove
            </button>
        `;

        list.appendChild(block);
    });

    document.addEventListener("click", (e) => {
        if (e.target.classList.contains("remove-item")) {
            e.target.closest(".dictionary-item").remove();
        }
    });

});
</script>
