/*
    Cross-Browser Tooltip von Mathias Karst?dt steht unter einer Creative Commons Namensnennung 3.0 Unported Lizenz.
    http://webmatze.de/ein-einfacher-cross-browser-tooltip-mit-javascript-und-css/
*/
(function(window, document, undefined){
    var XBTooltip = function(element, userConf, tooltip) {
        var config = {
            id: userConf.id || undefined,
            className: userConf.className || undefined,
            x: userConf.x || 20,
            y: userConf.y || 20,
            text: userConf.text || undefined
        };
        var over = function(event) {
            tooltip.style.display = "block";
        },
        out = function(event) {
            tooltip.style.display = "none";
        },
        move = function(event) {
            event = event ? event : window.event;
            if ( event.pageX == null && event.clientX != null ) {
                var doc = document.documentElement, body = document.body;
                event.pageX = event.clientX + (doc && doc.scrollLeft || body && body.scrollLeft || 0) - (doc && doc.clientLeft || body && body.clientLeft || 0);
                event.pageY = event.clientY + (doc && doc.scrollTop  || body && body.scrollTop  || 0) - (doc && doc.clientTop  || body && body.clientTop  || 0);
            }
            tooltip.style.top = (event.pageY+config.y) + "px";
            tooltip.style.left = (event.pageX+config.x) + "px";
        }
        if (tooltip === undefined && config.id) {
            tooltip = document.getElementById(config.id);
            if (tooltip) tooltip = tooltip.parentNode.removeChild(tooltip)
        }
        if (tooltip === undefined && config.text) {
            tooltip = document.createElement("div");
            if (config.id) tooltip.id= config.id;
            tooltip.innerHTML = config.text;
        }
        if (config.className) tooltip.className = config.className;
        tooltip = document.body.appendChild(tooltip);
        tooltip.style.position = "absolute";
        tooltip.style.zIndex = "999";
        element.onmouseover = over;
        element.onmouseout = out;
        element.onmousemove = move;
        over();
    };
    window.XBTooltip = window.XBT = XBTooltip;
})(this, this.document);