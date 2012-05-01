/**
 * Simple program to read a registry keys file and save them
 * for use in places where regedit is unavailable
 *
 * 2002/02/26 Will Holcomb
 */

#ifdef WIN32
#define _POSIX_SOURCE
#include <windows.h>
#else
#include <errno.h>
#define DWORD int
#define HKEY int
#endif

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <math.h>

#define PERMANENT 0
#define TEMPORARY 1

#define BUFFER_SIZE 1000

extern int errno;

void load_reg_entries(FILE* input);
HKEY add_key(char* key);
void set_dword(char* key, DWORD value, HKEY hkey);
void set_text(char* key, char* value, HKEY hkey);

int main(int argc, char** argv) {
  int i = 0;
  for(i = 1; i < argc; i++) {
    FILE* input = fopen(argv[i], "r");
    if(input != NULL) {
      printf("Loading: %s\n", argv[i]);
      load_reg_entries(input);
      fclose(input);
    } else {
      printf("Could not open \"%s\": ", argv[i]);
#ifdef WIN32
      printf("\n");
#else
      char* message;
      switch(errno) {
      case EINVAL:
        message = "bad mode";
        break;
      case ENOMEM:
        message = "no memory";
        break;
      case ENAMETOOLONG:
        message = "path too long";
        break;
      case EACCES:
        message = "insufficient access";
        break;
      case ELOOP:
        message = "too many symbolic links";
        break;
      case EMFILE:
        message = "process not allowed any more files";
        break;
      case ENFILE:
        message = "system not allowed any more files";
        break;
      }
      printf("%s\n", message);
      return errno;
#endif
    }
  }
  return 0;
}

void load_reg_entries(FILE* input) {
  char* buffer = (char*)malloc(BUFFER_SIZE * sizeof(char));
  char* return_buffer = NULL;
  int length, index;
  DWORD dword;
  char* path, *key, *value = (char*)NULL;
  HKEY current_key;
  int preface_length = strlen("HKEY_CURRENT_USER\\");

  do {
    return_buffer = fgets(buffer, BUFFER_SIZE, input);
    if(return_buffer != NULL) {
      length = strlen(buffer);
      while(length > 0 && (buffer[length - 1] == '\n' ||
            buffer[length - 1] == '\r' || buffer[length - 1] == ' ' ||
            buffer[length - 1] == '\t')) {
        length--;
      }
      switch(buffer[0]) {
      case '[': /* The entry is a path */
        path = malloc((length - preface_length - 1) * sizeof(char));
        strncpy(path, buffer + preface_length + 1, length - preface_length - 2);
        path[length - preface_length - 2] = (char)NULL;
#ifdef WIN32
        if(current_key != (HKEY)NULL) {
          RegCloseKey(current_key);
        }
#endif
        current_key = add_key(path);
        
        free(path);
        break;
      case '\"': /* The entry is a value */
        index = 1;
        while(buffer[index] != '\"' && index < length) {
          index++;
        }
        key = malloc(index * sizeof(char));
        strncpy(key, buffer + 1, index - 1);
        key[index - 1] = (char)NULL;

        /* skip over = */
        index += 2;

        switch(buffer[index]) {
        case '\"': /* String value */
          /* Seems off by one to me, but works in windows */
          value = malloc((length - index - 1) * sizeof(char));
          strncpy(value, buffer + index + 1, length - index - 2);
          value[length - index - 2] = (char)NULL;
          break;
        case 'd': /* dword */
          index = length - 1;
          while((buffer[index] >= '0' && buffer[index] <= '9') ||
                (buffer[index] >= 'a' && buffer[index] <= 'f') ||
                (buffer[index] >= 'A' && buffer[index] <= 'F')) {
            index--;
          }
          dword = (DWORD)strtol(buffer + index + 1, (char**)NULL, 16);
        }
        if(value != NULL) {
          set_text(key, value, current_key);
          free(value);
          value = NULL;
        } else {
          set_dword(key, dword, current_key);
        }
        free(key);
        break;
      default: /* Discard */
        buffer[length - 1] = (char)NULL;
        printf("Throwing away: \"%s\"\n", buffer);
      }
    }
  } while(return_buffer != NULL);
#ifdef WIN32
  if(current_key != (HKEY)NULL) {
    RegCloseKey(current_key);
  }
#endif
  free(buffer);
}

HKEY add_key(char* key) {
#ifdef WIN32
  static HKEY hkey;
  int disposition;
  int returnValue;
#endif
  printf("Adding key: %s\n", key);
#ifdef WIN32
  returnValue = RegCreateKeyEx(HKEY_CURRENT_USER, /* Root Key */
                               key,               /* Subkey */
                               0,                 /* Reserved */
                               "",                /* Key Class */
                               TEMPORARY,	  /* Permanace */
                               KEY_ALL_ACCESS,    /* Access */
                               0,		  /* Security Attributes (NT / 2000) */
                               &hkey,             /* Result */
                               &disposition);     /* 1 = new, 2 = opened */
  if(returnValue == ERROR_SUCCESS) {
    return hkey;
  } else {
    printf("Error opening: \"%s\"\n", key);
    return (HKEY)NULL;
  }
#else
  return (HKEY)NULL;
#endif
}

void set_dword(char* key, DWORD value, HKEY hkey) {
  printf("DWord value for %s = %d\n", key, value);
#ifdef WIN32
  RegSetValueEx(hkey,             /* Key Handle */
                key,              /* Value Name */
                0,                /* Reserved */
                REG_DWORD,        /* Type */
                (BYTE*)&value,    /* Data Pointer */
                sizeof(DWORD));   /* Data Size (includes string null) */

  /* Valid types are:
   *   REG_BINARY    Binary data in any form.
   *   REG_DWORD     A 32-bit number.
   *   REG_DWORD_LITTLE_ENDIAN
   *                 A 32-bit number in little-endian format.
   *   REG_DWORD_BIG_ENDIAN
   *                 A 32-bit number in big-endian format.
   *   REG_EXPAND_SZ Null-terminated string that contains unexpanded
   *                  references to environment variables (for example,
   *                  "%PATH%"). It will be a Unicode or ANSI string
   *                  depending on whether you use the Unicode or ANSI
   *                  functions.
   *   REG_LINK      Reserved for system use.
   *   REG_MULTI_SZ  Array of null-terminated strings, terminated by two
   *                  null characters.
   *   REG_NONE      No defined value type.
   *   REG_QWORD     A 64-bit number.
   *   REG_QWORD_LITTLE_ENDIAN [ = REG_QWORD ]
   *                 A 64-bit number in little-endian format.
   *   REG_SZ        Null-terminated string. It will be a Unicode or
   *                  ANSI string, depending on whether you use the
   *                  Unicode or ANSI functions.
   */
#endif
}

void set_text(char* key, char* value, HKEY hkey) {
  printf("String value for %s = %s\n", key, value);
#ifdef WIN32
  RegSetValueEx(hkey,
                key,
                0,
                REG_SZ,
                (LPTSTR)value,
                sizeof(char) * (strlen(value) + 1));
#endif
}
