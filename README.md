# 📘 Dictionary Module — v1.0.0

A lightweight, plug-and-play module that automatically detects predefined terms inside any webpage, highlights them with a dashed underline, and displays a clean tooltip containing their definitions on hover.  
All terms and definitions are stored in a simple JSON file (`dictionary.json`), making the module portable, editable, and easy to integrate into any PHP, JS, or static website project.

---

## 🚀 Features

- **Automatic term detection**  
  Scans the entire page and identifies words defined in `dictionary.json`.

- **Clean tooltip UI**  
  Shows a definition box on hover with customizable colors, fonts, and dimensions.

- **Dashed underline highlight**  
  Every recognized term is visually marked in the text.

- **JSON-based dictionary**  
  All definitions are stored in a simple `dictionary.json` file.

- **HTML support in tooltips**  
  Auto-detects URLs and emails inside definitions and converts them into clickable links.

- **CSS customization panel**  
  Includes a built-in editor (`dictionary-style-editor.php`) to customize tooltip design.

- **Dictionary editor**  
  Includes a visual editor (`dictionary-editor.php`) to add, edit, and delete terms.

- **No dependencies**  
  Pure JavaScript + CSS + PHP. Works in any environment.

---

## 📁 Project Structure



dictionary/
│
├── index.html
├── dictionary.js
├── dictionary.css
├── dictionary-custom.css ← generated automatically (optional)
├── dictionary.json
│
├── dictionary-editor.php ← JSON editor
├── save_dictionary.php
│
├── dictionary-style-editor.php ← CSS theme editor
└── save_dictionary_css.php


---

## 🧩 How It Works

1. The script loads `dictionary.json`.
2. It scans the DOM for matches of the defined terms.
3. Each term is wrapped in:
   ```html
   <span class="dict-term">
       term
       <span class="dict-tooltip">definition</span>
   </span>


The tooltip appears on hover, styled by:

dictionary.css

dictionary-custom.css (if present)

🛠 Installation

Just copy the entire folder into any website project.

Include the CSS and JS in your HTML:

<link rel="stylesheet" href="dictionary.css">
<link rel="stylesheet" href="dictionary-custom.css"> <!-- optional -->

<script src="dictionary.js" defer></script>

📚 Editing the Dictionary

Open:

dictionary-editor.php


This visual editor allows you to:

Add new terms

Edit existing terms

Delete terms

Save the updated JSON file (alphabetically sorted)

Download the updated dictionary.json

Definitions may include URLs or emails, which are automatically converted into clickable links in tooltips.

🎨 Customizing the Tooltip UI

Open:

dictionary-style-editor.php


You can customize:

Tooltip background color

Text color

Underline color

Width

Padding

Border radius

Font size

Changes are saved in:

dictionary-custom.css


This file is optional; the module works even if it doesn't exist.

🧪 Demo Page

The included index.html demonstrates how the module behaves with sample content.

🔒 Requirements

PHP 7+ (only for the editors; the core module works without PHP)

Modern browser with ES6 support

📦 Version

v1.0.0 — Initial stable release

Includes:

Term detection and tooltip engine

JSON dictionary system

Dictionary editor

CSS customization editor

Auto-linking for URLs and emails

Demo page

📄 License

MIT License. Free for personal and commercial use.

🤝 Contributing

Feel free to open an issue or submit a pull request with suggestions or improvements.