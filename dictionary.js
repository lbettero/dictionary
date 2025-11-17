// dictionary.js – UNIVERSAL VERSION WITH SCOPE SUPPORT
(async function() {

    //----------------------------------------------------------------------
    // 1. GET JSON LOCATION
    //----------------------------------------------------------------------
    const DICT_URL = window.DICT_URL || 'dictionary.json';

    async function loadDictionary() {
        try {
            const res = await fetch(DICT_URL);

            if (!res.ok) {
                console.error("Dictionary module: error loading dictionary.json at:", DICT_URL);
                return {};
            }

            return await res.json();

        } catch (err) {
            console.error("Dictionary module: fetch failed:", err);
            return {};
        }
    }

    //----------------------------------------------------------------------
    // 2. TURN URLS AND EMAILS INTO LINKS
    //----------------------------------------------------------------------
    function linkify(text) {
        if (!text) return text;

        // URL pattern
        const urlPattern = /\b((https?:\/\/|www\.)[^\s]+)/gi;
        text = text.replace(urlPattern, match => {
            let url = match;
            if (!match.startsWith('http')) {
                url = 'http://' + match;
            }
            return `<a href="${url}" target="_blank" rel="noopener noreferrer">${match}</a>`;
        });

        // Email pattern
        const emailPattern = /\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z]{2,}\b/gi;
        text = text.replace(emailPattern, email => {
            return `<a href="mailto:${email}">${email}</a>`;
        });

        return text;
    }

    //----------------------------------------------------------------------
    // 3. HIGHLIGHT TERMS INSIDE A GIVEN SCOPE
    //----------------------------------------------------------------------
    function highlightTerms(dict, scopeElement) {
        if (!dict || Object.keys(dict).length === 0) return;

        // Sort terms longest-first → avoids partial matches
        const terms = Object.keys(dict)
            .sort((a, b) => b.length - a.length)
            .map(t => t.replace(/[.*+?^${}()|[\]\\]/g, "\\$&")); // regex escape

        if (terms.length === 0) return;

        const regex = new RegExp(`\\b(${terms.join("|")})\\b`, "gi");

        function walk(node) {

            // Process only text nodes
            if (node.nodeType === Node.TEXT_NODE) {
                const text = node.textContent;

                if (regex.test(text)) {
                    const span = document.createElement("span");

                    span.innerHTML = text.replace(regex, match => {
                        const key = Object.keys(dict).find(
                            k => k.toLowerCase() === match.toLowerCase()
                        );

                        const definition = linkify(dict[key]);

                        return `
                            <span class="dict-term">
                                ${match}
                                <span class="dict-tooltip">${definition}</span>
                            </span>
                        `;
                    });

                    node.replaceWith(...span.childNodes);
                }

            } else if (node.nodeType === Node.ELEMENT_NODE) {

                const forbidden = ["SCRIPT", "STYLE", "CODE", "PRE"];
                if (!forbidden.includes(node.tagName)) {
                    Array.from(node.childNodes).forEach(walk);
                }
            }
        }

        walk(scopeElement);
    }

    //----------------------------------------------------------------------
    // 4. DETERMINE SCOPE (NEW!)
    //----------------------------------------------------------------------
    const scopeSelector = window.DICT_SCOPE_SELECTOR || null;

    let scopeElement = null;

    if (scopeSelector) {
        // If selector provided, try to find the element
        scopeElement = document.querySelector(scopeSelector);

        if (!scopeElement) {
            console.warn(
                "Dictionary module: DICT_SCOPE_SELECTOR was set but no element matched:",
                scopeSelector,
                "\nFallback to document.body"
            );
            scopeElement = document.body;
        }
    } else {
        // Default behavior
        scopeElement = document.body;
    }

    //----------------------------------------------------------------------
    // 5. LOAD & APPLY DICTIONARY
    //----------------------------------------------------------------------
    const dictionary = await loadDictionary();
    highlightTerms(dictionary, scopeElement);

})();
