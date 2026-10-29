// Dictionary tooltip engine with scoped, text-only rendering.
(async function () {
    'use strict';

    const MAX_DICTIONARY_BYTES = 1048576;
    const MAX_ENTRIES = 2000;
    const MAX_TERM_LENGTH = 200;
    const MAX_DEFINITION_LENGTH = 5000;
    const dictionaryUrl = window.DICT_URL || 'assets/data/dictionary.json';

    async function loadDictionary() {
        try {
            const response = await fetch(dictionaryUrl, {
                credentials: 'same-origin',
                headers: { Accept: 'application/json' }
            });
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const contentLength = Number(response.headers.get('content-length') || 0);
            if (contentLength > MAX_DICTIONARY_BYTES) {
                throw new Error('Dictionary is too large');
            }

            const raw = await response.text();
            if (new Blob([raw]).size > MAX_DICTIONARY_BYTES) {
                throw new Error('Dictionary is too large');
            }

            const parsed = JSON.parse(raw);
            if (!parsed || Array.isArray(parsed) || typeof parsed !== 'object') {
                throw new Error('Dictionary must be an object');
            }

            const entries = Object.entries(parsed);
            if (entries.length > MAX_ENTRIES) {
                throw new Error('Dictionary has too many entries');
            }

            const safeDictionary = {};
            for (const [term, definition] of entries) {
                if (typeof definition !== 'string' || term.length > MAX_TERM_LENGTH || definition.length > MAX_DEFINITION_LENGTH) {
                    throw new Error('Dictionary contains an invalid entry');
                }
                safeDictionary[term] = definition;
            }
            return safeDictionary;
        } catch (error) {
            console.error('Dictionary module: could not load the dictionary.', error);
            return {};
        }
    }

    function appendDefinition(container, definition) {
        const pattern = /\b((?:https?:\/\/|www\.)[^\s]+|[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,})\b/gi;
        let lastIndex = 0;

        for (const match of definition.matchAll(pattern)) {
            container.appendChild(document.createTextNode(definition.slice(lastIndex, match.index)));
            const value = match[0];
            const link = document.createElement('a');
            link.textContent = value;

            if (value.includes('@') && !value.toLowerCase().startsWith('http')) {
                link.href = `mailto:${value}`;
            } else {
                try {
                    const url = new URL(value.startsWith('www.') ? `https://${value}` : value);
                    if (!['http:', 'https:'].includes(url.protocol)) {
                        throw new Error('Unsupported protocol');
                    }
                    link.href = url.href;
                    link.target = '_blank';
                    link.rel = 'noopener noreferrer';
                } catch {
                    container.appendChild(document.createTextNode(value));
                    lastIndex = match.index + value.length;
                    continue;
                }
            }

            container.appendChild(link);
            lastIndex = match.index + value.length;
        }
        container.appendChild(document.createTextNode(definition.slice(lastIndex)));
    }

    function highlightTerms(dictionary, scopeElement) {
        const keys = Object.keys(dictionary);
        if (keys.length === 0) {
            return;
        }

        const escapedTerms = keys
            .sort((a, b) => b.length - a.length)
            .map((term) => term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'));
        const regex = new RegExp(`\\b(${escapedTerms.join('|')})\\b`, 'gi');
        const lookup = new Map(keys.map((key) => [key.toLocaleLowerCase(), key]));

        function walk(node) {
            if (node.nodeType === Node.TEXT_NODE) {
                const text = node.textContent || '';
                const matches = Array.from(text.matchAll(regex));
                if (matches.length === 0) {
                    return;
                }

                const fragment = document.createDocumentFragment();
                let lastIndex = 0;
                for (const match of matches) {
                    fragment.appendChild(document.createTextNode(text.slice(lastIndex, match.index)));
                    const originalTerm = match[0];
                    const key = lookup.get(originalTerm.toLocaleLowerCase());
                    if (!key) {
                        fragment.appendChild(document.createTextNode(originalTerm));
                    } else {
                        const term = document.createElement('span');
                        term.className = 'dict-term';
                        term.appendChild(document.createTextNode(originalTerm));

                        const tooltip = document.createElement('span');
                        tooltip.className = 'dict-tooltip';
                        appendDefinition(tooltip, dictionary[key]);
                        term.appendChild(tooltip);
                        fragment.appendChild(term);
                    }
                    lastIndex = match.index + originalTerm.length;
                }
                fragment.appendChild(document.createTextNode(text.slice(lastIndex)));
                node.replaceWith(fragment);
                return;
            }

            if (node.nodeType === Node.ELEMENT_NODE) {
                const forbidden = ['SCRIPT', 'STYLE', 'CODE', 'PRE', 'TEXTAREA', 'INPUT', 'SELECT', 'OPTION'];
                if (!forbidden.includes(node.tagName) && !node.classList.contains('dict-term')) {
                    Array.from(node.childNodes).forEach(walk);
                }
            }
        }

        walk(scopeElement);
    }

    const selector = typeof window.DICT_SCOPE_SELECTOR === 'string' ? window.DICT_SCOPE_SELECTOR : '';
    let scopeElement = document.body;
    if (selector) {
        try {
            scopeElement = document.querySelector(selector) || document.body;
        } catch {
            console.error('Dictionary module: invalid scope selector.');
            return;
        }
    }

    highlightTerms(await loadDictionary(), scopeElement);
}());
