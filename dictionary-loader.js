// dictionary-loader.js
(function() {

    // --------------------------------------------
    // AUTO-DETECT MODE (DEMO or EMBED)
    // --------------------------------------------
    const IS_DEMO = window.DICT_DEMO_MODE === true;

    // --------------------------------------------
    // CONFIGS FOR DEMO MODE
    // (paths come from index.php)
    // --------------------------------------------
    const DEMO_BASE = window.DICT_BASE_URL || "./";

    const DEMO = {
        css: [
            DEMO_BASE + "dictionary.css",
            DEMO_BASE + "dictionary-custom.css"
        ],
        js:  DEMO_BASE + "dictionary.js",
        json: DEMO_BASE + "dictionary.json"
    };

    // --------------------------------------------
    // CONFIGS FOR EMBED MODE (default)
    // --------------------------------------------
    const EMBED_BASE = "https://smartsmallthings.com/UITools/dictionary";

    const EMBED = {
        css: [
            EMBED_BASE + "/dictionary.css",
            "/assets/css/dictionary-custom.css"
        ],
        js: EMBED_BASE + "/dictionary.js",
        json: "/assets/data/dictionary.json"
    };

    // --------------------------------------------
    // FINAL CONFIG CHOSEN BASED ON MODE
    // --------------------------------------------
    const CFG = IS_DEMO ? DEMO : EMBED;

    // --------------------------------------------
    // CSS LOADER
    // --------------------------------------------
    function loadCSS(url) {
        const link = document.createElement("link");
        link.rel = "stylesheet";
        link.href = url;
        document.head.appendChild(link);
    }

    CFG.css.forEach(loadCSS);

    // --------------------------------------------
    // JS LOADER
    // --------------------------------------------
    function loadJS(url, callback) {
        const script = document.createElement("script");
        script.src = url;
        script.defer = true;
        if (callback) script.onload = callback;
        document.body.appendChild(script);
    }

    // --------------------------------------------
    // EXPOSE JSON URL GLOBALLY
    // --------------------------------------------
    window.DICT_URL = CFG.json;

    // --------------------------------------------
    // LOAD MAIN ENGINE
    // --------------------------------------------
    loadJS(CFG.js);

})();
