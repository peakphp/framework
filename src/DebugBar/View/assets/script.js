/* Peak DebugBar */
/* ie 10+ */
var peakDebugBar = (function() {
    "use strict";

    var bar = document.querySelector("#pkdebugbar");
    var tabs = document.querySelectorAll(".pkdebugbar-tab");

    // show the bar
    document.addEventListener("DOMContentLoaded", function() {
        bar.style.display = "block";
    });

    function targetName(el) {
        return el.getAttribute("data-target");
    }

    function targetElFromName(name) {
        return bar.querySelector(".pkdebugbar-window-" + name);
    }

    function targetEl(el) {
        return targetElFromName(targetName(el));
    }

    function toggleWindow(el) {
        el.classList.toggle('open');
        var content_el = bar.querySelector('.pkdebugbar-window-' + targetName(el));
        content_el.classList.toggle('open');
    }

    Array.prototype.forEach.call(tabs, function(el, i) {
        var current_tab = el;
        var content_el = targetEl(el);

        if (content_el != null) {
            el.addEventListener('click', function() {
                Array.prototype.forEach.call(tabs, function(el, i) {
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
        }
    }
})();