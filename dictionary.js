(async function() {

    const DICT_URL = 'dictionary.json';

    async function loadDictionary() {
        const res = await fetch(DICT_URL);
        if (!res.ok) {
            console.error("Dictionary module: error loading dictionary.json");
            return {};
        }
        return await res.json();
    }

    // Convert URLs and emails to clickable links
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

    function highlightTerms(dict) {
        if (!dict || Object.keys(dict).length === 0) return;

        const terms = Object.keys(dict)
            .sort((a, b) => b.length - a.length)
            .map(t => t.replace(/[.*+?^${}()|[\]\\]/g, "\\$&"));

        if (terms.length === 0) return;

        const regex = new RegExp(`\\b(${terms.join("|")})\\b`, "gi");

        function walk(node) {
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

        walk(document.body);
    }

    const dictionary = await loadDictionary();
    highlightTerms(dictionary);

})();
