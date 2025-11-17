# 📘 Dictionary Module — v1.0.0

A lightweight, plug-and-play tooltip dictionary that automatically detects predefined terms inside any webpage, highlights them with a dashed underline, and displays clean tooltips containing their definitions.  
All terms and definitions are loaded from a simple JSON file (`dictionary.json`), making the module fully portable and easy to integrate into any PHP, JS, or static website.

---

## 🚀 Features

- **Automatic term detection**  
  Scans the page and highlights any term found in the dictionary JSON.

- **Clean tooltip UI**  
  Modern, compact tooltips rendered with pure CSS + JavaScript.

- **Dashed underline highlight**  
  Clearly marks terms with a subtle dashed underline.

- **JSON-based dictionary**  
  Very easy to edit and maintain (`assets/data/dictionary.json`).

- **HTML support in definitions**  
  URLs and emails inside definitions are automatically transformed into clickable links.

- **Style inheritance**  
  The tooltip automatically inherits typography and global colors from the website’s main stylesheet.

- **Framework-free**  
  Works with plain HTML, PHP, or any frontend framework.

---

## 📁 Project Structure

```
dictionary/
│
├── index.php                     ← demo page
│
├── dictionary-loader.js          ← auto-initializer
├── dictionary.js                 ← core tooltip engine
│
├── assets/
│   ├── css/
│   │   ├── dictionary.css        ← base tooltip styles
│   │   └── dictionary-custom.css ← optional overrides
│   │
│   └── data/
│       └── dictionary.json       ← term definitions (editable)
│
├── content_editor.png            ← old editor preview (for documentation)
└── style_editor.png              ← old style editor preview
```

> 📝 *The legacy JSON and CSS editors were intentionally removed for security and simplicity.*

---

## 🧩 How It Works

1. The loader reads `dictionary.json`.
2. It scans the DOM for terms defined in the JSON file.
3. Each occurrence is automatically wrapped as:

```html
<span class="dict-term">
    term
    <span class="dict-tooltip">definition</span>
</span>
```

4. Tooltips become visible on hover, styled by:

- `assets/css/dictionary.css`  
- `assets/css/dictionary-custom.css` (optional)

---

## 🛠 Installation

Copy the entire `/dictionary` folder into your project under:

```
/UITools/dictionary/
```

Then include the module in your global **header**:

### **With automatic style detection (recommended)**

```php
<?php
$siteStyle = "";

$mainCss = $_SERVER['DOCUMENT_ROOT'] . "/assets/css/main.css";
$rootCss = $_SERVER['DOCUMENT_ROOT'] . "/style.css";

if (file_exists($mainCss)) {
    $siteStyle = "/assets/css/main.css";
} elseif (file_exists($rootCss)) {
    $siteStyle = "/style.css";
}
?>

<?php if ($siteStyle): ?>
<link rel="stylesheet" href="<?= $siteStyle ?>">
<?php endif; ?>

<link rel="stylesheet" href="/UITools/dictionary/assets/css/dictionary.css">
<link rel="stylesheet" href="/UITools/dictionary/assets/css/dictionary-custom.css">
<script src="/UITools/dictionary/dictionary-loader.js" defer></script>
```

### **Mark any content area for scanning**

```html
<div data-dictionary-scope="true">
    The Archaeology field depends on Interoperability.
</div>
```

---

## 📚 Editing the Dictionary

You can edit the dictionary manually in:

```
UITools/dictionary/assets/data/dictionary.json
```

Example structure:

```json
{
    "Archaeology": "Study of human history through material remains.",
    "Interoperability": "The ability of systems to exchange and use information.",
    "Cultural Heritage": "Legacy of physical artifacts and intangible attributes inherited from past generations."
}
```

---

## 🎨 Customizing the Tooltip UI

Optional overrides can be added inside:

```
UITools/dictionary/assets/css/dictionary-custom.css
```

This file can control:

- Colors  
- Padding  
- Border radius  
- Tooltip width  
- Font size  

If removed, the module falls back to default styling.

---

## 🧪 Demo Page

The included `index.php` contains:

- A live embedded example  
- A sample JSON dictionary  
- Documentation and preview images  

This page is safe to include inside any website during testing.

---

## 🔒 Requirements

- Works on **any server** (PHP not required unless editing JSON manually)  
- Modern browser with ES6 support  

---

## 📦 Version

**v1.0.0 — Initial stable release**

Includes:

- Automatic term detection system  
- Tooltip engine  
- JSON dictionary  
- Auto-linking of URLs/emails  
- Default and customizable styling  
- Demo page  
- Preview illustrations of the deprecated editors  

---

## 📄 License

MIT License — free for personal and commercial use.

---

## 🤝 Contributing

Pull requests and issues are welcome.  
If you want to improve the tooltip engine, dictionary parser, or loader logic, feel free to contribute.
