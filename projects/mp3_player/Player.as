/* From http://www.mtasc.org
 *  mtasc -swf mp3_player.swf -header 400:600:20 -main Player.as
 */
class Player {
  function Player() {
    // player doesn't need to update most of the time
    //_level0.stop();

    _level0.playerConfig = new Object();
    _level0.loaded = false;

    // without this, I can't call the functions from setPlaying
    // même avec "this."
    _level0.playerConfig.playControl = playControl;
    _level0.playerConfig.drawButton = drawButton;
    _level0.playerConfig.layoutButtons = layoutButtons;
    _level0.playerConfig.updateProgressBar = updateProgressBar;
    _level0.playerConfig.updateDisplayExtents = updateDisplayExtents;
    
    _root.createTextField("output", _root.getNextHighestDepth(), 0, 0, Stage.width, Stage.height);

    _level0.watch("playing", setPlaying);
    _level0.watch("paused", setPaused);
    _level0.watch("song", setSong);
    _level0.watch("color", setColor);
    _level0.watch("backgroundColor", setColorProperty);
    _level0.watch("hoverColor", setColorProperty);
    _level0.watch("fontSize", setFontProperty);
    _level0.watch("fontColor", setFontProperty);
    _level0.watch("fontBold", setFontProperty);
    _level0.watch("fontAlign", setFontProperty);
    _level0.watch("displayText", setDisplayText);

    //_level0.watch("seekOffset", setSeekOffset);
    _level0.addProperty("seekOffset", getSeekOffset, setSeekOffset);

    _level0.soundObject = new Sound(_level0);
    _level0.soundObject.onLoad = function(success) {
      _level0.playerConfig.loading = false;
      if(success) {
        if(typeof(_level0.soundObject.id3) != "undefined") {
          _level0.songTitle = (_level0.soundObject.id3['artist'] + " - " +
                               _level0.soundObject.id3['songname']);
        } else {
          _level0.songTitle = "Unknown";
        }
        _level0.loaded = true;
        _level0.playerConfig.layoutButtons();
        getURL("javascript:if(typeof('songLoadedCallback') != 'undefined') {" +
               "songLoadedCallback(true, '" + _level0.song.replace("'", "%27") + "'); }");
        if(_level0.playing) {
          _level0.playing = "false";
          _level0.playing = "true";
        }
      } else {
        TRACE("Load failed");
        getURL("javascript:if(typeof('songLoadedCallback') != 'undefined') {" +
               "songLoadedCallback(false, '" + _level0.song.replace("'", "%27") + "'); }");
        _level0.songTitle = "Load Failed";
      }
      _level0.trackDisplay.text = _level0.displayText + " (" + _level0.songTitle + ")";
    };
    
    _level0.soundObject.onSoundComplete = function() {
      TRACE("Ending sound: position: " + _level0.soundObject.position);
      _level0.playing = "false";
    };

    _level0.onEnterFrame = function() {
      _level0.playerConfig.updateProgressBar();
    }

    _level0.createEmptyMovieClip("background", _level0.getNextHighestDepth());
    var buttons = new Array("back", "play", "stop", "forward");
    for(var i = 0; i < buttons.length; i++) {
      var button = _level0.createEmptyMovieClip(buttons[i] + "Button", _level0.getNextHighestDepth());
      button.typeName = buttons[i];
      button.onRollOver = function() {
        this.hovered = true;
        _level0.playerConfig.drawButton(this);
      }
      button.onRollOut = function() {
        this.hovered = false;
        _level0.playerConfig.drawButton(this);
      }
    }

    _level0.createEmptyMovieClip("progressBar", _level0.getNextHighestDepth(),
                                 0, 0, Stage.width, Stage.height);
    _level0.createTextField("trackDisplay", _level0.getNextHighestDepth(),
                            0, 0, Stage.width, Stage.height);

    _level0.trackDisplay.selectable = false;
    _level0.trackDisplay.multiline = false;
    _level0.playerConfig.trackFormat = new TextFormat();

    _level0.createEmptyMovieClip("trackMouseCatcher", _level0.getNextHighestDepth(),
                                 0, 0, Stage.width, Stage.height);
    
    _level0.trackMouseCatcher.onMouseDown = function() {
      _level0.trackMouseCatcher.onMouseMove =
        function() {
          if(this._xmouse >= 0 && this._ymouse >= 0
             && this._ymouse <= _level0.displayExtents.height) {
            _level0.seekOffset = _level0.soundObject.duration
              * (this._xmouse / _level0.trackDisplay._width);
           }
        }
    }

    _level0.trackMouseCatcher.onMouseUp = function() {
      _level0.trackMouseCatcher.onMouseMove = undefined;
    }

    var args:Array = new Array("padPercentage", "linePercentage", "spacingPercentage",
                               "color", "backgroundColor", "hoverColor",
                               "displayText", "song", "fontSize", "fontColor",
                               "fontBold", "fontAlign");
    var defs:Array = new Array(10, 5, 12,
                               "0x232323", "0xE6E6E6", "0x9F9F04",
                               "MP3 Player", undefined, "15", "0",
                               "true", "center");
    for(var i = 0; i < args.length; i++) {
      if(typeof(_level0[args[i]]) == "undefined") {
        TRACE("Defaulting: " + args[i] + " to \"" + defs[i] + "\"");
        _level0[args[i]] = defs[i];
      } else {
        _level0[args[i]] = _level0[args[i]];
      }
    }

    // fixed anchor in the top left corner of the screen.
    Stage.align = "LT";
    // prevent the Flash movie from resizing when the browser window changes size.
    Stage.scaleMode = "noScale";

    var resizeListener = new Object();
    resizeListener.onResize = function() {
      _level0.playerConfig.updateDisplayExtents();
      _level0.playerConfig.layoutButtons();
    }
    Stage.addListener(resizeListener);

    /*
    loadMovie("Button", _level1);
    TRACE(typeof(_level1));
    */

    /*
    _level2 = _level0.createEmptyMovieClip("_level2", 2);
    TRACE(typeof(_level2));
    _level2.playButton = _level2.createEmptyMovieClip("playButton", 2);
    _level2.loadMovie("Button", _level2.playButton);
    TRACE(typeof(_level2.playButton));
    */

    //_level1.playButton = new PlayButton();
    //_level1.playButton = _level1.createEmptyMovieClip("playButton", 1);
    //playButton = _level1.createEmptyMovieClip("playButton", 1);
    //_level0.createEmptyMovieClip("playButton", 1).makeButton();
    //_level1.loadMovie("PlayButton", _level1.playButton);
    /*
    loadMovie("PlayButton", _level1.playButton);
    TRACE(_level1.playButton);
    */
    //_level0.playButton._x = Stage.width / 2 + 20;
    /*
    playButton._x = Stage.width / 2 + 20;
    playButton._y = Stage.height / 2;
    playButton.onRelease = function() {
    };
    */

    _level0.playerConfig.updateDisplayExtents();
    _level0.playerConfig.layoutButtons();

    _level0.playButton.onRelease = function() {
      if(typeof(_level0.playing) == "undefined" || !_level0.playing) {
        _level0.playing = "true";
      } else {
        _level0.paused = "true";
      }
    }

    _level0.stopButton.onRelease = function() {
      _level0.playerConfig.paused = false;
      _level0.playing = "false";
    }

    _level0.backButton.onRelease = function() {
      _level0.seekOffset -= 1000;
    }

    _level0.forwardButton.onRelease = function() {
      _level0.seekOffset += 1000;
    }

    //_level0.button = _level0.attachMovie("Button", "newButton", 3);
    /*
    _level0.newButton = new Button();
    _level0.newButton._x = 40;
    _level0.newButton._y = 100;
    TRACE("button = " + typeof(_level0.newButton._width));
    */
    /*
    TRACE("hasMP3 = " + System.capabilities.hasMP3);
    for(var prop:String in _level0.soundObject) {
      TRACE(prop + " = " + _level0.soundObject[prop]);
    }
    TRACE("pan = " + _level0.soundObject.getPan());
    TRACE("volume = " + _level0.soundObject.getVolume());
    TRACE("duration = " + _level0.soundObject.getDuration());
    TRACE("position = " + _level0.soundObject.getPosition());
    */
  }
  
  public static function customTrace(message, className, filename, lineNumber) {
    _root.output.text += "\n" + message;
  }

  function setPlaying(property:String, wasPlaying:Boolean, argShouldPlay:String):Boolean {
    _level0.playerConfig.playing = (argShouldPlay == "true" ? true : false);
    _level0.playerConfig.playControl(wasPlaying, _level0.playerConfig.playing, _level0.paused);
    if(!_level0.playerConfig.playing && !_level0.playerConfig.paused) {
      _level0.trackDisplay.text = _level0.displayText + " (" + _level0.songTitle + ")";
    } else {
      _level0.trackDisplay.text = _level0.songTitle;
    }
   return _level0.playerConfig.playing;
  }

  function setPaused(property:String, wasPaused:Boolean, argShouldPause:String):Boolean {
    var shouldPause:Boolean = (argShouldPause == "true" ? true : false);
    var message = "setPaused(" + wasPaused + ", " + shouldPause + ")";
    if(_level0.playing && shouldPause) {
      TRACE(message + " >> pausing");
      _level0.seekOffset = _level0.soundObject.position;
      _level0.playerConfig.paused = shouldPause;
      _level0.playing = "false";
    } else if(wasPaused && !shouldPause) {
      TRACE(message + " >> unpausing");
      _level0.playing = "true";
    }
    return shouldPause;
  }

  function playControl(wasPlaying:Boolean, shouldPlay:Boolean, isPaused:Boolean):Void {
    var message = ("playControl: (" + wasPlaying + ") => (" + shouldPlay + ") " +
                   "[" + isPaused + "/" + _level0.paused + "/" + _level0.playerConfig.paused + "]" +
                   ": " + _level0.seekOffset);
    if((!wasPlaying && shouldPlay) || (_level0.playerConfig.paused && shouldPlay)) {
      message += " >> playing";
      var offset = _level0.playerConfig.paused ? _level0.seekOffset / 1000 : 0;
      _level0.seekOffset = 0;
      _level0.soundObject.start(offset, 1);
      _level0.playButton.typeName = "pause";
      _level0.playerConfig.drawButton(_level0.playButton);
      _level0.playerConfig.paused = false;
    } else if(wasPlaying && !shouldPlay) {
      message += " >> stoping";
      _level0.soundObject.stop();
      _level0.playButton.typeName = "play";
      _level0.playerConfig.drawButton(_level0.playButton);
    }
    TRACE(message);
  }

  //function setSeekOffset(property:String, currentOffset:Number, argNewOffset:String):Number {
  function setSeekOffset(argNewOffset:String):Number {
    var newOffset:Number = parseInt(argNewOffset);
    if(_level0.playing) {
      _level0.soundObject.stop();
      _level0.soundObject.start(newOffset / 1000, 1);
    }
    return newOffset;
  }

  public function getSeekOffset():Number {
    return _level0.soundObject.position;
  }

  public function setSong(property:String, oldFile:String, newFile:String):String {
    if(typeof(newFile) != "undefined" && _level0.playerConfig.song != newFile) {
      TRACE("Loading: " + _level0.playerConfig.song + " => " + newFile);
      _level0.playing = "false";
      _level0.soundObject.loadSound(newFile, false);
      _level0.playerConfig.song = newFile;
      _level0.trackDisplay.text = "Loading: " + newFile;
      _level0.playerConfig.loading = true;
      _level0.loaded = false;
      _level0.playerConfig.layoutButtons();
    }
    return newFile;
  }      

  public function setDisplayText(property:String, oldText:String, newText:String):String {
    if(!_level0.playerConfig.playing && !_level0.playerConfig.paused) {
      // Do nothing, only the song title shows while playing
    } else {
      var text = newText;
      if(typeof(_level0.songTitle) != "undefined") {
        text += " (" + _level0.songTitle + ")";
      }
      _level0.trackDisplay.text = text;
    }
    return newText;
  }

  public function setColor(property:String, oldColor:Number, argNewColor:String):Number {
    var newColor = parseInt(argNewColor);
    if(newColor != NaN) {
      _level0.playerConfig.color = newColor;
    }
    layoutButtons();
    return _level0.playerConfig.color;
  }

  public function setColorProperty(property:String, oldColor:Number, argNewColor:String):Number {
    if(argNewColor == "") {
      _level0.playerConfig[property] = undefined;
    } else {
      var newColor = parseInt(argNewColor);
      if(newColor != NaN) {
        _level0.playerConfig[property] = newColor;
      }
    }
    layoutButtons();
    return _level0.playerConfig[property];
  }
  
  public function setFontProperty(property:String, oldValue, argNewValue:String) {
    var prop = property.substring("font".length).toLowerCase();
    if(argNewValue == "") {
      _level0.playerConfig.trackFormat[prop] = undefined;
    } else {
      var newValue;
      if(prop == "color" || prop == "size") {
        newValue = parseInt(argNewValue);
      } else if(prop == "bold") {
        newValue = (argNewValue == "true" ? true  : false);
      } else if(prop == "align") {
        newValue = argNewValue;
      } else {
        TRACE("Unknown font property: " + property);
      }
      if(newValue != NaN) {
        _level0.playerConfig.trackFormat[prop] = newValue;
      }
    }
    _level0.trackDisplay.setNewTextFormat(_level0.playerConfig.trackFormat);
    _level0.trackDisplay.setTextFormat(_level0.playerConfig.trackFormat);
    return _level0.playerConfig.trackFormat[prop];
  }
  
  public function layoutButtons() {
    var buttons = new Array("back", "play", "stop", "forward");
    var y = Stage.height / 2 - _level0.displayExtents.height / 2;
    
    _level0.progressBar._x = _level0.displayExtents.width * buttons.length;
    _level0.trackDisplay._x = _level0.trackMouseCatcher._x =
      _level0.progressBar._x + 2 * _level0.displayExtents.pad;
    _level0.trackMouseCatcher._y = _level0.progressBar._y =
      _level0.trackDisplay._y = y;
    _level0.trackDisplay._width = //_level0.trackMouseCatcher._width =
      Stage.width - _level0.trackDisplay._x - 1;
    /*
    _level0.trackDisplay._height = _level0.trackMouseCatcher._height =
      _level0.displayExtents.height;
    */

    var textHeight = (_level0.trackDisplay.getTextFormat()
                      .getTextExtent(_level0.trackDisplay.text).height);
    _level0.trackDisplay._y += (_level0.displayExtents.height / 2 -
                                textHeight * .6);
    
    if(typeof(_level0.playerConfig.backgroundColor) != "undefined") {
      TRACE("Background: (" + _level0.playerConfig.backgroundColor + ")" +
            " [0," + y + "] => [" + Stage.width + "," + (y + _level0.displayExtents.height) + "]");

      _level0.background.clear();
      _level0.background.beginFill(_level0.playerConfig.backgroundColor, 100);
      _level0.background.moveTo(0, y);
      _level0.background.lineTo(Stage.width, y);
      _level0.background.lineTo(Stage.width, y + _level0.displayExtents.height);
      _level0.background.lineTo(0, y + _level0.displayExtents.height);
      _level0.background.lineTo(0, y);
      _level0.background.endFill();
    }

    for(var i = 0; i < buttons.length; i++) {
      var button = _level0[buttons[i] + "Button"];
      _level0.playerConfig.drawButton(button);
      button._x = _level0.displayExtents.width * i;
      button._y = y;
    }
    _level0.playerConfig.updateProgressBar();
  }

  function updateDisplayExtents() {
    if(typeof(_level0.displayExtents) == "undefined") {
      _level0.displayExtents = new Object();
    }
    var size = Math.min(Stage.height, Stage.width * .075);
    _level0.displayExtents.width = size;
    _level0.displayExtents.height = size;
    _level0.displayExtents.pad =
      Math.min(_level0.displayExtents.width * _level0.padPercentage / 100,
               _level0.displayExtents.height * _level0.padPercentage / 100);
    _level0.displayExtents.lineWidth =
      Math.min(_level0.displayExtents.width * _level0.linePercentage / 100,
               _level0.displayExtents.height * _level0.linePercentage / 100);
    _level0.displayExtents.space =
      Math.min(_level0.displayExtents.width * _level0.spacingPercentage / 100,
               _level0.displayExtents.height * _level0.spacingPercentage / 100);
  }

  public function updateProgressBar() {
    _level0.progressBar.clear();
    if(_level0.playerConfig.loading || _level0.playerConfig.playing
       || _level0.playerConfig.paused) {
      var width = _level0.trackDisplay._width - 2 * _level0.displayExtents.pad;
      if(_level0.playerConfig.loading
         && typeof(_level0.soundObject.getBytesLoaded()) != "undefined"
         && typeof(_level0.soundObject.getBytesTotal()) != "undefined") {
        width *= _level0.soundObject.getBytesLoaded() / _level0.soundObject.getBytesTotal();
      } else if(_level0.playerConfig.playing) {
        width *= _level0.soundObject.position / _level0.soundObject.duration;
      } else if(_level0.playerConfig.paused) {
        width *= _level0.seekOffset / _level0.soundObject.duration;
      }
      if(width > 0) {
        _level0.progressBar.lineStyle(_level0.displayExtents.lineWidth,
                                      _level0.playerConfig.color, 100);
        _level0.progressBar.beginFill(_level0.playerConfig.color, 50);
        _level0.progressBar.moveTo(_level0.displayExtents.pad, _level0.displayExtents.pad);
        _level0.progressBar.lineTo(_level0.displayExtents.pad + width, _level0.displayExtents.pad);
        _level0.progressBar.lineTo(_level0.displayExtents.pad + width,
                                   _level0.displayExtents.height - _level0.displayExtents.pad);
        _level0.progressBar.lineTo(_level0.displayExtents.pad,
                                   _level0.displayExtents.height - _level0.displayExtents.pad);
        _level0.progressBar.lineTo(_level0.displayExtents.pad, _level0.displayExtents.pad);
        _level0.progressBar.endFill();
      }
    }
  }

  public function drawButton(button:MovieClip) {
    var color:Number = ((typeof(_level0.playerConfig.hoverColor) != "undefined"
                         && typeof(button.hovered) != "undefined"
                         && button.hovered)
                        ? _level0.playerConfig.hoverColor
                        : _level0.playerConfig.color);
    button.clear();
    if(_level0.loaded) {
      button.beginFill(color, 50);
    }
    button.lineStyle(_level0.displayExtents.lineWidth, color, 100);
    button.moveTo(_level0.displayExtents.pad, _level0.displayExtents.pad);
    switch(button.typeName) {
    case "play":
      button.lineTo(_level0.displayExtents.width - _level0.displayExtents.pad,
                    _level0.displayExtents.height / 2);
      button.lineTo(_level0.displayExtents.pad,
                    _level0.displayExtents.height - _level0.displayExtents.pad);
      button.lineTo(_level0.displayExtents.pad, _level0.displayExtents.pad);
      break;
    case "pause":
      button.lineTo((_level0.displayExtents.width - _level0.displayExtents.space) / 2,
                    _level0.displayExtents.pad);
      button.lineTo((_level0.displayExtents.width - _level0.displayExtents.space) / 2,
                    _level0.displayExtents.height - _level0.displayExtents.pad);
      button.lineTo(_level0.displayExtents.pad,
                    _level0.displayExtents.height - _level0.displayExtents.pad);
      button.lineTo(_level0.displayExtents.pad, _level0.displayExtents.pad);
      button.moveTo((_level0.displayExtents.width + _level0.displayExtents.space) / 2,
                    _level0.displayExtents.pad);
      button.lineTo(_level0.displayExtents.width - _level0.displayExtents.pad,
                    _level0.displayExtents.pad);
      button.lineTo(_level0.displayExtents.width - _level0.displayExtents.pad,
                    _level0.displayExtents.height - _level0.displayExtents.pad);
      button.lineTo((_level0.displayExtents.width + _level0.displayExtents.space) / 2,
                    _level0.displayExtents.height - _level0.displayExtents.pad);
      button.lineTo((_level0.displayExtents.width + _level0.displayExtents.space) / 2,
                    _level0.displayExtents.pad);
      break;
    case "stop":
      button.lineTo(_level0.displayExtents.width - _level0.displayExtents.pad,
                    _level0.displayExtents.pad);
      button.lineTo(_level0.displayExtents.width - _level0.displayExtents.pad,
                    _level0.displayExtents.height - _level0.displayExtents.pad);
      button.lineTo(_level0.displayExtents.pad,
                    _level0.displayExtents.height - _level0.displayExtents.pad);
      button.lineTo(_level0.displayExtents.pad, _level0.displayExtents.pad);
      break;
    case "forward":
      button.lineTo(_level0.displayExtents.width / 2,
                    _level0.displayExtents.height / 2);
      button.lineTo(_level0.displayExtents.pad,
                    _level0.displayExtents.height - _level0.displayExtents.pad);
      button.lineTo(_level0.displayExtents.pad,
                    _level0.displayExtents.pad);
      button.moveTo(_level0.displayExtents.width / 2,
                    _level0.displayExtents.pad);
      button.lineTo(_level0.displayExtents.width - _level0.displayExtents.pad,
                    _level0.displayExtents.height / 2);
      button.lineTo(_level0.displayExtents.width / 2,
                    _level0.displayExtents.height - _level0.displayExtents.pad);
      button.lineTo(_level0.displayExtents.width / 2,
                    _level0.displayExtents.pad);
      break;
    case "back":
      button.moveTo(_level0.displayExtents.width - _level0.displayExtents.pad,
                    _level0.displayExtents.pad);
      button.lineTo(_level0.displayExtents.width / 2,
                    _level0.displayExtents.height / 2);
      button.lineTo(_level0.displayExtents.width - _level0.displayExtents.pad,
                    _level0.displayExtents.height - _level0.displayExtents.pad);
      button.lineTo(_level0.displayExtents.width - _level0.displayExtents.pad,
                    _level0.displayExtents.pad);
      button.moveTo(_level0.displayExtents.width / 2,
                    _level0.displayExtents.pad);
      button.lineTo(_level0.displayExtents.pad,
                    _level0.displayExtents.height / 2);
      button.lineTo(_level0.displayExtents.width / 2,
                    _level0.displayExtents.height - _level0.displayExtents.pad);
      button.lineTo(_level0.displayExtents.width / 2,
                    _level0.displayExtents.pad);
      break;
    }
    button.endFill();
  }

  // entry point
  static function main() {
    var obj:Player = new Player();
  }
}
