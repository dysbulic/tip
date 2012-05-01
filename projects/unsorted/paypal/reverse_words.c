/**
 * Input:  "Four score and seven years ago"
 * Output: "ago years seven and score Four"
 * Trick:  No local storage
 */

#include <stdio.h>

char* reverse_string(char* string, int offset);
int string_length(char* string, int length);
int word_length(char* string, int length);
void swap_characters(char* a, char* b);
void reverse_words(char* string);

int main(int argc, char** argv) {
  while(--argc > 0) {
    printf("Before: %s\n", argv[argc]);
    reverse_string(argv[argc], string_length(argv[argc], 0) - 1);
    reverse_words(argv[argc]);
    printf(" After: %s\n", argv[argc]);
  }

  return 0;
}

char* reverse_string(char* start, int offset) {
  if(offset > 0) {
    swap_characters(start, start + offset);
    reverse_string(start + 1, offset - 2);
  }
  return start + offset;
}

void reverse_words(char* string) {
  while(*string != '\0') {
    while(*string == ' ' || *string == '\t' ||
	  *string == '\n' || *string == '\0') {
      string++;
    }
    string = reverse_string(string, word_length(string, 0) - 1) + 1;
  }
}

int string_length(char* string, int length) {
  if(*string == '\0') {
    return length;
  } else {
    return string_length(string + 1, length) + 1;
  }
}

int word_length(char* string, int length) {
  if(*string == ' ' || *string == '\t' ||
     *string == '\n' || *string == '\0') {
    return length;
  } else {
    return word_length(string + 1, length) + 1;
  }
}

void swap_characters(char* a, char* b) {
  *a ^= *b;
  *b ^= *a;
  *a ^= *b;
}
