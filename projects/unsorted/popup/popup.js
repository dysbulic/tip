function popupOnce(page, properties, expiration) {
  if(typeof(expiration) == "undefined") {
    expiration = 90; // popup again in three months
  }
  if(typeof(properties) == "undefined") {
    properties = { width:document.body.offsetWidth / 2, height:document.body.offsetHeight / 2,
                   left:document.body.offsetWidth / 4, top:document.body.offsetHeight / 4,
                   location:'no', toolbar:'no', menubar:'no', scrollbars:'yes',
                   resizable:'yes' };
  }
  if(typeof(properties) == "object") {
    var propString = "";
    for(element in properties) {
      propString += element + "=" + properties[element] + ",";
    }
    properties = propString;
  }
  var count = getCookie()['visit_count'];
  if(typeof(count) == "undefined") {
    var popup = window.open(page, "", properties);
    if(popup != null) {
      popup.opener = self;
    }
    count = 1;
  } else {
    count++;
  }
  setCookie({visit_count:count}, expiration);
}
