var bb = null;
function drawRect( e ) {
    var elm = e.target
  
    // fixes for 'use' elements
    if(!elm.getBBox) {
        if( elm.correspondingUseElement ) {
            elm = elm.correspondingUseElement
        }
    }
    
    var r = elm.getBBox()
  
    // don't care about the root element
    var root = document.documentElement;
    if(root == e.target) {
        return false;
    }
  
    // remove any old bbox
    if( bb ) {
        bb.parentNode.removeChild(bb)
        bb = null
    }
  
    // make new bbox
    if( !bb ) {    
        bb = document.createElementNS("http://www.w3.org/2000/svg", "rect")
        bb.setAttributeNS(null, "fill", "none")
        bb.setAttributeNS(null, "stroke", "red")
        bb.setAttributeNS(null, "pointer-events", "none")
        root.appendChild(bb)
    }
  
    // make the bbox rect element have the same user units even though it's the last child of the root element
    bb.transform.baseVal.clear()
    var tfm2elm = elm.getTransformToElement( bb )

    var pad = 2

    var origin = root.createSVGPoint()
    origin.x = r.x - pad
    origin.y = r.y - pad

    var dest = root.createSVGPoint()
    dest.x = origin.x + r.width + 2 * pad
    dest.y = origin.y + r.height + 2 * pad

    origin = origin.matrixTransform(tfm2elm)
    dest = dest.matrixTransform(tfm2elm)

    dest.x -= origin.x
    dest.y -= origin.y

    // outset the rect a bit
    bb.x.baseVal.value = origin.x
    bb.y.baseVal.value = origin.y
    bb.width.baseVal.value = dest.x
    bb.height.baseVal.value = dest.y
  
    //bb.transform.baseVal.appendItem(bb.transform.baseVal.createSVGTransformFromMatrix(tfm2elm));
}

// install listener
document.documentElement.addEventListener("mousemove", drawRect, false);
