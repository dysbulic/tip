#include <stdio.h>

#define SEPARATOR "+---+---+---+------------+\n"

/**
 * Submitting bug report on MURI project and want to make sure it's
 * accurate so Sandy doesn't yell at me.
 */
int main(void) {
  int i, j, k;

  printf(SEPARATOR);
  printf("| i | j | k | !i||!j&&!k |\n");
  printf(SEPARATOR);

  for(i = 0; i <=1; i++) {
    for(j = 0; j <=1; j++) {
      for(k = 0; k <=1; k++) {
        printf("| %s | %s | %s |    %s    |\n",
               i ? "T" : "F", j ? "T" : "F", k ? "T" : "F",
               (!i || !j && !k) ? "T" : "F");
        printf(SEPARATOR);
      }
    }
  }
  return 0;
}
        
