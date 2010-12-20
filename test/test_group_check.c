#include <stdio.h>
#include <unistd.h>
#include <grp.h>

int main(int argc, char** argv)
{
  struct group* grent;
  char** members;
  int i = 0;

  for(i = 1; i < argc; i++)
    {
      printf("Members of %s:", argv[i]);
      if ((grent = (struct group*)getgrnam (argv[i])) &&
	  (members = grent->gr_mem) != NULL)
	{
	  while (*members)
	    {
	      printf(" %s", *members);
	      members++;
	    }
	}
      printf("\n");
    }
  return argc > 1;
}
