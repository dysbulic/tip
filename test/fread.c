#include <stdio.h>
#include <stdlib.h>

#define FILE_NAME "login.com"

#define TRUE  1
#define FALSE 0

int main(void) {
  FILE* test_file = NULL;
  char temp;
  
  test_file = fopen(FILE_NAME, "r");
  
  if(test_file != NULL) {
    do {
      fscanf(test_file, "%c", &temp);
      printf("%c", temp);
    } while(feof(test_file) == FALSE);
  } else {
    printf("Error reading file/n");
    return -1;
  }
  fclose(test_file);
  return 0;
}
