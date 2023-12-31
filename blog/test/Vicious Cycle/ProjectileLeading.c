/**************************************
ProjectileLeading.cpp  Brett Holcomb  2006-02-07
Question 5 on Vicious Cycle-Engineering Test
 
Projectile Leading Problem: Given an initial shot position (Spos), a constant shot speed (Sspeed), an 
initial target position (Tpos), and a constant target velocity (speed + dir) (Tvel) derive the equation 
for calculating the shot direction (Sdir) such that at some time (t) the shot would hit the target. 
Be sure to derive an equation for t as well!

Write a function to compute these values (Sdir and t).
***************************************
GIVEN:
sPos:     initial shot position     (vector)
sSpeed:   constant shot speed       (scalar)
tPos:     initial target position   (vector)
tVel:    constant target velocity  (vector) 

FIND:
sDir:     direction to shoot in to hit target                 (vector)
t:        time it will take for projectile to hit target      (scalar)

CALCULATION:
projDist:  distance projectile will have traveled from sPos in time (t)  (scalar)
projDist = sSpeed * t
targetLoc: targets current location at a time (t)                        (vector)
targetLoc = tPos + tVel*t
targetDist: distance target is from sPos at a time (t)                   (scalar)
targetDist = sqrt( pow(targetLoc.x - sPos.x, 2) + 
                   pow(targetLoc.y - sPos.y, 2) + 
                   pow(targetLoc.z - sPos.z, 2) )
                   
When times (t) where projDist = targetDist will be possible times the collision will occur. Since we will 
solve for 2 values of t, we will choose the smaller, non-negative one.

Working through the above and plugging into the quadratic formula to solve for values of t.
t = (-B +/- squrt(B*B -4AC)) / 2*A
where
A = (tVel.x)^2 + (tVel.y)^2 + (tVel.z)^2 - sSpeed^2
B = 2tVel.x*(tPos.x - sPos.x) + 2tVel.y*(tPos.y - sPos.y) + 2tVel.z*(tPos.z - sPos.z)
C = (tPos.x - sPos.x)^2 + (tPos.y - sPos.y)^2 + (tPos.z - sPos.z)^2 
**************************************/
#include <stdio.h>
#include <stdlib.h>
#include <math.h>

/** BEGIN Misc. Math Functions **/

// normalize a given vector
void normalize(float * normal) {
  // calculate magnitude and use to normalize vector
  float magnitude = sqrt( pow( normal[0], 2) + pow( normal[1], 2) + pow( normal[2], 2) );
  normal[0] = normal[0] / magnitude;  
  normal[1] = normal[1] / magnitude;
  normal[2] = normal[2] / magnitude;  
}
/** END Misc. Math Functions **/

/**
 * If t < 0 is returned, the projectile is too fast
 */
float projectileTime(float *sPos, float *tPos, float sSpeed, float* tVel) {
  float A = pow(tVel[0], 2) + pow(tVel[1], 2) + pow(tVel[2], 2) - pow(sSpeed, 2);
  float B = 2*tVel[0]*(tPos[0] - sPos[0]) + 2*tVel[1]*(tPos[1] - sPos[1]) + 2*tVel[2]*(tPos[2] - sPos[2]);
  float C = pow(tPos[0] - sPos[0], 2) + pow(tPos[1] - sPos[1], 2) + pow(tPos[2] - sPos[2], 2);

  float t1 = (-B + sqrt(B*B -4*A*C) ) / 2*A;
  float t2 = (-B - sqrt(B*B -4*A*C) ) / 2*A;
  
  float t = t1;
  if (t1 < 0 || ( t1 > t2 && t2 > 0) )
    t = t2;
  return t;
}

float* projectileDirection(float *sPos, float *tPos, float sSpeed, float* tVel) {
  float t = projectileTime(sPos, tPos, sSpeed, tVel);
  // Now we have (t), finding sDir is simply a matter of determining where the target is at time t, 
  // calculating the vector from sPos to the target, then normalizing that vector.
  float tPosHit [3];
  float* sDir = malloc(sizeof(float) * 3);
  int i;
  for (i=0; i < 3; i++) {
    tPosHit[i] = tPos[i] + t * tVel[i];
    sDir[i] = tPosHit[i] - sPos[i];
  }
  
  normalize(sDir);
  return sDir;
}

int main () {
  float sPos [3] = {5,0,0};       // initial shot position
  float sSpeed = 1;              // constant shot speed
  float tPos [3] = {0,0,0};       // initial target position
  float tVel [3] = {0,0,0};    // constant target velocity

  // trying an example with a moving target @ t=3:
  // tPos = [3+3*1,0,0] and sPos = [0+2*3,0,0] (if sDir = [1,0,0])
  sPos[0] = 0;
  sSpeed = 2;
  tPos[0] = 3;
  tVel[0] = 1;
  
  float t = projectileTime(sPos, tPos, sSpeed, tVel);

  printf("t is : %0.2f\n", t);
 
  float* sDir = projectileDirection(sPos, tPos, sSpeed, tVel);
  printf("sDir.x: %0.2f  sDir.y: %0.2f  sDir.z: %0.2f\n", sDir[0], sDir[1], sDir[2]);
  free(sDir);
  
  return 0;
}
