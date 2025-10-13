//to load theme at the start of page load 
(function() {
    try {
        const saved = localStorage.getItem("theme");
        if (saved) {
             document.documentElement.setAttribute("web-theme", saved);
         } else if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
             document.documentElement.setAttribute("web-theme", "dark");
         }
    } catch (e) {
        console.log("Theme load error", e);
    }
})();