function MenuController(menuName) {
  /* One of the things Idon't like about this menu is it pops out every time the 
   * page is loaded. I like it the first time so a new user can see the menu is 
   * there. After that though, I want it hidden.
   */
  var cookiePrefix = "menu-slide-"; // name of the cookie used to prevent the menu always popping out
  this.validityPeriod = 3; // number of days until the menu redisplays initially

  this.slideTime = 1000; // Time to complete slide (in ms)
  this.slideDelay = 50;  // Time between updates (in ms)

  /* There are two main ways to handle the sliding:
   * One is by marking the start time and doing a percentage of the way
   *  through the total slide time.
   * Two is to accumulate time as the program loops until the slide
   *  time is reached.
   * One is more accurate. Two is more smooth.
   */
  this.slideByAccumulation = true;

  this.loop.parentController = this; // see the loop function for explanation

  this.menuElement = document.getElementById(menuName);
  this.offsets = { min : -this.menuElement.offsetWidth,
                   max : new Number(getCurrentStyle(this.menuElement).left.replace(/[a-z]/g, "")) };
  this.cookieName = cookiePrefix + menuName;
  var cookieNameExp = new RegExp("^\\s*" + this.cookieName);
  var cookies = document.cookie.split(';');
  var cookieFound = false;
  for(var i = 0; !cookieFound && i < cookies.length; i++) {
    if(cookies[i].match(cookieNameExp)) { cookieFound = true; }
  }
  this.setMenuVisibility(false, !cookieFound); // close the menu with animation based on cookie
  this.setCookie(); // always set the cookie, so a complete lapse is necessary to reset
}

/* Sets a cookie preventing menu display on page load for <validityPeriod> days
 */
MenuController.prototype.setCookie = function(validityPeriod) {
  if(typeof(validityPeriod) == "undefined") { validityPeriod = this.validityPeriod; }
  var date = new Date();
  date.setTime(date.getTime() + (validityPeriod * 24 * 60 * 60 * 1000));
  var cookie = this.cookieName + "=nodisplay; expires=" + date.toGMTString() + "; path=/";
  document.cookie = cookie;
}

MenuController.prototype.eraseCookie = function() {
  this.setCookie(-1);
}

MenuController.prototype.toggleMenu = function() {
  this.setMenuVisibility(!this.visible);
}

MenuController.prototype.setMenuVisibility = function(visible, animate) {
  if(typeof(animate) == "undefined") { animate = true; }
  this.visible = visible;
  if(visible) { this.eraseCookie(); } // have the menu do an animated disappearance when visible
  if(animate) {
    if(this.slideByAccumulation) { this.elapsedTime = 0; }
    else { this.startTime = new Date().getTime(); }
    this.moveMenu();
  } else {
    this.moveMenu(visible ? this.offsets.max : this.offsets.min);
  }
}

MenuController.prototype.moveMenu = function(position) {
  if(typeof(position) == "undefined") {
    var timePassed;
    if(this.slideByAccumulation) {
      this.elapsedTime += this.slideDelay;
      timePassed = this.elapsedTime;
    } else {
      timePassed = new Date().getTime() - this.startTime;
    }
    var offset = Math.floor((this.offsets.max - this.offsets.min) * (timePassed / this.slideTime));
    if(this.visible) { position = this.offsets.min + offset; }
    else { position = this.offsets.max - offset; }
  }
  position = Math.max(this.offsets.min, Math.min(this.offsets.max, position));
  this.menuElement.style.left = position + "px";

  if((this.visible && position != this.offsets.max)
     || (!this.visible && position != this.offsets.min)) {
    setTimeout(this.loop, this.slideDelay);
  }
}

/* This is a little convoluted. When the function is called from setTimeout(),
 * "this" refers to the window object. "this" needs to refer to the Controller
 *  object, so all this function does is make the call with the correct scope.
 */
MenuController.prototype.loop = function() {
  arguments.callee.parentController.moveMenu();
}
