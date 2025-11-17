<?php
// dictionary-editor.php  (root)

$dictionaryPath = __DIR__ . '/dictionary.json';

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
    <?php endif; ?>

    <form action="save-dictionary.php" method="POST">

        <div id="dictionary-list" class="space-y-4">

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
