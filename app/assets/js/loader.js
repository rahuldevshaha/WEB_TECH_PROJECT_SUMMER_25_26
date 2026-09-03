





(function () {
    if (document.getElementById("global_page_loader")) return; 

    
    var style = document.createElement("style");
    style.id = "global_page_loader_style";
    style.textContent =
        "#global_page_loader{position:fixed;inset:0;background:rgba(255,255,255,0.75);" +
        "display:none;align-items:center;justify-content:center;z-index:999999;}" +
        "#global_page_loader.active{display:flex;}" +
        "#global_page_loader .gpl_spinner{width:42px;height:42px;border:4px solid #f3d9d9;" +
        "border-top-color:#ff5252;border-radius:50%;animation:gpl_spin 0.7s linear infinite;}" +
        "@keyframes gpl_spin{to{transform:rotate(360deg);}}";
    document.head.appendChild(style);

    
    var overlay = document.createElement("div");
    overlay.id = "global_page_loader";
    overlay.innerHTML = '<div class="gpl_spinner"></div>';
    document.body.appendChild(overlay);

    function showLoader() {
        overlay.classList.add("active");
    }
    function hideLoader() {
        overlay.classList.remove("active");
    }

    
    
    
    
    
    
    document.addEventListener("click", function (e) {
        if (e.defaultPrevented) return;
        if (e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

        var link = e.target.closest("a[href]");
        if (!link) return;
        if (link.hasAttribute("data-url")) return;
        if (link.target && link.target !== "_self") return;

        var href = link.getAttribute("href");
        if (
            !href ||
            href === "#" ||
            href.indexOf("javascript:") === 0 ||
            href.indexOf("mailto:") === 0 ||
            href.indexOf("tel:") === 0
        ) {
            return;
        }

        showLoader();
    });

    
    
    window.addEventListener("pageshow", function () {
        hideLoader();
    });
})();
