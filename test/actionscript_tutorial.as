/* From http://www.mtasc.org
 *  mtasc -swf actionscript_tutorial.swf -header 800:600:20 -main actionscript_tutorial.as
 */
class Tuto {
  function Tuto() {
    // creates a 'tf' TextField size 800x600 at pos 0,0
    _root.createTextField("tf",0,0,0,800,600);
    // write some text into it
    _root.tf.text = "Hello world !";
  }

  // entry point
  static function main() {
    var t = new Tuto();
  }
}
