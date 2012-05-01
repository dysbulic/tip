#ifndef __SHARED_DATA__
#define __SHARED_DATA__

#include <QMutex>

#include <vector>
#include "global.h"

typedef struct tagWAYPOINT{
    long id;
    long task;
    float x, y;
    float distance;
} WAYPOINT;

typedef struct tagROBOT_CONFIGURATION{
    char name[MAX_ROBOT_NAME_LENGTH];
    long id;
    
} ROBOT_CONFIGURATION;

typedef struct tagROBOT_STATUS{
    long nextWaypointId;
    bool running;
    long batteryLevel;
    float xPosition, yPosition, orientation;
    double xSpeed, yawSpeed;
    bool stalled;
    float startingWaypointDistance;
    float currentWaypointDistance;
    bool performingWaypoint;

    bool atTask;
    long overallProgress;
    long maxProgress;
    long taskProgress;
    std::vector<WAYPOINT> waypoints;

} ROBOT_STATUS;

typedef struct tagROBOT_DATA{
    
    ROBOT_CONFIGURATION configuration;
    ROBOT_STATUS status;
    
} ROBOT_DATA;

class SharedData{
    
 public:
    SharedData();
    ~SharedData();

    void Lock() {m_Mutex.lock();}
    void Unlock() {m_Mutex.unlock();}

    long GetRobotCount() {return(m_lRobotCount);}
    bool DoesRobotExist(long robot);
    long AddRobot(ROBOT_CONFIGURATION * pConfiguration);
    ROBOT_DATA * GetAllRobotData() {return(m_RobotData);}
    ROBOT_DATA * GetRobotData(long robot);
    unsigned char * GetVideoData() {return(m_VideoData);}
    long GetCurrentRobot() {return(m_lCurrentRobot);}
    void SetCurrentRobot(long robot);

    void SetMode(long mode) {m_lMode = mode;}
    long GetMode() {return(m_lMode);}

 private:
    long m_lRobotCount;
    long m_lCurrentRobot;
    QMutex m_Mutex;
    bool m_bRobotExists[MAX_NUM_ROBOTS];
    ROBOT_DATA m_RobotData[MAX_NUM_ROBOTS];
    unsigned char m_VideoData[3*VIDEO_WIDTH*VIDEO_HEIGHT];
    long m_lMode;
};

#endif
