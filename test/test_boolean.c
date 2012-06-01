#include <stdio.h>

int main(void) {
  if(1 && -1) {
    printf("one is true\n");
  } else if(0) {
    printf("zero is true\n");
  } else {
    printf("there is no truth\n");
  }
  return 0;
}
