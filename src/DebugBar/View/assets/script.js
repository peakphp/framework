/* Peak DebugBar */
/* ie 10+ */
var peakDebugBar = (function() {
    "use strict";

    var bar = document.querySelector("#pkdebugbar");
    var bar_toggle = bar.querySelector(".pkdebugbar-toggle");
    var tabs = document.querySelectorAll(".pkdebugbar-tab");
    var windows = document.querySelector("#pkdebugbar-windows");

    // show the bar
    document.addEventListener("DOMContentLoaded", function() {
        bar.style.display = "block";
    });

    // toogle bar visibility
    bar_toggle.addEventListener("click", function() {
        toggleBar();
    });

    function forEach(els, callback) {
        Array.prototype.forEach.call(els, callback);
    }

    function toggleBar() {
        bar.classList.toggle("collapse");
        if (bar.classList.contains("collapse")) {
            var windows_el = windows.querySelectorAll(".pkdebugbar-window");
            forEach(windows_el, function(el, i) {
                el.classList.remove("open");
            });
            forEach(tabs, function(el, i) {
                el.classList.remove("open");
            });
        }
    }

    function targetName(el) {
        return el.getAttribute("data-target");
    }

    function targetElFromName(name) {
        return windows.querySelector(".pkdebugbar-window-" + name);
    }

    function targetEl(el) {
        return targetElFromName(targetName(el));
    }

    function toggleWindow(el) {
        el.classList.toggle('open');
        var content_el = windows.querySelector('.pkdebugbar-window-' + targetName(el));
        content_el.classList.toggle('open');
    }



    forEach(tabs, function(el, i) {
        var current_tab = el;
        var content_el = targetEl(el);
        if (content_el != null) {
            el.addEventListener('click', function() {
                forEach(tabs, function(el, i) {
                    if (el != current_tab && el.classList.contains('open')) {
                        toggleWindow(el);
                    }
                })
                toggleWindow(el);
            })
        }
    });

    return {
        window: function(name) {
            return targetElFromName(name)
        },
        toggle: function() {
            toggleBar();
        }
    }
})();