



(function () {

    var contentBody = document.getElementById("content_body");
    if (!contentBody) return;

    
    
    contentBody.style.transition = "opacity 0.12s ease-in-out";

    
    
    
    
    
    
    
    var styleCache = {};

    
    
    
    var requestToken = 0;

    
    
    
    var LOADING_DELAY_MS = 180;

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    var CONTENT_SCOPE = "#content_body";

    function splitTopLevelCommas(str) {
        var parts = [];
        var depth = 0;
        var last = 0;
        for (var i = 0; i < str.length; i++) {
            var ch = str[i];
            if (ch === "(" || ch === "[") depth++;
            else if (ch === ")" || ch === "]") depth--;
            else if (ch === "," && depth === 0) {
                parts.push(str.slice(last, i));
                last = i + 1;
            }
        }
        parts.push(str.slice(last));
        return parts;
    }

    function scopeSingleSelector(sel) {
        sel = sel.trim();
        if (!sel) return sel;

        
        
        
        if (
            sel === CONTENT_SCOPE ||
            sel.indexOf(CONTENT_SCOPE + " ") === 0 ||
            sel.indexOf(CONTENT_SCOPE + ">") === 0 ||
            sel.indexOf(CONTENT_SCOPE + "+") === 0 ||
            sel.indexOf(CONTENT_SCOPE + "~") === 0 ||
            sel.indexOf(CONTENT_SCOPE + ":") === 0 ||
            sel.indexOf(CONTENT_SCOPE + ".") === 0 ||
            sel.indexOf(CONTENT_SCOPE + "#") === 0 ||
            sel.indexOf(CONTENT_SCOPE + "[") === 0
        ) {
            return sel;
        }

        
        
        if (sel.charAt(0) === "*") {
            return CONTENT_SCOPE + " " + sel;
        }

        
        
        
        var tagMatch = sel.match(/^(html|body)\b(.*)$/i);
        if (tagMatch) {
            return CONTENT_SCOPE + tagMatch[2];
        }

        
        
        return CONTENT_SCOPE + " " + sel;
    }

    function scopeSelectorList(selectorText) {
        return splitTopLevelCommas(selectorText).map(scopeSingleSelector).join(", ");
    }

    function scopeCssText(cssText) {
        
        
        cssText = cssText.replace(/\/\*[\s\S]*?\*\

        var out = "";
        var i = 0;
        var len = cssText.length;

        while (i < len) {
            var depth = 0;
            var braceIdx = -1;
            var j = i;
            for (; j < len; j++) {
                var c = cssText[j];
                if (c === "(" || c === "[") depth++;
                else if (c === ")" || c === "]") depth--;
                else if (c === "{" && depth === 0) { braceIdx = j; break; }
            }

            if (braceIdx === -1) {
                
                
                out += cssText.slice(i);
                break;
            }

            var prelude = cssText.slice(i, braceIdx).trim();

            
            var blockDepth = 1;
            var k = braceIdx + 1;
            for (; k < len && blockDepth > 0; k++) {
                if (cssText[k] === "{") blockDepth++;
                else if (cssText[k] === "}") blockDepth--;
            }
            var blockEnd = k; 
            var blockContent = cssText.slice(braceIdx + 1, k - 1);

            if (prelude.charAt(0) === "@") {
                var atName = (prelude.match(/^@([a-zA-Z-]+)/) || ["", ""])[1].toLowerCase();
                if (atName === "media" || atName === "supports") {
                    
                    
                    out += prelude + " {" + scopeCssText(blockContent) + "}";
                } else {
                    
                    
                    
                    out += cssText.slice(i, blockEnd);
                }
            } else {
                out += scopeSelectorList(prelude) + " {" + blockContent + "}";
            }

            i = blockEnd;
        }

        return out;
    }

    function sanitizeComponentCss(cssText) {
        return scopeCssText(cssText);
    }

    function ensureScopedCss(href) {
        
        
        
        
        var cached = styleCache[href];
        if (cached) return cached;

        var promise = fetch(href, { credentials: "same-origin" })
            .then(function (res) { return res.text(); })
            .then(function (cssText) { return sanitizeComponentCss(cssText); })
            .catch(function (err) {
                delete styleCache[href]; 
                throw err;
            });

        styleCache[href] = promise;
        return promise;
    }

    function extractStylesheets(container, token) {
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        var hrefs = [];
        container.querySelectorAll('link[rel="stylesheet"]').forEach(function (link) {
            hrefs.push(link.href);
            link.remove();
        });

        document.querySelectorAll('style[data-component-style]').forEach(function (el) {
            el.remove();
        });

        hrefs.forEach(function (href) {
            ensureScopedCss(href).then(function (scopedCss) {
                if (token !== requestToken) return; 
                var styleEl = document.createElement("style");
                styleEl.setAttribute("data-component-style", "1");
                styleEl.setAttribute("data-source", href);
                styleEl.textContent = scopedCss;
                document.head.appendChild(styleEl);
            }).catch(function (err) {
                console.error("Failed to load component stylesheet:", href, err);
            });
        });
    }

    
    
    function runScripts(container) {
        var scripts = container.querySelectorAll("script");
        scripts.forEach(function (oldScript) {
            var newScript = document.createElement("script");
            for (var i = 0; i < oldScript.attributes.length; i++) {
                var attr = oldScript.attributes[i];
                newScript.setAttribute(attr.name, attr.value);
            }
            newScript.textContent = oldScript.textContent;
            oldScript.parentNode.replaceChild(newScript, oldScript);
        });
    }

    function setActiveSidebarItem(el) {
        document.querySelectorAll(".sidebar_item").forEach(function (item) {
            item.classList.remove("active");
        });
        if (el) {
            var sidebarItem = el.closest(".sidebar_item");
            if (sidebarItem) sidebarItem.classList.add("active");
        }
    }

    
    
    function renderInto(html, token) {
        contentBody.style.opacity = "0";

        setTimeout(function () {
            contentBody.innerHTML = html;
            extractStylesheets(contentBody, token);
            runScripts(contentBody);
            bindContentForms();
            contentBody.style.opacity = "1";
        }, 120);
    }

    function bindContentForms() {
        contentBody.querySelectorAll("form").forEach(function (form) {
            if (form.dataset.bound === "1") return;
            form.dataset.bound = "1";

            form.addEventListener("submit", function (e) {
                e.preventDefault();
                var actionUrl = form.getAttribute("action");
                if (!actionUrl) return;

                var formData = new FormData(form);
                var token = ++requestToken;
                var loadingTimer = setTimeout(function () {
                    if (token === requestToken) setLoadingIndicator();
                }, LOADING_DELAY_MS);

                fetch(actionUrl, {
                    method: "POST",
                    body: formData,
                    credentials: "same-origin"
                })
                    .then(function (res) {
                        if (!res.ok) throw new Error("Request failed: " + res.status);
                        return res.text();
                    })
                    .then(function (html) {
                        clearTimeout(loadingTimer);
                        if (token !== requestToken) return; 
                        renderInto(html, token);
                    })
                    .catch(function (err) {
                        clearTimeout(loadingTimer);
                        if (token !== requestToken) return;
                        console.error(err);
                        renderInto('<p class="error_text">কন্টেন্ট লোড করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।</p>', token);
                    });
            });
        });
    }

    function setLoadingIndicator() {
        contentBody.style.opacity = "1";
        contentBody.innerHTML = '<p class="loading_text">লোড হচ্ছে...</p>';
    }

    function loadComponent(url, triggerEl) {
        var token = ++requestToken;
        setActiveSidebarItem(triggerEl);

        var loadingTimer = setTimeout(function () {
            if (token === requestToken) setLoadingIndicator();
        }, LOADING_DELAY_MS);

        fetch(url, { credentials: "same-origin" })
            .then(function (res) {
                if (!res.ok) throw new Error("Request failed: " + res.status);
                return res.text();
            })
            .then(function (html) {
                clearTimeout(loadingTimer);
                if (token !== requestToken) return; 
                renderInto(html, token);
            })
            .catch(function (err) {
                clearTimeout(loadingTimer);
                if (token !== requestToken) return;
                console.error(err);
                renderInto('<p class="error_text">কন্টেন্ট লোড করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।</p>', token);
            });
    }

    function goHome(triggerEl) {
        var token = ++requestToken; 
        setActiveSidebarItem(triggerEl);
        renderInto(defaultContent, token);
    }

    
    
    document.addEventListener("click", function (e) {
        var target = e.target.closest("[data-url]");
        if (!target) return;

        var url = target.getAttribute("data-url");

        if (url === "home") {
            e.preventDefault();
            goHome(target);
            return;
        }

        if (!url) {
            
            e.preventDefault();
            var skipToken = ++requestToken; 
            setActiveSidebarItem(target);
            renderInto('<p class="empty_state">এই ফিচারটি শীঘ্রই আসছে।</p>', skipToken);
            return;
        }

        e.preventDefault();
        loadComponent(url, target);
    });

    bindContentForms();

    
    
    
    
    
    
    
    
    
    extractStylesheets(contentBody, requestToken);
    var defaultContent = contentBody.innerHTML;

})();
