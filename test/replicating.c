#include <stdio.h>

i = 3;
char* p = "char *p=%c%s%c; main() { printf(p,34,p,34); }";
char j[25] = {"william james holcomb"};

int main(void) {
  printf("i = %d\n", i);	
  printf("j = %s\n", j);	

  printf(p,34,p,34);
  printf("\n");
  printf("char *p=%c%s%c; main() { printf(p,34,p,34); }",
         34,
         "char *p=%c%s%c; main() { printf(p,34,p,34); }",
         34);
  printf("\n");
}
