# 📘 Dictionary Module — v1.1.0

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

- **Safe links in definitions**
  Plain-text URLs and emails are transformed into links without interpreting definitions as HTML.

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

> The optional JSON and CSS editors are disabled by default and require explicit credentials and CSRF protection.

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

### Optional protected editors

The browser editors and their save endpoints return `403` by default. Enable them only in a trusted administrative environment by defining all three server environment variables:

```text
DICTIONARY_EDITOR_ENABLED=1
DICTIONARY_ADMIN_USER=<administrator name>
DICTIONARY_ADMIN_PASSWORD=<strong unique password>
```

Open `dictionary-editor.php` or `dictionary-style-editor.php` and authenticate with HTTP Basic authentication. Forms use a session-bound CSRF token. The save endpoints accept only same-origin `POST` requests, validate size and values, and replace files atomically.

Do not commit credentials to this repository. Production must use HTTPS so that Basic authentication and the administrative session are encrypted in transit.

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
- PHP 8.1 or newer for the optional protected editors
- HTTPS for any deployed administrative editor

---

## 📦 Version

**v1.1.0 — Security hardening release**

This release keeps the original tooltip workflow and adds:

- protected, disabled-by-default content and style editors;
- authentication, CSRF and same-origin checks for administrative writes;
- strict input limits and atomic file replacement;
- text-only DOM rendering that prevents definitions from becoming executable HTML;
- local, versioned runtime loading instead of mutable remote JavaScript;
- Content Security Policy and secure deployment guidance.

### Version history

- **v1.1.0** — Security hardening and protected optional editors.
- **v1.0.0** — Initial stable tooltip dictionary release.

---

## 📄 License

MIT License — free for personal and commercial use.

---

## 🤝 Contributing

Pull requests and issues are welcome.  
If you want to improve the tooltip engine, dictionary parser, or loader logic, feel free to contribute.
