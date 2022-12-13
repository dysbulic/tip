/* Check-in program for use by Helpdesk

   Bill Langston (LABMGR), February 1996
   Academic Computing Support, D.W. Mattson Computer Center
   (Updated and adjusted by Jim Davis (JAD7084))
   (Support for Clement Hall 215 added by Elijah Wright (ELW9354) January 1997)
   (Support for Daniels Hall 203 added by Elijah Wright (ELW9354) January 1998)
   (Support for Prescott Hall 204 added by Danyel Bruggink (DMB1153) August 98)

   Last Revision: 21 Feb 1996 - wfl
   Last Revision: 21 Mar 1996 - jad
   Last Revision: 13 Jan 1997 - elw (Linuxboy) Added CH215 to options.
   Last Revision: 9 Jan 1998 - elw Added DH203 to options.
   Last Revision: 17 Apr 1998 - dmb 
   Last Revision: 17 Aug 1998 - dmb
   Last Revision: 4 Jan 1999 - dmb Added SH 128 to options

   Program gutted and completely redone January 28 - February 3 1999
    by dysbulic. The quit_signal and check_exit functions are
    the only remnants of the original program.

  To add a new lab up the #defined NUMBER_LABS and add an entry at the end
   of initialize_labs() for it and you are good to go. (Pretty sweet, eh?
   Come find me and I'll show you the old code. Each lab had its own
   function. It was unique, I'll say that much.) =)
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
#include <stdlib.h>
#include <curses.h>
#include <limits.h>
#include <stddef.h>

#ifdef VMS
  #include <processes.h>
  #include <unixlib.h>
#endif

/* term definition file */
#define TERMDATA     "USER:[ACS.ROOMCOUNT]TERMDEF.DAT" 

/* data directory */
#define LOGDIR       "USER:[labmgr.checkin]"

/* defaults file */
#define DEFAULTSFILE "SYS$SCRATCH:helpdesk_checkin.defaults"

#define NUMBER_LABS 9
#define LAB_NAME_LENGTH 20
#define LAB_ABBREVIATION_LENGTH 7

#define BUFFER_SIZE 30

#define DEFAULT_BUSYNESS 3
#define DEFAULT_REAMS    2
#define DEFAULT_STATUS   1
#define DEFAULT_TEMP     1

typedef struct lab_entry_type
{
	char name[LAB_NAME_LENGTH];
	char abbreviation[LAB_ABBREVIATION_LENGTH];
	int number_rooms;
}lab_entry;

void initialize_labs(lab_entry lab[]) {
  strcpy(lab[0].name, "Clement Hall 313");
  strcpy(lab[0].abbreviation, "CH313");
  lab[0].number_rooms = 4;

  strcpy(lab[1].name, "Brown Hall 207");
  strcpy(lab[1].abbreviation, "BN207");
  lab[1].number_rooms = 1;

  strcpy(lab[2].name, "Clement Hall 215");
  strcpy(lab[2].abbreviation, "CH215");
  lab[2].number_rooms = 1;

  strcpy(lab[3].name, "Henderson Hall 111");
  strcpy(lab[3].abbreviation, "HH111");
  lab[3].number_rooms = 1;

  strcpy(lab[4].name, "Daniels Hall 203");
  strcpy(lab[4].abbreviation, "DN203");
  lab[4].number_rooms = 1;

  strcpy(lab[5].name, "Prescott Hall 204");
  strcpy(lab[5].abbreviation, "PH204");
  lab[5].number_rooms = 1;

  strcpy(lab[6].name, "South Hall 128");
  strcpy(lab[6].abbreviation, "SH128");
  lab[6].number_rooms = 1;

  strcpy(lab[7].name, "Bruner Hall 305");
  strcpy(lab[7].abbreviation, "BR305");
  lab[7].number_rooms = 1;

  strcpy(lab[8].name, "Pennebaker Hall 211");
  strcpy(lab[8].abbreviation, "PR211");
  lab[8].number_rooms = 1;
}

struct lab_data_type {
  char term[9];
  char date[40];
  char time[40];
  char id[15];
  int busyness;
  int reams;
  int status;
  int temp;
};

typedef struct lab_data_type LAB_DATA;

void  initialize_defaults(LAB_DATA*);
void  initialize_data    (LAB_DATA**, int);

short menu (lab_entry*, int);

void quit_signal  (void);
void clrscr       (void);
char yes_or_no    (void);
int  get_response (int);
void check_exit   (char*);

int equip_check (char*, LAB_DATA);
int paper_check (char*, LAB_DATA);
int temp_check  (char*, LAB_DATA);
int busy_check  (char*, LAB_DATA);

int  check_lab     (lab_entry*, int, LAB_DATA*, LAB_DATA);
char final_check   (lab_entry*, int, LAB_DATA*);
void print_output  (lab_entry*, int, LAB_DATA*);
void save_defaults (LAB_DATA);

int main() {
  int       lab_choice = 0;
  LAB_DATA* stat;
  LAB_DATA  defaults;
  lab_entry lab[NUMBER_LABS];

  signal(SIGINT,  (void (*)(int)) quit_signal);
  signal(SIGQUIT, (void (*)(int)) quit_signal);

  initialize_labs(lab);

  while(lab_choice < 1 || lab_choice > NUMBER_LABS)   /* force valid choice */
    lab_choice = menu(lab, NUMBER_LABS);

  initialize_defaults(&defaults);
  initialize_data(&stat, lab[lab_choice - 1].number_rooms);
  
  clrscr();

  while(!check_lab(lab, lab_choice, stat, defaults));

  print_output(lab, lab_choice, stat);

  return(0); /* keep compiler happy *//* Jolly little compiler! */
             /* Jolly little compiler my left eye! Only Dec CC and  */ 
             /* Turbo C require return(0) or bust... Oh well, at    */
             /* least they forcibly uphold standards! */
             /* Stupid K&R syntax of this program is starting to *really*
                 get on my nerves....  */
}

void initialize_defaults(LAB_DATA *defaults) {
  FILE* defaults_file = NULL;
  char  buffer[81];

  if((defaults_file = fopen(DEFAULTSFILE, "r")) != NULL) {
    fgets(buffer, 80, defaults_file);
    sscanf(buffer, "%d", &(defaults->status));
    fgets(buffer, 80, defaults_file);
    sscanf(buffer, "%d", &(defaults->reams));
    fgets(buffer, 80, defaults_file);
    sscanf(buffer, "%d", &(defaults->temp));
    fgets(buffer, 80, defaults_file);
    sscanf(buffer, "%d", &(defaults->busyness));

    fclose(defaults_file);
  } else {
    defaults->status = DEFAULT_STATUS;
    defaults->reams = DEFAULT_REAMS;
    defaults->temp = DEFAULT_TEMP;
    defaults->busyness = DEFAULT_BUSYNESS;
  }

  *(defaults->term) = '\0';
  *(defaults->date) = '\0';
  *(defaults->time) = '\0';
  *(defaults->id) = '\0';
}

void initialize_data (LAB_DATA** stat, int number_rooms) {
  LAB_DATA      temp;

  struct tm     *time_structure;
  int           termdates[7][2];
  int           thisterm = 0;
  int           i = 0;
  time_t        time_val;
  char          buffer[80];
  char          spam[128];
  static char   *weekday[7] = {"Sun ", "Mon ", "Tue ",
                               "Wed ", "Thrs", "Fri ",
                               "Sat "};
  static char   *month[12] = {"Jan  ", "Feb  ", "March",
                              "April", "May  ", "June ",
                              "July ", "Aug  ", "Sept ",
                              "Oct  ", "Nov  ", "Dec  "};
  static char   *term[7] = {"Break #1", "Spring  ", "Break #2", "Summer  ",
                            "Break #3", "Fall    ", "Break #1"};
  FILE          *termdat;

  /* read term data file */
  if ((termdat = fopen(TERMDATA, "r")) == NULL) {
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

  /* username, read from environment */
  cuserid(temp.id);

  temp.busyness = -1;
  temp.reams = -1;
  temp.status = -1;
  temp.temp = -1;

  *stat = (LAB_DATA*)(malloc(sizeof(LAB_DATA) * number_rooms));

  for(i = 0; i < number_rooms; i++)
    (*stat)[i] = temp;
}

short menu(lab_entry lab[], int number_labs) {
  int  choice = 0;
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

int check_lab(lab_entry lab[], int lab_number,
              LAB_DATA stat[], LAB_DATA defaults) {
  int response = -1;
  int i = 0;
  char base_lab_name[LAB_NAME_LENGTH];
  char lab_name[LAB_NAME_LENGTH];

  strcpy(base_lab_name, lab[lab_number - 1].name);

  for(i = 0; i < lab[lab_number - 1].number_rooms; i++) {
    if(lab[lab_number - 1].number_rooms > 1)
      sprintf(lab_name, "%s %c", base_lab_name,
                                 (char)((int)'A' + i));
    else
      strcpy(lab_name, base_lab_name);

    while(response < 1 || response > 3) 
      response = equip_check(lab_name, defaults);

    stat[i].status = response;
    response = -1;
  }

  i = 0;

  if(stat[i].status != 3) { /* If the lab is not reserved */
    strcpy(lab_name, base_lab_name);

    while(response < 0 || response > 3)
      response = paper_check(lab_name, defaults);

    stat[i].reams = response;
    response = -1;
  }

  for(i = 0; i < lab[lab_number - 1].number_rooms; i++) {
    if(lab[lab_number - 1].number_rooms > 1)
      sprintf(lab_name, "%s %c", base_lab_name,
                                 (char)((int)'A' + i));
    else
      strcpy(lab_name, base_lab_name);

    if(stat[i].status != 3) { /* If the lab is not reserved */
      while(response < 1 || response > 3) 
        response = temp_check(lab_name, defaults);

      stat[i].temp = response;
      response = -1;
    }
  }

  i = 0;

  if(stat[i].status != 3) { /* If the lab is not reserved */
    strcpy(lab_name, base_lab_name);

    while(response < 1 || response > 5)
      response = busy_check(lab_name, defaults);

    stat[i].busyness = response;
    response = -1;
  }

  while(response != (int)'Y' && response != (int)'y'
   &&   response != (int)'N' && response != (int)'n'
   &&   response != (int)'1' && response != (int)'0')
    response = (int)final_check(lab, lab_number, stat);

  if(response == (int)'Y' || response == (int)'y'
   || response == (int)'1')
    return TRUE;
  else
    return FALSE;
}

int equip_check(char* lab_name, LAB_DATA defaults) {
   clrscr();

   printf("I certify that I have checked all equipment in\n"
          "    %s and:\n", lab_name);
   printf("\n");
   printf("1: All hardware is working and accounted for\n");
   printf("2: Missing or broken hardware has been reported\n");
   printf("3: This lab was reserved for a class\n");
   printf("\n");
   printf("Enter a number between 1 and 3: ");

   return get_response(defaults.status);
}

int paper_check(char* lab_name, LAB_DATA defaults) {
   clrscr();

   printf("All paper trays in the %s Laser Printer are\n"
          "    loaded and there are ___ extra reams of laser paper:\n",
          lab_name);
   printf("\n");
   printf("0: I have arranged for more paper to be delivered\n");
   printf("1: One ream of EXTRA laser paper is available\n");
   printf("2: Two reams of EXTRA laser paper are available\n");
   printf("3: Three reams of EXTRA laser paper are available\n");
   printf("\n");
   printf("Enter a number between 0 and 3: ");

   return get_response(defaults.reams);
}

int temp_check(char* lab_name, LAB_DATA defaults) {
   clrscr();

   printf("I have checked the thermostat in %s, and it is:\n",
          lab_name);
   printf("\n");
   printf("1: Functioning properly at approx 70-75 degrees\n");
   printf("2: Set at approx 70-75, but the room is too warm\n");
   printf("3: Set at approx 70-75, but the room is too cold\n");
   printf("\n");
   printf("Enter a number between 1 and 3: ");

   return get_response(defaults.temp);
}

int busy_check(char* lab_name, LAB_DATA defaults) {
   clrscr();

   printf("How busy are you in %s\n", lab_name);
   printf("\n");
   printf("1 = not busy at all\n");
   printf("  .\n");
   printf("  .\n");
   printf("  .\n");
   printf("5 = extremely busy\n");
   printf("\n");
   printf("Enter a number between 1 and 5: ");

   return get_response(defaults.busyness);
}

char final_check(lab_entry lab[], int lab_number, LAB_DATA stat[]) {
  int i = 0;

  clrscr();

  printf("You reported your status in %s as:\n", lab[lab_number - 1].name);
  printf("\n");

  for(i = 0; i < lab[lab_number - 1].number_rooms; i++) {
    if(lab[lab_number - 1].number_rooms > 1)
      printf("In %s %c:\n", lab[lab_number - 1].name, (char)((int)'A' + i));

    printf("\t%d: %s\n", stat[i].status,
            (stat[i].status == 1) ? "All hardware is working and accounted for"
          : (stat[i].status == 2) ? "Missing or broken hardware has been reported"
          : (stat[i].status == 3) ? "This lab is reserved for a class"
          : "Status Report Error!");
  }

  i = 0;

  if(stat[i].status != 3) { /* If the lab is not reserved */
    if(lab[lab_number - 1].number_rooms > 1)
      printf("For all of %s:\n", lab[lab_number - 1].name);

    printf("\t%d: %s\n", stat[i].reams,
          (stat[i].reams == 0) ? "I have arranged for more paper to be delivered"
        : (stat[i].reams == 1) ? "I have one ream of extra laser paper"
        : (stat[i].reams == 2) ? "I have two reams of extra laser paper"
        : (stat[i].reams == 3) ? "I have three reams of extra laser paper"
        : "Reams Report Error!");


    for(i = 0; i < lab[lab_number - 1].number_rooms; i++) {
      if(lab[lab_number - 1].number_rooms > 1)
        printf("In %s %c:\n", lab[lab_number - 1].name, (char)((int)'A' + i));
      if(stat[i].status != 3) /* If the lab is not reserved */
        printf("\t%d: %s\n", stat[i].temp,
               (stat[i].temp == 1) ? "The thermostat is functioning properly "
                                     "at approximately 70-75 degrees"
             : (stat[i].temp == 2) ? "The thermostat is set at approx 70-75, "
                                     "but the room is too warm"
             : (stat[i].temp == 3) ? "The thermostat is set at approx 70-75, "
                                     "but the room is too cold"
             : "Temperature Report Error!");
      else
        printf("\t%d: This lab is reserved for a class\n", stat[i].status);
    }

    i = 0;

    if(lab[lab_number - 1].number_rooms > 1) {
      printf("For all of %s:\n", lab[lab_number - 1].name);
    }

    printf("\t%d: %s\n", stat[i].busyness,
          (stat[i].busyness == 1) ? "I am not busy at all"
        : (stat[i].busyness == 2) ? "I am a little busy"
        : (stat[i].busyness == 3) ? "I am kinda busy"
        : (stat[i].busyness == 4) ? "I am fairly busy"
        : (stat[i].busyness == 5) ? "I am extremely busy"
        : "Busyness Report Error!");
  }
  
  printf("\n");
  printf("Is this correct? [y/n] ");

  return yes_or_no();
}

/* Takes input */

int get_response(int defaultchoice) {
  char buffer[BUFFER_SIZE];
  char test = '\0';
  int response;

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
  }
  else if(!isdigit(buffer[0]))
     response = -1;
  else
     sscanf(buffer, "%d", &response);

  check_exit(buffer);

  return response;
}

void check_exit(char *string) { /* see if user hit CTRL-Z; exit if so. */
  if (string[0] == 0)
    quit_signal();
}

void quit_signal(void) { /* handle interrupt signals and terminate */
   printf("\n%c[7m Check-In Aborted! %c[0m\a\n", 27, 27);
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
  FILE* output_file = NULL;
  int i = 0;

  for(i = 0; i < lab[lab_number - 1].number_rooms; i++) {
    strcpy(filename, LOGDIR);  
    strcat(filename, lab[lab_number - 1].abbreviation);

    if(lab[lab_number - 1].number_rooms > 1)
      sprintf(filename, "%s%c", filename, (char)((int)'A' + i));

    strcat(filename, "_checkin.log");

    output_file = fopen(filename, "a");

    fprintf(output_file, "%-10s %s %s %s, %s %d %d %d %d\n",
                         stat[i].id,
                         lab[lab_number - 1].abbreviation,
                         stat[i].term,
                         stat[i].date,
                         stat[i].time,
                         (stat[i].status >= 0)    ? stat[i].status   : 0,
                         (stat[i].reams >= 0)     ? stat[i].reams    : 0,
                         (stat[i].temp >= 0)      ? stat[i].temp     : 0,
                         (stat[i].busyness >= 0) ? stat[i].busyness : 0);
    fclose(output_file);
  }
  save_defaults(stat[0]);
}

void save_defaults(LAB_DATA stat) {
  FILE* defaults_file = NULL;

  if((defaults_file = fopen(DEFAULTSFILE, "w")) != NULL) {
    fprintf(defaults_file, "%d ! This file saves the options used the\n",
            stat.status);
    fprintf(defaults_file, "%d !  last time that the helpdesk checkin\n",
            stat.reams);
    fprintf(defaults_file, "%d !  program was run and reads them in to\n",
            stat.temp);
    fprintf(defaults_file, "%d !  form the defaults the next time.\n",
            stat.busyness);

    fclose(defaults_file);
  }
}
