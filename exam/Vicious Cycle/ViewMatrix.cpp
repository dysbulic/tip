/**************************************
ViewMatrix.cpp  Brett Holcomb  2006-02-07
Question 3 on Vicious Cycle-Engineering Test
 
Given a camera at point p1 compute a view matrix so that the camera looks at an arbitrary point p2. 
Assume vector ‘up’ as the up vector (hint: use {0,1,0} to estimate the true up vector). Compiled code 
not required.   
**************************************
The View Matrix transforms from World Space into View Space and it describes the camera's location 
and orientation.

THE VIEW MATRIX:
  Given:
    up vector: determines which direction is up
    right vector: points to the camera's right
    look vector: the direction the camera is pointing 
    position vector: 3d coordinate of the camera's location in the world
    
  4x4 View matrix:
    { { right.x,         up.x,         look.x,         0.0},
      { right.y,         up.y,         look.y,         0.0},
      { right.y,         up.z,         look.z,         0.0},
      { -position.right, -position.up, -position.look, 1.0} }
      
  Where vectorA.vectorB represents the dot product of the vectors
**************************************/
#include <iostream>
#include <math.h>
using namespace std;

/** BEGIN Misc. Math Functions **/

// normalize a given vector
void normalize(float * normal) {
  // calculate magnitude and use to normalize vector
  float magnitude = sqrt( pow( normal[0], 2) + pow( normal[1], 2) + pow( normal[2], 2) );
  normal[0] = normal[0] / magnitude;  
  normal[1] = normal[1] / magnitude;
  normal[2] = normal[2] / magnitude;  
}

// return dot product of 2 vectors = Ax*Bx + Ay*By + Az*Bz
float dotProduct (float a[3], float b[3]) {
  return ( a[0]*b[0] + a[1]*b[1] + a[2]*b[2] );
}

// set the cross product of 2 vectors A and B
// A x B = {Ay*Bz - Az*By, Az*Bx - Ax*Bz, Ax*By - Ay*Bx}
void crossProduct(float * crossProd, float a[3], float b[3]) {
  crossProd[0] = a[1]*b[2] - a[2]*b[1];
  crossProd[1] = a[2]*b[0] - a[0]*b[2];
  crossProd[2] = a[0]*b[1] - a[1]*b[0];  
}
/** END Misc. Math Functions **/

class ViewMatrix {
  public:
  float matrix [4][4];
  
  // calculate the view matrix assuming up direction of (0,0,1) if none is specified
  void calcMatrix(float eyePoint[3], float lookPoint[3]) {
    float upDir[] = {0, 0, 1};
    calcMatrix(eyePoint, lookPoint, upDir);
  }
  
  // calculate the view matrix given points to look from and at, and an up direction
  void calcMatrix(float eyePoint[3], float lookPoint[3], float upVec[3]) {
    // calculate look vector
    float lookVec[3];
    for (int i=0; i < 3; i++)
      lookVec[i] = lookPoint[i] - eyePoint[i];
    normalize(lookVec);
    
    // calculate right vector
    normalize(upVec);     // make sure upVec is normalized
    float rightVec[3];
    crossProduct( rightVec, lookVec, upVec );
        
    // populate view matrix elements
    for (int i=0; i < 3; i++) {     // first 3 rows
      matrix[i][0] = rightVec[i];
      matrix[i][1] = upVec[i];
      matrix[i][2] = lookVec[i];
      matrix[i][3] = 0.0f;
    }
    
    // last row
    float negEye[3] = {-eyePoint[0], -eyePoint[1], -eyePoint[2]};
    matrix[3][0] = dotProduct(negEye, rightVec);
    matrix[3][1] = dotProduct(negEye, upVec);
    matrix[3][2] = dotProduct(negEye, lookVec);
    matrix[3][3] = 1.0f;         
  }  
};

// demo code
int main () {
  float myEyePt[3] = {1, 1, 1};
  float myLookPt[3] = {3, 3, 3};
  
  ViewMatrix myVuMat;
  myVuMat.calcMatrix(myEyePt, myLookPt); 
  
  return 0;
}
      
