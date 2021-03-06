#include "colors.inc"    // The include files contain
#include "stones.inc"    // pre-defined scene elements

camera {
  location <0, 2, -3>
  look_at  <0, 1,  2>
}

  box {
    <-1, 0,   -1>,  // Near lower left corner
    < 1, 0.5,  3>   // Far upper right corner
    texture {
      T_Stone25     // Pre-defined from stones.inc
      scale 4       // Scale by the same amount in all
                    // directions
    }
    rotate y*20     // Equivalent to "rotate <0,20,0>"
  }

/*
sphere {
  <0, 1, 2>, 2
  texture {
    pigment { color Yellow }
  }
}
*/

light_source {
  <2, 4, -3> color White
}
