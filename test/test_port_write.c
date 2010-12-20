/**
 * My little brother has an epox EP-7KXA board.
 * http://www.epox.com/html/motherboard.asp?product=EP-7KXA
 * On it there is a little two digit led monitor for
 *  showing the post codes. According to the documentation
 *  if a program writes to io port 0x80 then it will appear
 *  on the monitor. (In general writing to 0x80 should have
 *  no affect on the system.)
 * This programs just sends all the characters that come in
 *  as arguments to the port. There is a little pause in
 *  between each defined by the -l (letter_wait) and
 *  -w (word_wait) arguments.
 * Also, for testing sake, if the program is invoved with
 *  the name "print_write" it will print to the stdout
 *  rather than the port.
 *
 * 2002/10/24 wjh
 */

#ifdef LINUX
#include <sys/io.h>
#endif

#include <unistd.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

typedef unsigned char byte;
typedef unsigned short boolean;

#define true 1
#define false 0

#define PORT 0x80       /* Port to use */

/* Time between letters in seconds */
#define LETTER_WAIT .5

/* Time between words in seconds */
#define WORD_WAIT 1.5

/* Converts the waits to the appropriate value for the
 *  function being used. Sleep supposedly takes decimal
 *  values, but it does not seem to work correctly with
 *  ones starting with 0
 * Currently converts micro seconds (10^-6) to seconds
 */
#define WAIT_MULTIPLIER 1000000

/* Use the usleep (microsecond precision) where the
 * sleep (second presision) function is called
 */
#define sleep usleep

int main(int argc, char** argv)
{
  #ifdef LINUX
  int io_status = ioperm(PORT,  /* port number 0x000 - 0x3ff */
                         1,     /* number of ports to access */
                         true); /* boolean true to gain,
                                   false to release */
  #else
  int io_status = -1;
  #endif

  /* Having gained access to the port the root privileges
     may be dropped if this program is being run suid */
  int drop_status = setuid(getuid());

  /* Test the name to know how to run */
  int print = strcmp(argv[0], "print_write") == 0 ? true : false;

  /* Time to wait between letters */
  int letter_wait = LETTER_WAIT * WAIT_MULTIPLIER;

  /* Time to wait between words */
  int word_wait = WORD_WAIT * WAIT_MULTIPLIER;

  /* Used to track the position in the string */
  int charind;

  /* Part of using get_opt; tracks the return status */
  int opt_status = 0;
  do
    {
      opt_status = getopt(argc, argv, "l:w:");
      switch(opt_status)
        {
        case 'l':
          letter_wait =
            strtod(optarg, (char**)NULL) * WAIT_MULTIPLIER;
          break;
        case 'w':
          word_wait =
            strtod(optarg, (char**)NULL) * WAIT_MULTIPLIER;
          break;
        }
    }
  while(opt_status >= 0);

  if(io_status == 0)
    {
      for(charind = 0; optind < argc; charind = 0)
        {
          while(argv[optind][charind] != (char)NULL)
            {
              byte value =
                (byte)argv[optind][charind++];
              if(print)
                {
                  putchar(value);
                  fflush(stdout);
                }
              else
                {
                  #ifdef LINUX
                  outb(value, PORT);
                  #endif
                }
              sleep(letter_wait);
            }
          if(++optind < argc)
            {
              if(print)
                {
                  putchar(' ');
                }
              if(letter_wait < word_wait)
                {
                  sleep(word_wait - letter_wait);
                }
            }
        }
    }
  if(print)
    {
      putchar('\n');
    }
  return io_status;
}
