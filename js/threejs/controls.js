var Controls = (function(Controls) {
  "use strict";

  // Check for double inclusion
  if (Controls.addMouseHandler)
  return Controls;

  Controls.addMouseHandler = function (domObject, drag, zoomIn, zoomOut) {
    var startDragX = null,
    startDragY = null;

    function mouseWheelHandler(e) {
      e = window.event || e;
      var delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));

      if (delta < 0 && zoomOut) {
        zoomOut(delta);
      } else if (zoomIn) {
        zoomIn(delta);
      }

      e.preventDefault();
    }

    function mouseDownHandler(e) {
      startDragX = e.clientX;
      startDragY = e.clientY;

      e.preventDefault();
    }

    function mouseMoveHandler(e) {
      if (startDragX === null || startDragY === null)
      return;
      
      if (drag)
      drag(e.clientX - startDragX, e.clientY - startDragY);

      startDragX = e.clientX;
      startDragY = e.clientY;

      e.preventDefault();
    }

    function mouseUpHandler(e) {
      mouseMoveHandler.call(this, e);
      startDragX = null;
      startDragY = null;

      e.preventDefault();
    }

    domObject.addEventListener("mousewheel", mouseWheelHandler);
    domObject.addEventListener("DOMMouseScroll", mouseWheelHandler);
    domObject.addEventListener("mousedown", mouseDownHandler);
    domObject.addEventListener("mousemove", mouseMoveHandler);
    domObject.addEventListener("mouseup", mouseUpHandler);
  };
  return Controls;
}(Controls || {}));
