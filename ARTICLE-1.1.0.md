# Dictionary Module 1.1.0: safer tooltip dictionaries for websites

Dictionary Module is a lightweight component maintained by **LBETTERO** that finds predefined terms on a web page and displays their definitions in compact tooltips. It is designed for documentation, cultural projects, educational websites, and other content where readers may need quick explanations without leaving the page.

Terms and definitions live in a simple JSON file. A site adds the local loader, marks the content area that should be scanned, and the module highlights matching terms automatically. Plain-text web addresses and email addresses inside definitions remain clickable.

## What changes in version 1.1.0

Version 1.1.0 keeps the existing tooltip workflow while strengthening the administrative and browser-facing parts of the project:

- optional content and style editors are disabled by default;
- administrative writes require configured credentials, a valid session token, and a same-origin request;
- dictionary size, entry count, text length, colors, and numeric style values are validated;
- JSON and CSS files are replaced atomically to reduce the risk of partial writes;
- definitions are rendered as text through safe DOM APIs instead of being interpreted as HTML;
- generated links accept only the supported web and email formats;
- runtime JavaScript, CSS, and dictionary data are loaded from the local installation rather than a mutable remote source;
- Content Security Policy and related browser security headers are included;
- deployment and vulnerability-reporting guidance is available in `SECURITY.md`.

The original security findings were retested locally. Anonymous access, missing session tokens, and incorrect origins were rejected without changing stored files. Invalid CSS was also rejected, while authorized dictionary and style updates continued to work.

## Protected editing

The public tooltip component does not need the PHP editors. A normal installation can leave them disabled and serve only the local JavaScript, CSS, and JSON files.

Administrators who need browser-based editing must explicitly enable it and provide credentials through server environment variables. The editor should only be deployed over HTTPS, and credentials must never be committed to the repository.

This design keeps the public reading experience simple while separating it from operations that modify stored content.

## Requirements

For the public tooltip module:

- a modern browser with ES6 support;
- a web server capable of serving JavaScript, CSS, and JSON files;
- no PHP runtime when the JSON and CSS files are maintained outside the browser editors.

For the optional protected editors:

- PHP 8.1 or newer;
- HTTPS in production;
- server environment variables for the editor switch and administrative credentials;
- write permission limited to the dictionary JSON and custom CSS files.

## Availability

Dictionary Module is an open-source project maintained by **LBETTERO**.

- [Project repository](https://github.com/lbettero/dictionary)
- [Dictionary Module 1.1.0 release](https://github.com/lbettero/dictionary/releases/tag/1.1.0)
- [Security policy](https://github.com/lbettero/dictionary/blob/main/SECURITY.md)
