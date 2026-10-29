// Loads the dictionary module only from the same installation as this file.
(function () {
    'use strict';

    const loaderUrl = document.currentScript && document.currentScript.src
        ? document.currentScript.src
        : document.baseURI;
    const moduleBase = new URL('./', loaderUrl);

    const config = {
        css: [
            new URL('assets/css/dictionary.css', moduleBase).href,
            new URL('assets/css/dictionary-custom.css', moduleBase).href
        ],
        js: new URL('dictionary.js', moduleBase).href,
        json: new URL('assets/data/dictionary.json', moduleBase).href
    };

    function loadCss(url) {
        const alreadyLoaded = Array.from(document.querySelectorAll('link[rel="stylesheet"]'))
            .some((link) => link.href === url);
        if (alreadyLoaded) {
            return;
        }
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = url;
        document.head.appendChild(link);
    }

    function loadScript(url) {
        const script = document.createElement('script');
        script.src = url;
        script.defer = true;
        document.body.appendChild(script);
    }

    config.css.forEach(loadCss);
    window.DICT_URL = config.json;
    loadScript(config.js);
}());
