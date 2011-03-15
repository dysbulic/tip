function get_viewbox() {
  if(document.rootElement.hasAttribute("viewBox")) {
    /* The viewBox specifies the size of the coordinate space for the
     *  image. The viewbox space is mapped onto the viewport space.
     *  Also the whole image might be translated if it is too narrow
     *  or short and the aspect ratio is to be maintained.
     */
    var viewBox_param =
      document.rootElement.getAttribute("viewBox").split(/\s*,\s*|\s+/);
    var viewBox = new Object();
    viewBox.x = parseFloat(viewBox_param[0]);
    viewBox.y = parseFloat(viewBox_param[1]);
    viewBox.width = parseFloat(viewBox_param[2]);
    viewBox.height = parseFloat(viewBox_param[3]);
    return viewBox;
  } else {
    return null;
  }
}

function get_viewport() {
  /* The literal viewport for the svg element is just the screen, so
   *  it necessarily starts at (0,0) and the size can be taken from
   *  the window object.
   */
  var viewport = new Object();
  viewport.width = window.innerWidth;
  viewport.height = window.innerHeight;
  viewport.x = 0;
  viewport.y = 0;
  
  /* If x, y, width and height are specified for the viewport they
   *  specify the size on the screen. Since there is no way to
   *  convert to pixels with ASV from em or whatever, it is assumed
   *  to either be pixels or a percentage.
   */
  var attrs = ["width", "height", "x", "y"];
  for(var index = 0; index < attrs.length; index++) {
    if(document.rootElement.hasAttribute(attrs[index])) {
      var param = document.rootElement.getAttribute(attrs[index]);
      if(param.indexOf("%") == -1) {
        viewport[attrs[index]] = parseFloat(param);
      } else {
        viewport[attrs[index]] *=
          parseFloat(param.substring(0, param.indexOf('%')) / 100);
      }
    }
  }

  return viewport;
}

/* Mouse events are in the screen space which is different from the
 *  document space. Figuring out exacly how depends on the scaling.
 */
function get_user_point(event) {
  var doc_matrix = document.rootElement.createSVGMatrix();

  /* If there is a viewBox it can alter where the image is oriented
   *  relative to the viewport. We can theoretically have nested
   *  viewports which would entail walking the tree, but for now this
   *  only handles the primary viewport.
   */
  var viewBox = get_viewbox();
  if(viewBox != null) {
    var viewport = get_viewport();

    /* The viewBox has to be translated onto the viewport.
     * This scale is in user space units per pixel, so it should be
     *  appropriate for scaling the event to use space.
     */
    var scale = new Object();
    scale.x = viewBox.width / viewport.width;
    scale.y = viewBox.height / viewport.height;

    var aspect = new Object();
    aspect.position = "xMidYMid";
    aspect.slice = "meet";

    if(document.rootElement.hasAttribute("preserveAspectRatio")) {
      aspect_param =
        document.rootElement.getAttribute("preserveAspectRatio").split(/\s+/);
      aspect.position = aspect_param[0];
      if(aspect_param.length > 1) {
        aspect.slice = aspect_param[1];
      }
    }

    /* If the image won't fit there will be whitespace along one
     *  axis. This introduces a translation along with the scale.
     */
    var whitespace = new Object();
    whitespace.x = 0;
    whitespace.y = 0;
    
    if(aspect.position != "none") {
      /* If there is any preservation of aspect ration then one
       *  dimension has to be dominant.
       */
      scale.x = scale.y = (aspect.slice == "meet" ?
                           Math.max(scale.x, scale.y) :
                           Math.min(scale.x, scale.y));
      
      if(/xMin/.test(aspect.position)) {
        whitespace.x = 0;
      } else if(/xMid/.test(aspect.position)) {
        whitespace.x = Math.round((viewport.width - viewBox.width / scale.x) / 2);
      } else if(/xMax/.test(aspect.position)) {
        whitespace.x = Math.round(viewport.width - viewBox.width / scale.x);
      } else {
        alert("No X match for: " + aspect.position);
      }
      if(/YMin/.test(aspect.position)) {
        whitespace.y = 0;
      } else if(/YMid/.test(aspect.position)) {
       whitespace.y = Math.round((viewport.height - viewBox.height / scale.y) / 2);
      } else if(/YMax/.test(aspect.position)) {
        whitespace.y = Math.round(viewport.height - viewBox.height / scale.y);
      } else {
        alert("No Y match for: " + aspect.position);
      }
    }
    /*
    alert("viewport: [" + viewport.width + ", " + viewport.height + "]\n" +
          "viewBox: [" + viewBox.width + ", " + viewBox.height + "]\n" +
          ("scale: [" + (Math.round(scale.x * 100) / 100) + ", " +
           (Math.round(scale.y * 100) / 100) + "]\n") +
          "whitespace: [" + whitespace.x + ", " + whitespace.y + "]");
    */
    doc_matrix = doc_matrix.scaleNonUniform(scale.x, scale.y);
    doc_matrix = doc_matrix.translate(-whitespace.x, -whitespace.y);
  }

  if(typeof(event.target.getCTM) != "undefined") {
    doc_matrix = doc_matrix.multiply(event.target.getCTM());
  }

  //print_matrix(doc_matrix);

  var point = document.rootElement.createSVGPoint();
  point.x = event.clientX;
  point.y = event.clientY;
  point = point.matrixTransform(doc_matrix);
  /*
  alert("(" + event.clientX + ", " + event.clientY + ") => " +
        "(" + point.x + ", " + point.y + ")");
  */
  return point;
}

function print_matrix(matrix) {
  alert(matrix.a + " " + matrix.b + " " + matrix.c + "\n" +
        matrix.d + " " + matrix.e + " " + matrix.f);
}
