class PlayButton extends PlayControl {
  function PlayControl() {
    var pad = Math.min(Stage.height * padPercentage,
                       Stage.width * padPercentage);
    var lineWidth = Math.min(Stage.height * linePercentage,
                             Stage.width * linePercentage);
    this.moveTo(pad, pad);
    this.beginFill(color, 50);
    this.lineStyle(lineWidth, color, 100);
    this.lineTo(Stage.width - pad, Stage.height / 2);
    this.lineTo(pad, Stage.height - pad);
    this.lineTo(pad, pad);
    this.endFill();
  }
  
  public static function main() {
  }
}
