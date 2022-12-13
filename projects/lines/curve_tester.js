function CurveControl(formname, displayname) {
  this.form = document.getElementById(formname);
  this.display = document.getElementById(displayname);
  this.curves =
    {"cat" : ("M 22.3,7.2 L 25.3,5.2 25.55,9.8" +
              "M 23.25,7.7 L 24.6,6.9 24.6,8.9" +
              "M 16.05,9.85 L 16.25,5.4 19.2,7.4" +
              "M 16.85,8.8 L 16.9,6.8 18.2,7.6" +
              "M 19.6,12.8 L 21.2,12.8 20.55,13.85 z" +
              "M 20.6 7.7 C 26.7,7.5 27.2,17.65 20.7,17.7 C 14.2,17.75 14.5,7.9 20.6,7.7" +
              "M 18.7 14.4 C 20,16.4 20.4,13.9 20.55,13.9 C 20.7,13.9 20.9,16.2 22.25,14.5"),
     "cannon" : "L 5,10 10,0 z",
     "fish" : "L 10,10",
     "heart" : "L 10,10"};
  this.dots = new Array();

  for(curve in this.curves) {
    var option = document.createElement("option");
    option.appendChild(document.createTextNode(curve));
    this.form.curve.appendChild(option);

    this.curves[curve] = parseSVGPath(this.curves[curve]);
  }
  
  this.propchange = function(event) {
    var controller = arguments.callee.parent;
    var form = controller.form;
    var curve = new Array();
    for(i = 0; i < controller.curves[form.curve.value].length; i++) {
      var segment = new Array();
      for(j = 0; j < controller.curves[form.curve.value][i].length; j++) {
        var point = new Array();
        for(k = 0; k < controller.curves[form.curve.value][i][j].length; k++) {
          point.push(controller.curves[form.curve.value][i][j][k]);
        }
        segment.push(point);
      }
      curve.push(segment);
    }
    if(curve) {
      if(form.rotation.value != 0) {
        rotate_curve(curve, form.rotation.value * Math.PI / 180);
      }
      if(form.scalex.value != 1 || form.scaley.value != 1) {
        scale_curve(curve, {x:new Number(form.scalex.value),
                            y:new Number(form.scaley.value)});
      }
      if(form.transx.value != 0 || form.transy.value != 0) {
        translate_curve(curve, {x:new Number(form.transx.value),
                                y:new Number(form.transy.value)});
      }
      controller.dots.activeindex = 0;
      draw_lines(controller.display, make_lines(curve), controller.dots);
    }
  }
  this.propchange.parent = this;

  for(element in this.form) {
    if(this.form[element] && this.form[element].nodeType == Node.ELEMENT_NODE &&
       this.form[element].name) {
      addListener(this.form[element], "change", this.propchange);
    }
  }
}
