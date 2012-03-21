/***
  This program was originally written by Bill Langston in 1994 and up
    until this point it has been maintained by Paul Tsai. I, Will
    Holcomb, recently redid the checkin program (also originally
    written by Bill Langston) and used the program created there as
    the basic structure for this program. With the checkin I gave
    credit to Bill as author and myself as revisor, but this program
    honestly looks nothing like what he originally had, so:

  Roomcount.c written February 17 & 18 1999 by Will Holcomb. =)

  The major changes over the checkin program is that when a new lab is
   added whoever is updating has to specify how many machines are in
   the lab. The machies are either specified as dumb terminals or as
   pcs. It took me about twice as long to write this program in order
   to allow for that distinction. It is there though (all for the sake
   of the two remaining dumb terminals in CH215.)

  In order to up the coolness factor over the old checkin I added
   defaults so that when you ran the program and specified the
   conditions in the lab the next time it would give you those same
   conditions as default choices which you could select simply by
   hitting enter. Whereas the temperature and the number of reams of
   paper in a lab stay fairly constant in a lab, the numnber of people
   does not, so the defaults didn't make sense for roomcount. Instead
   I added a command line option where people could specify the number
   of people in the lab when they first run the program.

  As with the checkin I still made it so that they have to confirm
   their choice. This prevents the roomcount from getting too
   automatic. =)

  To add a new lab up the #defined NUMBER_LABS and add an entry at the end
   of initialize_labs() for it and you are good to go. (Pretty sweet, eh?
   Come find me and I'll show you the old code. Each lab had its own
   function. It was unique, I'll say that much.) =)

						-Will Holcomb (WJH3957)
*/

/* In order for the autosensing to work HELPDESK_LAB_SET must be defined.
   This is done by typing 

   define/nolog HELPDESK_LAB_SET "CH313"

   or whatever the lab is. This should be taken care of automatically by
   the same program that sets the process name. (WJH3957)
*/

#include <stdio.h>
#include <string.h>
#include <time.h>
#include <signal.h>
#include <ctype.h>
#include <stdlib.h>  /* This program has been edited about 20 times in  */
                     /*  the last 5 years, you would think that in that */
#include <curses.h>  /*  time someone would have known that the exit()  */
#include <limits.h>  /*  function is in stdlib. Apparently not.         */
#include <stddef.h>  /* This program compiled with 25 warnings when I   */
                     /*  started working on it. You ought to notice     */
                     /*  that it is now 0. (You'll have to excuse me, I */
                     /*  get a little cocky when I finish a project. =) */
#ifdef VMS
  #include <unixlib.h>
  #include <processes.h>
#endif

/* term definition file */
#define TERMDATA     "USER:[ACS.ROOMCOUNT]TERMDEF.DAT" 

/* data directory */
#define LOGDIR       "USER:[acs.roomcount]"

/* These nest two define statements affect the nature of the output.
    As of release time both are going to be used to maintain
    backwards compatibility, though there is a definite potential
    for problems in the future especially with the filename output
    because the old filenaming format does not use a name that is
    guaranteed to be unique for each lab. At this time there are no
    conflicts, though if you are the person trying to fix the program
    because you have two labs with the same room number in different
    buildings and the roomvount is writing them both to the same file
    just comment the line #define OLD_FILENAME and the program will
    switch naming systems.
*/

/* If this is defined the program will output in the original format
    comments about the nature of the output can be found in the body
    of the print_to() function. */

#define OLD_OUTPUT

/* If this is defined the program will output with the original
    filename format. Commants about the filename nomencature can
    be found in the body of the print_output() function. */

#define OLD_FILENAME

#define NUMBER_LABS 9
#define LAB_NAME_LENGTH 20
#define LAB_ABBREVIATION_LENGTH 7

#define BUFFER_SIZE 30

typedef struct lab_entry_type {
	char name[LAB_NAME_LENGTH];
	char abbreviation[LAB_ABBREVIATION_LENGTH];
	int number_rooms;
	int* number_pcs;
	int* number_terminals;
}lab_entry;

void initialize_labs(lab_entry lab[]) {
  int i = 0;

  strcpy(lab[i].name, "Clement Hall 313");
  strcpy(lab[i].abbreviation, "CH313");
  lab[i].number_rooms = 4;
  lab[i].number_pcs = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_pcs[0] = 0;
  lab[i].number_pcs[1] = 31;
  lab[i].number_pcs[2] = 31;
  lab[i].number_pcs[3] = 31;
  lab[i].number_terminals = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_terminals[0] = 17;
  lab[i].number_terminals[1] = 0;
  lab[i].number_terminals[2] = 0;
  lab[i].number_terminals[3] = 0;

  i++;

  strcpy(lab[i].name, "Brown Hall 207");
  strcpy(lab[i].abbreviation, "BN207");
  lab[i].number_rooms = 1;
  lab[i].number_pcs = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_pcs[0] = 32;
  lab[i].number_terminals = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_terminals[0] = 0;

  i++;

  strcpy(lab[i].name, "Clement Hall 215");
  strcpy(lab[i].abbreviation, "CH215");
  lab[i].number_rooms = 1;
  lab[i].number_pcs = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_pcs[0] = 35;
  lab[i].number_terminals = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_terminals[0] = 0;

  i++;

  strcpy(lab[i].name, "Henderson Hall 111");
  strcpy(lab[i].abbreviation, "HH111");
  lab[i].number_rooms = 1;
  lab[i].number_pcs = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_pcs[0] = 23;
  lab[i].number_terminals = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_terminals[0] = 0;

  i++;

  strcpy(lab[i].name, "Daniels Hall 203");
  strcpy(lab[i].abbreviation, "DN203");
  lab[i].number_rooms = 1;
  lab[i].number_pcs = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_pcs[0] = 22;
  lab[i].number_terminals = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_terminals[0] = 0;

  i++;

  strcpy(lab[i].name, "Prescott Hall 204");
  strcpy(lab[i].abbreviation, "PH204");
  lab[i].number_rooms = 1;
  lab[i].number_pcs = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_pcs[0] = 32;
  lab[i].number_terminals = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_terminals[0] = 0;

  i++;
    
  strcpy(lab[i].name, "South Hall 128");
  strcpy(lab[i].abbreviation, "SH128");
  lab[i].number_rooms = 1;
  lab[i].number_pcs = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_pcs[0] = 18;
  lab[i].number_terminals = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_terminals[0] = 0;

  i++;

  strcpy(lab[i].name, "Bruner Hall 305");
  strcpy(lab[i].abbreviation, "BR305");
  lab[i].number_rooms = 1;
  lab[i].number_pcs = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_pcs[0] = 30;
  lab[i].number_terminals = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_terminals[0] = 0;

  i++;

  strcpy(lab[i].name, "Pennebaker Hall 211");
  strcpy(lab[i].abbreviation, "PR211");
  lab[i].number_rooms = 1;
  lab[i].number_pcs = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_pcs[0] = 30;
  lab[i].number_terminals = (int*)(malloc(sizeof(int) * lab[i].number_rooms));
  lab[i].number_terminals[0] = 0;

  i++;
}

struct lab_data_type {
  char term[9];
  char date[40];
  char time[40];
  char id[15];
  int  pc_count;
  int  terminal_count;
};

#define TRUE  1
#define FALSE 0

typedef struct lab_data_type LAB_DATA;

void  initialize_data    (LAB_DATA**, int);
short menu (lab_entry*, int);

void quit_signal  (void);
void clrscr       (void);
char yes_or_no    (void);
long int get_response (int);
void check_exit   (char*);

short int count_check (char*);
short int valid_count(int, int, int);

short int check_lab (lab_entry*, int, LAB_DATA*);
char final_check    (lab_entry*, int, LAB_DATA*);
void print_output   (lab_entry*, int, LAB_DATA*);
void print_to       (char*, char*, LAB_DATA, lab_entry, short int);
void print_err      (int, int, int);

int main(int argc, char* argv[]) {
  int       lab_choice = 0;
  LAB_DATA* stat;
  LAB_DATA  defaults;
  lab_entry lab[NUMBER_LABS];
  int       i = 0;

  signal(SIGINT,  (void (*)(int)) quit_signal);
  signal(SIGQUIT, (void (*)(int)) quit_signal);

  initialize_labs(lab);

  while(lab_choice < 1 || lab_choice > NUMBER_LABS)   /* force valid choice */
    lab_choice = menu(lab, NUMBER_LABS);

  initialize_data(&stat, lab[lab_choice - 1].number_rooms);

  clrscr();

  if(argc - 1 == lab[lab_choice - 1].number_rooms)
    for(i = 0; i < lab[lab_choice - 1].number_rooms; i++)
      if(lab[lab_choice - 1].number_pcs[i] > 0)
        stat[i].pc_count = atoi(argv[i + 1]);
      else
        stat[i].terminal_count = atoi(argv[i + 1]);
 

  while(!check_lab(lab, lab_choice, stat));

  print_output(lab, lab_choice, stat);

  return(0); /* keep compiler happy */
}

void initialize_data (LAB_DATA** stat, int number_rooms) {
  LAB_DATA  temp;

  struct tm *time_structure;
  int       termdates[7][2];
  int       thisterm = 0;
  int       i = 0;
  time_t    time_val;
  char      buffer[80];
  char      spam[128];
  FILE      *termdat;

  #ifndef OLD_OUTPUT
    static char   *weekday[7] = {"Sun ", "Mon ", "Tue ",
                               "Wed ", "Thrs", "Fri ",
                               "Sat "};
    static char   *month[12] = {"Jan  ", "Feb  ", "March",
                                "April", "May  ", "June ",
                                "July ", "Aug  ", "Sept ",
                                "Oct  ", "Nov  ", "Dec  "};
    static char   *term[7] = {"Break #1", "Spring  ", "Break #2", "Summer  ",
                              "Break #3", "Fall    ", "Break #1"};
  #else
    static char *weekday[7] = {"SU", "MO", "TU", "WE",
                               "TH", "FR", "SA"};
    static char *term[7] = {"break1", "spring", "break2", "summer",
                            "break3", "fall  ", "break1"};
  #endif

  /* read term data file */

  if ((termdat = fopen(TERMDATA,"r")) == NULL) {
    fprintf(stderr, "Error opening file `%s'.\n", TERMDATA);
    fprintf(stderr, "Please report this to LABMGR.\n");
    exit(0);
  }

  for(i = 0; i < 6; i++) {
    fgets(buffer, 79, termdat);
    sscanf(buffer, "%d %d", &termdates[i][0], &termdates[i][1]);
  }

  /* read and process time */

  time(&time_val);
  time_structure = localtime(&time_val);

  /* term  - fall, summer, spring or break */

  for(i = 0; i < 6; i++)
    if ( (time_structure->tm_mon + 1 >  termdates[i][0])  ||
       ( (time_structure->tm_mon + 1 == termdates[i][0])   &&
         (time_structure->tm_mday    >= termdates[i][1]) ) )
      thisterm++;

  strcpy(temp.term, term[thisterm]);

  /* date in 24 hour format - adds 1 to tm_mon for real month */

  #ifndef OLD_OUTPUT
    sprintf(temp.date, "%s %02d %s %d%02d",
                     weekday[time_structure->tm_wday],
                     time_structure->tm_mday,
                     month[time_structure->tm_mon],
                     (19 + time_structure->tm_year / 100),
                     time_structure->tm_year % 100);

    sprintf(temp.time, "%02d:%02d:%02d",
                     time_structure->tm_hour,
                     time_structure->tm_min,
                     time_structure->tm_sec);
  #else
        sprintf(temp.date, "%s %02d%02d%02d",
                     weekday[time_structure->tm_wday],
                     time_structure->tm_mon + 1,
                     time_structure->tm_mday,
                     time_structure->tm_year % 100);

        sprintf(temp.time,"%02d:%02d",
                     time_structure->tm_hour,
                     time_structure->tm_min);
  #endif

  /* username, read from environment */

  cuserid(temp.id);

  temp.pc_count = -1;
  temp.terminal_count = -1;

  *stat = (LAB_DATA*)(malloc(sizeof(LAB_DATA) * number_rooms));

  for(i = 0; i < number_rooms; i++)
    (*stat)[i] = temp;
}

short int menu(lab_entry lab[], int number_labs)
{
  long int choice = 0;
  int i = 0;
  char lab_autoset[LAB_ABBREVIATION_LENGTH] = {"\0"};

  clrscr();

  if(getenv("HELPDESK_LAB_SET") != '\0')
    strcpy(lab_autoset, getenv("HELPDESK_LAB_SET"));

  if(lab_autoset != NULL)
    for(i = 0; i < number_labs && !choice; i++)
      if(strcmp(lab_autoset, lab[i].abbreviation) == 0)
        choice = i + 1;

  if(!choice)
    printf("Select a lab\n\n");

  for(i = 0; i < number_labs && !choice; i++)
    printf("\t%d)  %s\n", i + 1, lab[i].name);

  if(!choice) {
    printf("\nChoice-> ");
    choice = get_response(-1);
  }

  return (choice);
}

short int check_lab(lab_entry lab[], int lab_number, LAB_DATA stat[]) {
  long int response = -1;
  int i = 0;
  char base_lab_name[LAB_NAME_LENGTH];
  char lab_name[LAB_NAME_LENGTH];
  short first_time = TRUE;

  strcpy(base_lab_name, lab[lab_number - 1].name);

  for(i = 0; i < lab[lab_number - 1].number_rooms; i++) {
    if(lab[lab_number - 1].number_rooms > 1)
      sprintf(lab_name, "%s %c", base_lab_name,
                                 (char)((int)'A' + i));
    else
      strcpy(lab_name, base_lab_name);

    clrscr();

    if(lab[lab_number - 1].number_terminals[i] > 0
     && lab[lab_number - 1].number_pcs[i] > 0) {
      while(!valid_count(stat[i].terminal_count,
       0, lab[lab_number - 1].number_terminals[i])) {
        printf(" \n");
        printf("Number of terminals being used in %s is: ", lab_name);
  
        response = get_response(-1);
        stat[i].terminal_count = response;
  
        if(!valid_count(stat[i].terminal_count,
         0, lab[lab_number - 1].number_terminals[i])) {
          print_err(response, 0, lab[lab_number - 1].number_terminals[i]);
          printf("\n");
        }
      }
  
      while(!valid_count(stat[i].pc_count,
       0, lab[lab_number - 1].number_pcs[i])) {
        printf("Number of PCs being used in %s is: ", lab_name);
 
        response = get_response(-1);
        stat[i].pc_count = response;
  
        if(!valid_count(stat[i].pc_count, 0, lab[lab_number - 1].number_pcs[i])) {
          print_err(response, 0, lab[lab_number - 1].number_pcs[i]);
          printf("\n");
        }
      }
    } else if(lab[lab_number - 1].number_pcs[i] > 0) {
      while(!valid_count(stat[i].pc_count,
       0, lab[lab_number - 1].number_pcs[i])) {
        printf("Roomcount for %s is: ", lab_name);
 
        response = get_response(-1);
        stat[i].pc_count = response;
  
        if(!valid_count(stat[i].pc_count, 0, lab[lab_number - 1].number_pcs[i])) {
          print_err(response, 0, lab[lab_number - 1].number_pcs[i]);
          printf("\n");
        }
      }
    } else {
      while(!valid_count(stat[i].terminal_count,
       0, lab[lab_number - 1].number_terminals[i])) {
        printf("Roomcount for %s is: ", lab_name);
  
        response = get_response(-1);
        stat[i].terminal_count = response;
  
        if(!valid_count(stat[i].terminal_count,
         0, lab[lab_number - 1].number_terminals[i])) {
          print_err(response, 0, lab[lab_number - 1].number_terminals[i]);
          printf("\n");
        }
      }
    }
    response = -1;
  }

  while(response != (int)'Y' && response != (int)'y'
   &&   response != (int)'N' && response != (int)'n'
   &&   response != (int)'1' && response != (int)'0')
    response = (int)final_check(lab, lab_number, stat);

  if(response == (int)'Y' || response == (int)'y'
     || response == (int)'1') {
    return TRUE;
  } else {
    for(i = 0; i < lab[lab_number - 1].number_rooms; i++) {
      stat[i].pc_count = -1;
      stat[i].terminal_count = -1;
    }
    return FALSE;
  }
}

void print_err(int count, int minimum, int maximum) {
  printf("%d is an invalid response.\n", count);
  printf("  The minimum is %d and the maximum is %d\n", minimum, maximum);
}

short int valid_count(int count, int lower_bound, int upper_bound) {
  if(upper_bound <= lower_bound)
    return TRUE;
  else if(count > upper_bound)
    return FALSE;
  else if(count < lower_bound)
    return FALSE;
  else
    return TRUE;
}

char final_check(lab_entry lab[], int lab_number, LAB_DATA stat[]) {
  int i = 0;
  char lab_name[BUFFER_SIZE];

  clrscr();

  for(i = 0; i < lab[lab_number - 1].number_rooms; i++) {
    if(lab[lab_number - 1].number_rooms > 1)
      sprintf(lab_name, "%s %c", lab[lab_number - 1].name,
       (char)((int)'A' + i));
    else
      strcpy(lab_name, lab[lab_number - 1].name);

    if(lab[lab_number - 1].number_pcs[i] > 0
     && lab[lab_number - 1].number_terminals[i] > 0) {
        printf("Number of terminals used in %s is: %d\n", lab_name,
         stat[i].terminal_count);

        printf("Number of PCs used in %s is: %d\n", lab_name,
         stat[i].pc_count);
    } else {
        printf("Roomcount for %s is: %d\n", lab_name,
         ((lab[lab_number - 1].number_pcs[i] > 0)
         ? stat[i].pc_count
         : stat[i].terminal_count));
    }
  }
  
  printf("\n");

  printf("Is this correct? [y/n] ");

  return yes_or_no();
}

/* Takes input */

long int get_response(int defaultchoice) {
  char buffer[BUFFER_SIZE];
  char test = '\0';
  long int response;

  if(defaultchoice >= 0) {
    printf("[%d]: ", defaultchoice);
    scanf("%c", &test);
    ungetc(test, stdin);
  }

  gets(buffer);

  if(test == '\n') {
     response = defaultchoice;
     buffer[0] = '\n';
     buffer[1] = '\0';
  } else if(!isdigit(buffer[0])) {
     response = -1;
  } else {
     sscanf(buffer, "%d", &response);
  }

/* This is a quick little check that I added becasue someone
    was complaining that they liked to be able to exit the
    roomcount by just hitting enter. So, I made it so that if
    they add the line:

    $ define/nolog ROOMCOUNT_EASY_EXIT TRUE

    top their login.com then they'll be able to exit by just
    hitting enter. I suspect that this little feature will
    soon be forgotten, but it takes about a hundredth of a
    second so I reckon I'll let it ride. =)
*/

  if(getenv("ROOMCOUNT_EASY_EXIT") != NULL) 
    check_exit(buffer);

  return response;
}

void check_exit(char *string) { /* see if user hit CTRL-Z; exit if so. */
  if (string[0] == 0)
    quit_signal();
}

void quit_signal(void) { /* handle interrupt signals and terminate */
   printf("\n%c[7m Roomcount Aborted! %c[0m\a\n", 27, 27);
   exit(1);
}

void clrscr() { /*This clears the screen with an ANSI/VT escape sequence.*/
  printf("%c[H%c[2J", 27, 27);
}

char yes_or_no() {
   char buffer[BUFFER_SIZE];

   gets(buffer);

   return(buffer[0]);
}

void print_output(lab_entry lab[], int lab_number, LAB_DATA stat[]) {
  char  filename[80];
  char  base[BUFFER_SIZE];
  char  lab_name[BUFFER_SIZE];
  FILE* output_file = NULL;
  int i = 0;
  int j = 0;
  lab_entry temp_lab_entry;

  /* In an effort to maintain two different nomenclatures for the
     filenames I am simply going to write two different versions of
     this function that will compile conditionally upon the
     definition of OLD_FILENAME */

  /* The first version uses just the number of the lab as the name and
     sublabs are represented with a letter after the number of the
     lab. This scheme does not allow for labs in two different
     buildings to be in a room with the same number. Also the names
     are short and not easy to understand unless you know what they
     represent. But that is what is wanted so I will code it. */
  
  #ifdef OLD_FILENAME 
    for(i = 0, j = 0; i < strlen(lab[lab_number - 1].abbreviation); i++)
      if(isdigit(lab[lab_number - 1].abbreviation[i])) {
        base[j] = lab[lab_number - 1].abbreviation[i];
        j++;
      }

    base[j] = '\0';

    for(i = 0; i < lab[lab_number - 1].number_rooms; i++) {
      strcpy(filename, LOGDIR);  
      strcpy(lab_name, base);
  
      temp_lab_entry = lab[lab_number - 1];

      if(lab[lab_number - 1].number_rooms > 1) {
        sprintf(lab_name, "%s%c", lab_name, (char)((int)'A' + i));
        sprintf(temp_lab_entry.abbreviation,
                "%s%c",
                temp_lab_entry.abbreviation,
                (char)((int)'A' + i));
      }
  
      strcat(filename, lab_name);
  
      if(lab[lab_number - 1].number_terminals[i] > 0
       && lab[lab_number - 1].number_pcs[i] > 0) {
        strcat(filename, "VAX.dat");
        print_to(filename, lab_name, stat[i], temp_lab_entry, TRUE);
  
        strcpy(filename, LOGDIR);  
        strcat(filename, lab_name);
        
        strcat(filename, "PC.dat");
        print_to(filename, lab_name, stat[i], temp_lab_entry, FALSE);
      } else {
        strcat(filename, ".dat");
  
        if(lab[lab_number - 1].number_pcs[i] > 0)
          print_to(filename, lab_name, stat[i], temp_lab_entry, FALSE);
        else
          print_to(filename, lab_name, stat[i], temp_lab_entry, TRUE);
      }
    }

  /* The second naming scheme is my personal creation. It is not all
     that different really, but I think it more readable and it does
     use the same abbreviation for the filename that is generally used
     for the lab. */

  #else

    strcpy(base, lab[lab_number - 1].abbreviation);
  
    for(i = 0; i < lab[lab_number - 1].number_rooms; i++) {
      strcpy(filename, LOGDIR);  
      strcpy(lab_name, base);
  
      temp_lab_entry = lab[lab_number - 1];

      if(lab[lab_number - 1].number_rooms > 1) {
        sprintf(lab_name, "%s%c", lab_name, (char)((int)'A' + i));
        sprintf(temp_lab_entry.abbreviation,
                "%s%c",
                temp_lab_entry.abbreviation,
                (char)((int)'A' + i));
      }
  
      strcat(filename, lab_name);
  
      if(lab[lab_number - 1].number_terminals[i] > 0
       && lab[lab_number - 1].number_pcs[i] > 0) {
        strcat(filename, "_terminals.log");
        print_to(filename, lab_name, stat[i], temp_lab_entry, TRUE);
  
        strcpy(filename, LOGDIR);  
        strcat(filename, lab_name);
        
        strcat(filename, "_pcs.log");
        print_to(filename, lab_name, stat[i], temp_lab_entry, FALSE);
      } else {
        strcat(filename, "_roomcount.log");
  
        if(lab[lab_number - 1].number_pcs[i] > 0)
          print_to(filename, lab_name, stat[i], temp_lab_entry, FALSE);
        else
          print_to(filename, lab_name, stat[i], temp_lab_entry, TRUE);
      }
    }
  #endif
}

void print_to(char* filename, char* lab_name,
              LAB_DATA stat, lab_entry lab, short int print_terminals) {
  FILE* output_file = NULL;

  output_file = fopen(filename, "a");

  if(output_file == NULL) {
    printf("Error opening %s, roomcount not recorded\n");
    exit(0);
  }

/* In order to maintain compatibility with the statistics program
    being used, I have coded two different versions of the output.
    The first looks like this:

    term   date      time   count username    lab   year
    spring TU 031699 08:23  2    WJH3957      CH313 1999
*/

  #ifdef OLD_OUTPUT
    fprintf(output_file," %6s %6s %5s %2d %-10s %-10s %s%s\n",
                         stat.term,
                         stat.date,
                         stat.time,
                         ((print_terminals)
                          ? stat.terminal_count : stat.pc_count),
                         stat.id,
                         lab.abbreviation,
                         (((int)stat.date[7] != (int)'9') ? "20" : "19"),
                         &stat.date[7]);

/* The revised output is nearly identical to that used in the checkin
    program and is designed to be more human readable. It looks like
    this:

    username   lab   term     date                time      count
    WJH3957    CH313 Spring   Tue  16 March 1999, 08:25:39 12
*/

  #else
    fprintf(output_file, "%-10s %s %s %s, %s %02d\n",
                         stat.id,
                         lab.abbreviation,
                         stat.term,
                         stat.date,
                         stat.time,
                         ((print_terminals)
                          ? stat.terminal_count : stat.pc_count));
  #endif

  fclose(output_file);
}
