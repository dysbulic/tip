#include <stdio.h>

/* Output:
 *   (int)10 + (uint)10 <= (int)-1
 *   (int)10 + (ushort)10 <= (uint)-1
 *   (int)10 + (ushort)10 > (int)-1
 *
 * Promotion is forced by the unsigned int, b, causing d to by
 * typecast to an unsigned int in the first example. The second exaple
 * does it manually with the same counter intuitive result.
 */

int main(void) {
  int a = 10;
  unsigned int b = 10;
  unsigned short c = 10;
  int d = -1;

  printf("(int)%d + (uint)%d %s (int)%d\n", a, b, (a + b > d) ? ">" : "<=", d);
  printf("(int)%d + (ushort)%d %s (uint)%d\n", a, c, (a + c > (unsigned int)d) ? ">" : "<=", d);
  printf("(int)%d + (ushort)%d %s (int)%d\n", a, c, (a + c > d) ? ">" : "<=", d);
  
  return 0;
}
