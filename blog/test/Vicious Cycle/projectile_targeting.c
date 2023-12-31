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
#include <math.h>
#include <stdio.h>
#include <stdlib.h>
#include <time.h>

#define DEBUG

#define max(a, b) ((a) > (b) ? (a) : (b))
#define sqr(x) ((x) * (x))

float* point_subtract(float* one, float* two) {
  float* new = malloc(3 * sizeof(float));
  new[0] = one[0] - two[0];
  new[1] = one[1] - two[1];
  new[2] = one[2] - two[2];
  return new;
}

inline float square_sum(float* vector) {
  return sqr(vector[0]) + sqr(vector[1]) + sqr(vector[2]);
}

inline float vector_magnitude(float* vector) {
  return sqrt(square_sum(vector));
}

void vector_normalize(float* vector) {
  float magnitude = vector_magnitude(vector);
  if(magnitude == 0) {
    vector[0] = vector[1] = vector[2] = 0;
  } else {
    vector[0] /= magnitude;
    vector[1] /= magnitude;
    vector[2] /= magnitude;
  }
}

float* vector_scale(float* vector, float factor) {
  float* newvector = malloc(3 * sizeof(float));
  if(factor == 0) {
    newvector[0] = newvector[1] = newvector[2] = 0;
  } else {
    newvector[0] = vector[0] * factor;
    newvector[1] = vector[1] * factor;
    newvector[2] = vector[2] * factor;
  }
  return newvector;
}

inline float dot_product(float* one, float* two) {
  return one[0] * two[0] + one[1] * two[1] + one[2] * two[2];
}

float flight_time(float* initial_projectile, float* initial_target,
                  float projectile_speed, float* target_velocity) {
  float* base = point_subtract(initial_target, initial_projectile);
  float target_speed = vector_magnitude(target_velocity);
  float* target_direction = vector_scale(target_velocity, 1 / target_speed);

  #ifdef DEBUG
  printf("d:[%0.2f, %0.2f, %0.2f] + T:[%0.2f, %0.2f, %0.2f] = [%0.2f, %0.2f, %0.2f] x %0.2f\n",
         base[0], base[1], base[2],
         target_velocity[0], target_velocity[1], target_velocity[2],
         target_direction[0], target_direction[1], target_direction[2],
         target_speed);
  #endif

  float speed_differential = sqr(target_speed) - sqr(projectile_speed);
  float adj_target_speed = target_speed * dot_product(base, target_direction);

  #ifdef DEBUG
  printf("diff:%0.2f adj:%0.2f\n", speed_differential, adj_target_speed);
  #endif

  //float base_squared = square_sum(base);
  //float rad = sqrt(sqr(adj_target_speed) - speed_differential * base_squared);
  float rad = sqrt(sqr(adj_target_speed) - speed_differential * sqr(vector_magnitude(base)));
  //float time[] = { (adj_target_speed + rad) / speed_differential, (adj_target_speed - rad) / speed_differential };

  float a = 1 - sqr(target_speed / projectile_speed);
  float b = -2 * dot_product(base, target_direction);
  float c = sqr(vector_magnitude(base));
  rad = sqrt(sqr(b) - 4 * a * c);
  float time[] = { (-b + rad) / (2 * a), (-b - rad) / (2 * a) };

  free(base);
  free(target_direction);
  return max(time[0], time[1]);
}

float* flight_direction(float* initial_projectile, float* initial_target,
                        float projectile_speed, float* target_velocity) {
  float time = flight_time(initial_projectile, initial_target, projectile_speed, target_velocity);
  float* flight_vector = malloc(sizeof(float) * 3);
  flight_vector[0] = initial_target[0] + target_velocity[0] * time - initial_projectile[0];
  flight_vector[1] = initial_target[1] + target_velocity[1] * time - initial_projectile[1];
  flight_vector[2] = initial_target[2] + target_velocity[2] * time - initial_projectile[2];
  vector_normalize(flight_vector);
  return flight_vector;
}

int main(void) {
}
