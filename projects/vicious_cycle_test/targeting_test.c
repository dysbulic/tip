/**
 * Author: Will Holcomb <wholcomb@gmail.com>
 * Date: 10 February 2007
 *
 * Projectile Leading Problem: Given an initial shot position (Spos),
 * a constant shot speed (Sspeed), an initial target position (Tpos),
 * and a constant target velocity (Tvel) derive the equation for
 * calculating the shot direction (Sdir) such that at some time (t)
 * the shot would hit the target.
 *
 * An eplanation for the mathematics used here is at:
 *  http://odin.himinbi.org/vicious_cycle_test/projectile_targeting.php
 */
#include <stdio.h>
#include <stdlib.h>
#include <math.h>
#include <string.h>

#define MINLOOPS 1
#define MAXLOOPS 3
#define MAXLEN 20
#define MAXSPEED 30
#define NUMFUNC 2

extern float flight_time(float*, float*, float, float*);
extern float* flight_direction(float*, float*, float, float*);
extern float projectileTime(float*, float*, float, float*);
extern float* projectileDirection(float*, float*, float, float*);

inline void randomize_vector(float* vector) {
  vector[0] = MAXLEN * (rand() / (float)RAND_MAX);
  vector[1] = MAXLEN * (rand() / (float)RAND_MAX);
  vector[2] = MAXLEN * (rand() / (float)RAND_MAX);
}

int main() {
  float initial_projectile[] = { 0, 0, 0 };
  float initial_target[] = { 3, 0, 0 };
  float projectile_speed = 2;
  float target_velocity[] = { 1, 0, 0 };
  char* output = malloc(sizeof(char) * 9);
  float (*times[])(float*, float*, float, float*) = { flight_time, projectileTime };
  float* (*directions[])(float*, float*, float, float*) = { flight_direction, projectileDirection };
  
  int i, j;
  for(i = (int)(MINLOOPS + (MAXLOOPS - MINLOOPS) * (rand() / (float)RAND_MAX)); i >= 0; i--) {
    printf("p0:[%0.2f, %0.2f, %0.2f] v t0:[%0.2f, %0.2f, %0.2f] @ Sp:%0.2f & Vt:[%0.2f, %0.2f, %0.2f]\n",
           initial_projectile[0], initial_projectile[1], initial_projectile[2],
           initial_target[0], initial_target[1], initial_target[2],
           projectile_speed, target_velocity[0], target_velocity[1], target_velocity[2]);
    for(j = 0; j < NUMFUNC; j++) {
      float time = (times[j])(initial_projectile, initial_target,
                              projectile_speed, target_velocity);
      
      if(time >= 0) sprintf(output, "%0.2f", time); else strcpy(output, "too fast");

      float* direction = (directions[j])(initial_projectile, initial_target,
                                         projectile_speed, target_velocity);
      
      printf("  [%0.2f, %0.2f, %0.2f] for %0.2f => [%0.2f, %0.2f, %0.2f]\n",
             direction[0], direction[1], direction[2], time,
             (initial_target[0] + target_velocity[0] * time) - (initial_projectile[0] + direction[0] * projectile_speed * time),
             (initial_target[1] + target_velocity[1] * time) - (initial_projectile[1] + direction[1] * projectile_speed * time),
             (initial_target[2] + target_velocity[2] * time) - (initial_projectile[2] + direction[2] * projectile_speed * time));
      free(direction);
    }
    randomize_vector(initial_projectile);
    randomize_vector(initial_target);
    randomize_vector(target_velocity);
    projectile_speed = MAXSPEED * (rand() / (float)RAND_MAX);
  }
  return 0;
}
