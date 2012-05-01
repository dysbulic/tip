#include "shareddata.h"

SharedData::SharedData(){

    m_lRobotCount = 0;

    for(long iter = 0; iter < MAX_NUM_ROBOTS; iter++)
	m_bRobotExists[iter] = false;
}

SharedData::~SharedData() {}

long SharedData::AddRobot(ROBOT_CONFIGURATION * pConfiguration){
    long nextIndex = 0;

    if(m_lRobotCount >= MAX_NUM_ROBOTS) return(-1);

    for(; nextIndex < MAX_NUM_ROBOTS; nextIndex++){
	if(!m_bRobotExists[nextIndex]) break;
    }

    if(nextIndex >= MAX_NUM_ROBOTS) return(-1);

    m_lRobotCount++;
    m_bRobotExists[nextIndex] = true;
    memcpy(&m_RobotData[nextIndex].configuration, pConfiguration, sizeof(ROBOT_CONFIGURATION));

    // Init robot status here
    m_RobotData[nextIndex].status.running = true;
    m_RobotData[nextIndex].status.nextWaypointId = 0;
    m_RobotData[nextIndex].status.atTask = false;
    m_RobotData[nextIndex].status.batteryLevel = 0;
    m_RobotData[nextIndex].status.stalled = false;

    return(nextIndex);
}

void SharedData::SetCurrentRobot(long robot){

    if(robot < 0 || robot >= MAX_NUM_ROBOTS || !m_bRobotExists[robot]) return;
    m_lCurrentRobot = robot;
}

ROBOT_DATA * SharedData::GetRobotData(long robot){

    if(robot < 0 || robot >= MAX_NUM_ROBOTS) return(NULL);
    return(&m_RobotData[robot]);
}

bool SharedData::DoesRobotExist(long robot){

    if(robot < 0 || robot >= MAX_NUM_ROBOTS) return(false);
    return(m_bRobotExists[robot]);
}
