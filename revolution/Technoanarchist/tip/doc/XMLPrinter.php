<?php
/**
 * Author: Will Holcomb <will@technoanarchy.org>
 * Date: July 2009
 *
 * XML printing using nested stacks
 */

//header('Content-type: text/plain');

/**
 * Things which can be viewed.
 */
class Thing {
  private $values = array();

  function __construct() {
    // If an argument is specified, make it the value for the thing
    if(func_num_args() > 0) $this->val = func_get_arg(0);
  }

  function __toString() { return (string)$this->val; }

  function __get($name) {
    if(isset($this->values[$name])) return $this->values[$name];
    return null;
  }
  
  function __set($name, $value) {
    $this->values[$name] = $value;
  }

  static function __invoke() {
    $as = func_get_args();
    $n = count($as);

    if($n == 1 && $as[0] instanceof Thing) return $as[0];

    for($i = 0; $i < $n; $i++) {
      if(is_array($as[$i])) {
        $as[$i] = call_user_func_array(array('Thing', '__invoke'), $as[$i]);
      } elseif(!$as[$i] instanceof Thing) {
	$as[$i] = new Thing($as[$i]);
      }
    }
    return $as;
  }
}

/**
 * An indexed Stack of Things.
 */
class Stack {
  public $things = array();

  /* Create a stack with the function arguments */
  function __construct() {
    if(func_num_args() > 0) {
      $as = func_get_args();
      $s = call_user_func_array(array('Stack', '__invoke'), $as);
      $this->things = $s->things;
    }
  }

  function __get($name) {
    if($name == 'ℓ') return $this->size();
    return Thing::__get($name);
  }
  
  /* Walk the tree: no loop checking */
  function walk($func) {
    for($i = 1; $i <= $this->ℓ; $i++) {
      if($this->elem($i) instanceof Stack) {
        $this->elem($i)->walk($func);
      } else {
	$func($this->elem($i));
      }
    }
  }
  
  /**
   * Size:
   *   size()   => int: size of the current stack
   *   size(x)  => bool: size of the current stack > x
   *   size(-x) => bool: size of the current stack < x
   */
  function size() {
    $n = func_num_args();
    
    if($n == 0) return count($this->things);
    
    $x = func_get_arg(0);
    if(is_integer($x)) {
      if($x >= 0) return $this->ℓ > $x;
      if($x < 0)  return $this->ℓ < $x;
    }
    return $this;
  }

  /**
   * This should be an override of the array operators
   *
   * Elements in a stack are indexed:
   *   elem(1)      => Top element
   *   elem(-1)     => Bottom element
   *   elem([1, 1]) => The top element of the top stack
   */
  function elem() {
    $n = func_num_args();

    if($n == 0) return $this;
    
    $x = func_get_arg(0);
    
    if(is_integer($x) && $x != 0) {
      $i = $x;
      if($i > 0) $i -= 1;        // Shift to zero-based indexing
      if($i < 0) $i += $this->ℓ; // Allow negative indexing
      return $this->things[$i];
    }
    if(is_array($x)) {
      if(count($x) > 1) return $this->elem(array_shift($x))->elem($x);
      return $this->elem(array_shift($x));
    }
    return $this;
  }
  
  /**
   * Add:
   *   insert(index Thing, Thing, ...) Insert Think, Thing, ... after index (0 -> front, -1 -> back)
   *   insert(x) = insert(0 x)
   */
  function insert() {
    $as = func_get_args();
    $n = count($as);
    
    if($n == 0) return $this;

    if($n == 1) return $this->insert(0, $as[0]);

    $i = array_shift($as);

    if($i < 0) $i += $this->ℓ + 1;    // Allow negative indexing

    $as = call_user_func_array(array('Thing', '__invoke'), $as); // encapsulate values as Things

    if(!is_array($as)) $as = array($as);

    array_splice($this->things, $i, 0, $as);

    return $this;
  }

  function delete() {
    $as = func_get_args();
    $n = count($as);

    if($n == 0) return $this;

    $i = $as[0];
    if($i > 0) $i -= 1;        // Shift to zero-based indexing
    if($i < 0) $i += $this->ℓ; // Allow negative indexing

    $x = array_splice($this->things, $i, 1);

    return $x[0];
  }

  /**
   * Stacks the arguments replacing arrays with Stacks and values with Things.
   */
  static function __invoke() {
    $as = func_get_args();
    $n = count($as);

    if($n == 1 && $as[0] instanceof Stack) return $as[0];
 
    $as = call_user_func_array(array('Thing', '__invoke'), $as); // encapsulate values as Things

    $s = new Stack();

    for($i = 0; $i < $n; $i++) {
      if(is_array($as[$i])) {
        $as[$i] = call_user_func_array(array('Stack', '__invoke'), $as[$i]);
      }
      $s->insert(-1, $as[$i]);
    }
    return $s;
  }

  function __toString() {
    $o = "";

    if(phpversion() >= '5.3.0') {
      eval('$walker = function ($elem) use ($o) { $o .= $elem; };');
    } else {
      $walker = create_function('$elem', 'static $o = ""; $o .= $elem; return $o;');
    }

    $this->walk($walker);

    if(phpversion() < '5.3.0') $o = $walker('');

    return $o;
  }
}

if(debug) {
  $one = new Thing("1:");
  print "One = $one\n";

  $bag = new Stack($one, "2:", "3:", "4");
  print "Bag = $bag\n";

  $bag = new Stack(array("1-1:", "1-2:"),
		   array("2-1:", array("2-2-1:", "2-2-2:", "2-2-3:")),
		   array(array("5-1-1:", "5-1-2:"), "5-2"));
  print "Bag = $bag\n";

  $bag->insert(2, "3:");
  $bag->insert(-2, "4:");

  for($i = 1; $i <= $bag->ℓ; $i++) {
    print "Thing[$i] = "  . $bag->elem($i) . "\n";
  }

  print "Deleting: {$bag->delete(3)}\n";
  print "Deleting: {$bag->delete(-2)}\n";
  
  for($i = -1; $i >= -$bag->ℓ; $i--) {
    print "Thing[$i] = "  . $bag->elem($i) . "\n";
  }

  print "Thing[0] = " . $bag->elem(0) . "\n";
}

class TagPrinter {
  protected $tags;

  function __construct() {
    $this->tags = new Stack();
  }

  public function __invoke() {
    $as = func_get_args();
    $n = count($as);
    if($n == 0) return $this->close();
    return call_user_func_array(array($this, 'open'), $as);
  }

  public function __get($name) {
    if($name == 'c') return $this->close();
  }

  function open($name, $args) {
    $this->tags->insert($name);
    print "<$name";
    foreach($args as $key => $val) {
      print " $key='$val'";
    }
    print ">";
  }

  function close() {
    print "</{$this->tags->delete(1)}>";
  }

  function link($dest) {
    return $this->open('a', array('href' => $dest));
  }
}

//   function clear() { $this->elements = array(); }


/**
 * A bag holds bags and scraps. A bag has one opening from which
 * things can be pulled.
 */
// class Bag extends Stack {

//   /* Constructor: Create a bag with the function arguments */
//   function __construct() {
//     Stack::__construct(array());
//     $reflect = new ReflectionMethod('Stack', 'a');
//     $args = func_get_args();
//     $reflect->invokeArgs($this, $args);
//     $this->a(func_get_args());
//   }


//   function __get($name) {
//     switch($name) {
//       case 's': // Strings of empty bags are skipped
//         $s = $this->s;
//         while($s instanceof Bag && $s->l == 0) { $s = $s->s; }
//         return $s;
//       case 'l': return $this->l;
//       default: return null;
//     }
//   }

//   function __set($name, $value) {
//     switch($name) {
//       case 'b':
//         $this->s = $value;
//         $this->l = isset($value) ? 1 : 0;
//         break;
//       case 'l': $this->l = $value; break;
//     }
//   }

// }


//   function e() {
//     $s = Bag::bag(func_get_args());

//     // Create a stack window to look into the arguments bag
//     $w = $s->window();

//     // No arguments pass through
//     if($w->count() == 0) { return parent::b(); }
    
//     // Lists of integers are indices
//     if($w->allInt()) {
//       // If searching for a particular bag
//       if($w->l == 1) {

//         // Pass through
//         if($i == 0) { return parent::b(0); }
        
//         return Stack::bag($this->s[$i]);
//       }

//       // Fill a bag with the numbered bags requested
//       $n = Stack::bag();
//       while(isset($w->b(1)->b)) { $n->a(-1, $this->b($w->m(1)->b)); }
//       $s = $n;
//     }
//     parent::b($s);
//   }

//   /**
//    * Add:
//    *   a(1, 2, 3... unknown) Insert unknown after 1, 2, 3, ....
//    *   a(unknown)            Add unknown to end of list
//    */
//   function a() {
//     $s = Stack::bag(func_get_args());

//     // Create a window
//     $w = $s->w();

//     // Skip integer arguments
//     while(is_integer($w->b(0)->b)) { $w->m(1); }

//     // Add at the end if no window movement
//     if($s->b(0) == $w->b(0)) { $this->b(-1)->a($s); }

//     // Work backwards from the window inserting rest of the list
//     for($i = -1; isset($w->b($i)->b); $i--) { $this->b($w->b($i)->b)->a($w->f()); }

//     return $this;
//   }

//   /* View */
//   function v() {
//     $out = "";
//     for($i = 0; $i < count($this->s); $i++) {
//       if(count($out > 0)) { $out .= ":"; }
//       if($this->s[$i] instanceof Stack) {
//         $out .= $this->s[$i]->v();
//       } elseif($this->s[$i] instanceof Bag) {
//         $out .= $this->s[$i]->b;
//       } else {
//         $out .= $this->s[$i];
//       }
//     }
//   }

//   /**
//    * Length Meanings:
//    *   0: An empty bag
//    *   1: A bag containing only one thing
//    *  >1: A bag containing more than one thing
//    */

//   function __construct($s = null) { $this->b = $s; }


//   function __isset($name) {
//     switch($name) {
//       case 'b': case 'l': return $this->l > 0 && isset($this->s);
//       default: return false;
//     }
//   }

//   function __unset($name) {
//     switch($name) {
//       case 'b': case 'l':
//         $this->l = 0;
//         $this->s = null;
//     }
//   }

//   /* Pull from the bag:
//    *   b()       The value for the bag
//    *   b(0)      This bag
//    *   otherwise Set the scrap
//    */
//   function b() {
//     $n = func_num_args();
    
//     // Holistic value
//     if($n == 0) { return $this->b; }

//     // b(0)
//     if($n == 1 && func_get_arg(1) == 0) { return $this; }

//     // Scrap
//     $this->b = Stack::bag(func_get_args());
//   }

//   /* PHP functions */
//   function isInt() { return is_integer($this->s); }
// }


//   function bag() {
//     $as = func_get_args();

//     // Guard against funny nestings
//     while(is_array($as) && count($as) == 1) { $as = $as[0]; }

//     print_r($as);
//     print "\n";

//     if(!is_array($as)) {
//       // Bags are always themselves
//       if($as instanceof Bag) { return $as; }

//       // Some bags are empty
//       if(!isset($as)) { return new Stack(); }

//       // Put scraps of the book in bags
//       return new Stack($as);
//     }

//     return;
//     $s = new Stack();

//     // Otherwise, put the arguments into bags
//     for($c = 0; $c < count($as); $c++) {
//       $s->a(Stack::bag($a));
//     }
//   }

//   /* Eval */
//   function e() {
//   }

//   /* Window into stack */
//   function w() { return new StackWindow($this); }
// }

// class StackWindow extends Bag {
//   function __construct() { }
// }

// /**
//  * A FlatArray which rewrites itself
//  */
// class DynamicStack extends Stack {
//   /* Evaluate */
//   function e() { $this->elements = eval($this->v()); return this; }
// }

// // Shortcut function for creating stacks
// function s() {
//   if(func_num_args() != 1) { return new Stack(func_get_args()); }
//   else {
//     $arg = func_get_arg(1);
//     return (is_array($arg) and count($arg) == 1) ? s($arg[0]) : new Stack($arg);
//   }
// }

// /**
//  * Print a statement to print n XML open tag
//  */
// class TagPrinterPrinter extents DynamicStack {
//   $format = new Stack();
//   $args = new Stack();
  
//   function __construct($name) {
//     Stack::__constructor("printf('<$name", $this->format, ">',\n", $this->args, ");");
//   }

//   function arg($name, $value) {
//     $this->format->b(' %s="%s"');

//     if(count($this->args) > 0) { $this->args->b(",\n"); }
//     $this->args->b("'$name', $location");
//   }
// }

// /**
//  * Print nested XML tags
//  */
// class XMLPrinter extends DynamicStack {
//   $tags = new DynamicStack();
  
//   function indent() { $this->a("   " * $tags->l()); }

//   /* Open Tag */
//   function o($name, $attributes) {
//     $this->tags->b("</$name>");
//     $this->indent();
        
//     $print = new TagPrinterPrinter($name);
//     if(is_array($attributes)) {
//       foreach(array_keys($attributes) as $attribute) {
//         $print->arg($attribute, sprintf("\$attributes['%s']", $attribute));
//       }
//     } else {
//       $numargs = func_num_args();
//       if($numargs > 2) {
//         $attributes = func_get_args();
//         for($i = 2; $i < $numargs; $i += 2) {
//           $print->arg($attributes[$i - 1], sprintf("\$attributes[%d]", $i));
//         }
//       }
//     }

//     $this->a($print->e())->p()->clear();
//   }

//   function c() {
//     $count = (func_num_args() == 1 ? func_get_arg(1) : 1);
//     while($count-- > 0 && !$this->openTags->empty()) {
//       indent();
//       p($this->tags->pop());
//     }
//   }

//   function oC() {
//     $this->tags->b(" -->\n");
//     $this->a("<!--\n")->p()->clear();
//   }
// }

// class Bag extends DynamicStack {
//   $strings = undefined;
//   $index = undefined;
  
//   function __construct() { $strings = func_get_args(); }
  
//   function v() { return isset($this->index) ? 
// }

// class DatePrinterPrinter extends DynamicStack {
//   public $dateFormats = StringBag("Y/m/d", "n F Y", "RANDOM");
//   $dateFormat = s($this->dateFormats[array_rand($this->dateFormats)]);
//   $date;

//   function __construct($date = false) {
//     if(!isset($date) || !$date || $date == "now") {
//       $dateFormat->clear()->a(strftime("%Y/%m/%d"));
//     }
//     Stack::__constructor("printf('", $this->format, ">',\n", $this->args, ");");
    

//         function p() {
//           return $date;
//           switch($dateFormat) {
//             case "RANDOM": return date($dateFormats[array_rand($dateFormats)], $date);
//             default:       return date($dateFormat, $date);
//           }
//         }
//       }
      

//       function pC() { OPENINGS() print "<!--\n"; }


//       /* -- Testing code - WJH
//       pC();
//         $test = new TagPrinter("Testing Print");
//         $val  = "123";

//         $test->arg("testattr", '$printval');
//         pN($testprint->printf());
//         eval($testprint->printf());
//       c();
//       */

//       $tags = array();
//       function openTag($name, $attributes) {
//         global $tags;
//         array_push($tags, $name);
        
//         $print = new PrintStatement($name);
//         if(is_array($attributes)) {
//           foreach(array_keys($attributes) as $attribute) {
//             $print->arg($attribute, sprintf("\$attributes['%s']", $attribute));
//           }
//         } else {
//           $numargs = func_num_args();
//           if($numargs > 2) {
//             $attributes = func_get_args();
//             for($i = 2; $i < $numargs; $i += 2) {
//               $print->arg($attributes[$i - 1], sprintf("\$attributes[%d]", $i));
//             }
//           }
//         }
//         return $print->printf();
//       }

//       function closeTag() {
//         global $tags;
//         return sprintf("</%s>", array_pop($tags));
//       }

//       function pOpenTag()  { eval(openTag); }
//       function pCloseTag() { eval(closeTag); }

//       function nOpenTag()  { pOpenTag();  p(); }
//       function nCloseTag() { pCloseTag(); p(); }

//       function c() {

