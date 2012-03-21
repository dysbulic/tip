#ifndef __ROBOT__
#define __ROBOT__

class Robot;

//#include <player-2.0/libplayerc++/playerc++.h>
#include <libplayerc++/playerc++.h>
#include <QString>

//#define USE_BEHAVIORS
#ifdef USE_BEHAVIORS
#include "behaviors.h"
#endif

const int ROBOT_SIMULATION = 1 >> 0;
const int ROBOT_POSITION2D = 1 << 1; 
const int ROBOT_LASER = 1 << 2;
const int ROBOT_SONAR = 1 << 3;
const int ROBOT_VFH = 1 << 4;
const int ROBOT_FAKE = 1 << 5;
const int ROBOT_FIDUCIAL = 1 << 6;
const int ROBOT_CAMERA = 1 << 7;
const int ROBOT_PTZ = 1 << 8;
const int ROBOT_BOLBFINDER = 1 << 9;
const int ROBOT_FRONT_BUMPER = 1 << 10;
const int ROBOT_BACK_BUMPER = 1 << 11;

const long ROBOT_SUCCESS = 0;
const long ROBOT_FAILURE = -1;

using namespace PlayerCc;

class Robot{
public:
    Robot();
    ~Robot();
    long Init(int id, QString name, const char * hostName, unsigned long portNumber,  int configFlags);
    void Clear();
    void Destroy();

    void Read();

#ifdef USE_BEHAVIORS
    long Run();
    RobotBehavior * GetBehavior(long behavior);
    void StopAllBehaviors();
#endif

    // Getters and Setters
    PlayerClient    * GetPlayerClient() {return(m_pPlayerClient);}
    SimulationProxy * GetSimulation() {return(m_pSimulation);}
    Position2dProxy * GetPosition2d() {return(m_pPosition2d);}
    Position2dProxy * GetVfh() {return(m_pVfh);}
    Position2dProxy * GetFake() {return(m_pFake);}
    LaserProxy      * GetLaser() {return(m_pLaser);}
    SonarProxy      * GetSonar() {return(m_pSonar);}
    FiducialProxy   * GetFiducial() {return(m_pFiducial);}
    CameraProxy     * GetCamera() {return(m_pCamera);}
    PtzProxy        * GetPtz() {return(m_pPtz);}
    BlobfinderProxy * GetBlobfinder() {return(m_pBlobfinder);}
    BumperProxy     * GetFrontBumper() {return(m_pFrontBumper);}
    BumperProxy     * GetBackBumper() {return(m_pBackBumper);}
    void            * GetContext() {return(m_pContext);}
    
 private:

    PlayerClient      * m_pPlayerClient;
    SimulationProxy * m_pSimulation;
    Position2dProxy * m_pPosition2d;
    Position2dProxy * m_pVfh;
    Position2dProxy * m_pFake;
    LaserProxy      * m_pLaser;
    SonarProxy      * m_pSonar;
    FiducialProxy   * m_pFiducial;
    CameraProxy     * m_pCamera;
    PtzProxy        * m_pPtz;
    BlobfinderProxy * m_pBlobfinder;
    BumperProxy     * m_pFrontBumper;
    BumperProxy     * m_pBackBumper;

    void            * m_pContext;

#ifdef USE_BEHAVIORS    
    RobotBehavior   * m_pBehaviors[NUM_BEHAVIORS];
#endif
};

#endif
