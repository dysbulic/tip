#include <math.h>
#include <stdio.h>
#include <stdlib.h>
#include <sys/time.h>

/**
 * The problem is, given a slope and the length of the
 * hypotenuse of a right triangle, find the lengths of the
 * legs.
 *
 * This is  program to test the performance difference
 * between using trig to compute those values versus
 * using identities drived using a similar triangle.
 *
 * This is a port of SlopePerformanceTest.java
 */

#define NUM_TESTS 5
#define MAX_SLOPE 100
#define LENGTH 100

int main(int argc, char** argv)
{
  if(argc == 1)
    {
      printf("Usage %s <iterations>\n", argv[0]);
      printf(" iterations is the number of slopes to generate\n");
      return -1;
    }

  char* test_names[NUM_TESTS];
  test_names[0] = "Trigonometric Test #1";
  test_names[1] = "Trigonometric Test #2";
  test_names[2] = "Identity Test";
  test_names[3] = "Countdown Identity Test";
  test_names[4] = "Empty Loop Test";
  
  int num_slopes = atoi(argv[1]);
  double* slopes = malloc(num_slopes * sizeof(double));
  int i;

  for(i = 0; i < num_slopes; i++)
    {
      slopes[i] = MAX_SLOPE * rand() / (RAND_MAX + 1.0);
    }

  printf("Generated %d random slope%c\n", num_slopes,
         num_slopes == 1 ? '\0' : 's');

  long times[NUM_TESTS];
  int test = 0;
  struct timeval start_time, end_time;

  for(test = 0; test < NUM_TESTS; test++)
    {
      double x, y;
      gettimeofday(&start_time, NULL);
      printf("Running test: \"%s\"\n", test_names[test]);
      
      switch(test)
        {
        case 0:
          for(i = 0; i < num_slopes; i++)
            {
              double theta = atan(slopes[i]);
              x = LENGTH * cos(theta);
              y = LENGTH * sin(theta);
            }
          break;
        case 1:
          for(i = 0; i < num_slopes; i++)
            {
              double theta = atan(slopes[i]);
              x = LENGTH * cos(theta);
              y = slopes[i] * x;
            }
          break;
        case 2:
          for(i = 0; i < num_slopes; i++)
            {
              x = LENGTH / sqrt(1 + slopes[i] * slopes[i]);
              y = slopes[i] * x;
            }
          break;
        case 3:
          for(i = num_slopes - 1; i >= 0; i--)
            {
              x = LENGTH / sqrt(1 + slopes[i] * slopes[i]);
              y = slopes[i] * x;
            }
          break;
        case 4:
          for(i = 0; i < num_slopes; i++)
            {
            }
          break;
        }
      gettimeofday(&end_time, NULL);
      times[test] = (end_time.tv_usec - start_time.tv_usec +
                     (end_time.tv_sec - start_time.tv_sec) * 1000000);
    }

  free(slopes);

  int fastest_index = 0;
  for(i = 1; i < NUM_TESTS; i++)
    {
      if(times[i] < times[fastest_index])
        {
          fastest_index = i;
        }
    }
        
  printf("The fastest time was %s\n", test_names[fastest_index]);
        
  printf("The times for each test were:\n");
  for(i = 0; i < NUM_TESTS; i++)
    {
      long delta = times[i] - times[fastest_index];
      double factor = (double)times[i] / (double)times[fastest_index];
      printf("  %lus ", times[i]);
      printf("[+%lus] ", delta);
      if(factor != 1)
        {
          printf("(%0.4f) ", factor);
        }
      printf("%s\n", test_names[i]);
    }
  return 0;
}
            
                
