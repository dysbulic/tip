/**************************************
Spheres.cpp  Brett Holcomb  2006-02-06
Question 2 on Vicious Cycle-Engineering Test
 
Given a set of spheres, each with: arbitrary radius, differing speeds, and differing direction of travel, 
design a system that updates the positions and detects any collisions among the spheres. Assume the 
position is updated by a uniform time step and all speeds are constant. Compiled code not required.

      Each sphere has the following attributes:
      Position – a 3D vector

Radius – float value
Direction of movement – a 3D unit vector
Speed – a float coefficient applied to the direction of movement vector

Also assuming time step is relativesly small enough to speeds/radiuses that error due to overlap of 
spheres at the time of collision calculation isn't significant (otherwise could do collision calculation 
by examining the *between* step where the spheres first touch)
**************************************/
#include <iostream>
#include <math.h>
using namespace std;

/** BEGIN Misc. Math Functions **/
// set normalized vector pointing from pointA to pointB
void getNormal(float * normal, float pointA[3], float pointB[3]) {
  float tempNorm [] = {pointB[0] - pointA[0], pointB[1] - pointA[1], pointB[2] - pointA[2] };
  // calculate magnitude and use to normalize vector
  float tempNormMag = sqrt( pow( tempNorm[0], 2) + pow( tempNorm[1], 2) + pow( tempNorm[2], 2) );
  normal[0] = tempNorm[0] / tempNormMag;  
  normal[1] = tempNorm[1] / tempNormMag;
  normal[2] = tempNorm[2] / tempNormMag;  
}
// return dot product of 2 vectors = Ax*Bx + Ay*By + Az*Bz
float dotProduct (float a[3], float b[3]) {
  return ( a[0]*b[0] + a[1]*b[1] + a[2]*b[2] );
}
/** END Misc. Math Functions **/

/** a sphere in space has the following: radius (float), direction of movement (3D vector), 
 ** speed (float), and a current location (3D vector) **/
class Sphere {
  public:
  float radius, speed, location[3], direction[3];
  
  // step forward 1 time unit
  void timesStep() {
    for (int i=0; i < 3; i++) 
      location[i] = location[i] + direction[i] * speed;
  }
  
  // print sphere's location and direction to console
  void printSphere() {
    cout << "Location: " << location[0] << ", " << location[1] << ", " << location[2] << 
            "   Direction: " << direction[0] << ", " << direction[1] << ", " << direction[2]; 
  }
};
    
  
/** SphereSpace keeps track of an arbitrary number of spheres, updates their positions, calculates 
 ** collisions and updates their directions of travel accordingly
 ** ASSUMPTIONS: speed is unchanged by collisions, uniform time step **/
class SphereSpace {
  public:
  int numSpheres;
  Sphere * pSpheres;
  
  // step forward 1 time unit
  void timeStep() {
    for (int i=0; i < numSpheres; i++) {
      (*(pSpheres + i)).timesStep();  
    }
    
    checkForCollisions();
  }
  
  // check for collisions and update trajectories as necessary
  void checkForCollisions() {
    Sphere * pSphereA, * pSphereB;
    
    for (int i=0; i < numSpheres; i++) {
      pSphereA = pSpheres + i;
      
      for (int j=i+1; j < numSpheres; j++) {
        pSphereB = pSpheres + j;
        float distAtoB = sqrt( pow((*pSphereA).location[0] - (*pSphereB).location[0], 2) + 
                               pow((*pSphereA).location[1] - (*pSphereB).location[1], 2) + 
                               pow((*pSphereA).location[2] - (*pSphereB).location[2], 2) );
//        cout << "distAtoB: " << distAtoB << "\n";
//        cout << "sum of radiuses: " << (*pSphereA).radius + (*pSphereB).radius << "\n";               
                                   
        if (distAtoB <= ( (*pSphereA).radius + (*pSphereB).radius ) ) {  // Collision!
          // To get the new trajectory, use the equation T = 2*(-I dot N)*N + I, where N is the normal 
          // vector (the line normal to plane exactly separating the 2 spheres as they collide) and I is 
          // the initial direction the sphere is travelling in. Both I and N must be normal vectors.
          
          // First calculate the new trajectory for pSphereA
          float normal [3];
          getNormal(normal, (*pSphereB).location, (*pSphereA).location);
          float tempVec [] = {-(*pSphereA).direction[0], -(*pSphereA).direction[1], -(*pSphereA).direction[2]};
          float temp = 2 * dotProduct( tempVec, normal );
          for (int i=0; i < 3; i++) {
            tempVec[i] = temp * normal[i];
            (*pSphereA).direction[i] = tempVec[i] + (*pSphereA).direction[i];
          }
          
          // Then calculate the new trajectory for pSphereB
          getNormal(normal, (*pSphereA).location, (*pSphereB).location);
          tempVec [0] = -(*pSphereB).direction[0]; 
          tempVec [1] = -(*pSphereB).direction[1]; 
          tempVec [2] = -(*pSphereB).direction[2];
          temp = 2 * dotProduct( tempVec, normal );
          for (int i=0; i < 3; i++) {
            tempVec[i] = temp * normal[i];
            (*pSphereB).direction[i] = tempVec[i] + (*pSphereB).direction[i];
          }
          
          // Do another time step to make sure the spheres don't continue to intersect
          for (int i=0; i < numSpheres; i++) {
            (*(pSpheres + i)).timesStep();  
          }
        } // end collision conditional
      } // end inner loop
    } // end outer loop  
  } // end function checkForCollisions()
  
  // print location and direction of all spheres
  void printSpheres() {
    for (int i=0; i < numSpheres; i++) {
      cout << "Sphere" << i << ": ";
      (*(pSpheres + i)).printSphere();
      cout << "\n";
    }
  }
};  


// demo code  
int main () {
  int numSpheres = 2;
  Sphere mySpheres[numSpheres];
  SphereSpace mySphereSpace;
  mySphereSpace.numSpheres = numSpheres;
  mySphereSpace.pSpheres = mySpheres;
  
  mySpheres[0].location[0] = -1;
  mySpheres[0].location[1] = 1; 
  mySpheres[0].location[2] = 0;
  mySpheres[1].location[0] = 2;
  mySpheres[1].location[1] = 2;
  mySpheres[1].location[2] = 0;
  
  mySpheres[0].direction[0] = 1;
  mySpheres[0].direction[1] = 0;
  mySpheres[0].direction[2] = 0;
  mySpheres[1].direction[0] = -1;
  mySpheres[1].direction[1] = 0;
  mySpheres[1].direction[2] = 0;
  
  for (int i=0; i < numSpheres; i++) {
    mySpheres[i].speed = .25;
    mySpheres[i].radius = 1;
  }
  
  // Test for 6 time steps
  mySphereSpace.printSpheres();
  mySphereSpace.timeStep();
  for (int i=0; i < 6; i++) {
    mySphereSpace.printSpheres();
    mySphereSpace.timeStep();
  }
  
  
  return 0;
}

