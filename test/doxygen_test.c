/**
 * This is a brief test of how doxygen documents #defines
 *
 * \author Will Holcomb <wholcomb@gmail.com>
 * \date 5 August 2006
 */

#include <stdio.h>

/**
 * \def BIT_1
 * \brief The first bit
 */

#define BIT_1 (1<<0)
#define BIT_2 (1<<1)
#define BIT_3 (1<<2)
#define BIT_4 (1<<3)
#define BIT_5 (1<<4)
#define BIT_6 (1<<5)

enum bits {
  one   = (1<<0),   /// The first bit
  two   = (1<<1),
  three = (1<<2),
  four  = (1<<3),
  five  = (1<<4),
  six   = (1<<5)
};

int main(void) {
  enum bits bit_mask;
  printf("%d : %d\n", BIT_1, bit_mask = one);
  return 0;
}
