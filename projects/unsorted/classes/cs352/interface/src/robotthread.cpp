#include "robotthread.h"
#include "global.h"

// only here for rand
#include <stdlib.h>
#include <string.h>

const float AUTONOMOUS_TURNING_SPEED = 1.0 * ROTATE_SPEED;
const float AUTONOMOUS_DRIVING_SPEED = 1.0 * DRIVE_SPEED;

RobotThread::RobotThread(){
    // only here to fake task completion
    srand(0);
    
    m_pSharedData = NULL;
}

long RobotThread::Init(int id, QString name, char * address, long port, int configFlags, SharedData * pSharedData){
    ROBOT_CONFIGURATION robotConfiguration;

    m_Name = QString(name);
    m_Robot.Init(id, name, address, port, configFlags);
    m_Robot.GetPlayerClient()->SetDataMode(PLAYER_DATAMODE_PULL);
    m_Robot.GetPlayerClient()->SetReplaceRule(true);
    m_pSharedData = pSharedData;

	strncpy(robotConfiguration.name, name.toLatin1().data(), name.length() + 1);
    m_pSharedData->Lock();
    m_lId = m_pSharedData->AddRobot(&robotConfiguration);
    
    // Because battery level is faked, it needs to be preset to the max value
    m_pSharedData->GetRobotData(m_lId)->status.batteryLevel = STARTING_BATTERY_LEVEL;
    m_pSharedData->GetRobotData(m_lId)->status.performingWaypoint = false;
    m_pSharedData->Unlock();

    return 0;
}

void RobotThread::run(){
    bool done = false;
    double xSpeed, yawSpeed;
    ROBOT_DATA * pRobotData;
    float waypointDistance = 0;
    bool bWaypoint;
    float startX, startY;
    float xDistance, yDistance;
    float xGoal = 0.0;
    float yGoal = 0.0;
    float previousSpeedX = 0.0;
    float previousSpeedYaw = 0.0;
    long stallCounter = 0;
    float previousX = 0.0;
    float previousY = 0.0;
    float previousOrientation = 0.0;
    float difference = 0;
    long cycleCount = 0;
    long taskProgress;
    float desiredX, desiredY;
    bool  bAtTask;
    // Incase run is called before Init, wait until m_pSharedData is set
    while(m_pSharedData == NULL);

    // Cache starting values
    m_Robot.Read();
    startX = m_Robot.GetPosition2d()->GetXPos();
    startY = m_Robot.GetPosition2d()->GetYPos();

     while(!done){
	// Update robot and shared data
	m_Robot.Read();
	m_pSharedData->Lock();
	
	pRobotData = m_pSharedData->GetRobotData(m_lId);

	// Check if thread should end
	if(!pRobotData->status.running){
	    printf("robot is done\n");
	    done = true;
	    m_pSharedData->Unlock();
	    continue;
	}
	
	// Fake reading battery level
	pRobotData->status.batteryLevel -= 1;
	if(pRobotData->status.batteryLevel < 0) pRobotData->status.batteryLevel = 0;
	if(pRobotData->status.batteryLevel == 0){
	    m_pSharedData->Unlock();
	    continue;
	}
	
	// Copy new sensor and status data to the shared data space
	// Update position (currently using temp proxy)
	pRobotData->status.xPosition = m_Robot.GetPosition2d()->GetXPos();
	pRobotData->status.yPosition = -1*m_Robot.GetPosition2d()->GetYPos();
	pRobotData->status.orientation = m_Robot.GetPosition2d()->GetYaw();
	if(pRobotData->status.orientation > 3.14159) pRobotData->status.orientation = pRobotData->status.orientation - (2*3.14159);
	pRobotData->status.orientation *= -1;
	
	cycleCount++;
	if(cycleCount % 30 == 0){
	    
	    // Check for a stall condition
	    if(previousSpeedX < 0.01 &&  previousSpeedYaw < 0.02){
		stallCounter = 0;
		pRobotData->status.stalled = false;
	    }
	    else{
		if(fabs(pRobotData->status.xPosition - previousX) < 0.05 && fabs(pRobotData->status.yPosition - previousY) < 0.05){
		    difference = fabs(pRobotData->status.orientation - previousOrientation);
		    if(difference < 0.1 || difference > 6.2){
			stallCounter++;
			if(stallCounter > 5){
			    pRobotData->status.stalled = true;
			    stallCounter = 5;
			}
		    }
		}
	    }
	    
	    previousX = pRobotData->status.xPosition;
	    previousY = pRobotData->status.yPosition;
	    previousOrientation = pRobotData->status.orientation;
	}
	
	bWaypoint = false;
	if(pRobotData->status.waypoints.size() > 0){
	    xGoal = pRobotData->status.waypoints[0].x;
	    yGoal = pRobotData->status.waypoints[0].y;
	    xDistance = xGoal - pRobotData->status.xPosition;
	    yDistance = yGoal - pRobotData->status.yPosition;
	    waypointDistance = sqrt(xDistance*xDistance + yDistance*yDistance);
	    if(!pRobotData->status.performingWaypoint){
		//pRobotData->status.startingWaypointDistance = waypointDistance;
		pRobotData->status.currentWaypointDistance = waypointDistance;
	    }
	    if(waypointDistance > REQUIRED_DISTANCE_TO_WAYPOINT){
		bWaypoint = true;
		pRobotData->status.performingWaypoint = true;
	    }
	    else{
		pRobotData->status.performingWaypoint = false;
		if(pRobotData->status.waypoints[0].task != TASK_WAYPOINT){
		    pRobotData->status.atTask = true;
		    pRobotData->status.taskProgress = 0;
		}
		pRobotData->status.waypoints.erase(pRobotData->status.waypoints.begin());
	    }
	}
	else{
	    pRobotData->status.performingWaypoint = false;
	}
	
	if(m_pSharedData->GetCurrentRobot() == m_lId){
		m_Robot.GetCamera()->GetImage(m_pSharedData->GetVideoData());
		desiredX = pRobotData->status.xSpeed;
		desiredY = pRobotData->status.yawSpeed;	
	}
	else{
		desiredX = 0.0;
		desiredY = 0.0;
	}
	
	// Update tasks
	if(pRobotData->status.atTask){
	    xSpeed = 0.0;
	    yawSpeed = 0.0;
	    
	    taskProgress = rand() % 3;
	    pRobotData->status.taskProgress += taskProgress;
	    if(pRobotData->status.taskProgress >= MAX_ROBOT_TASK_PROGRESS){
		pRobotData->status.atTask = false;
		pRobotData->status.taskProgress = 0;
	    }
	    else{
		bWaypoint = false;
	    }
	}

	// Handle progress
	if(pRobotData->status.atTask){
	    pRobotData->status.overallProgress += taskProgress;
	}
	else if(pRobotData->status.waypoints.size() > 0){
	    difference = pRobotData->status.currentWaypointDistance - waypointDistance;
	    pRobotData->status.currentWaypointDistance = waypointDistance;
	    pRobotData->status.overallProgress += (long)(WAYPOINT_COST * difference);
	}
	else{
	    pRobotData->status.overallProgress = 1;
	    pRobotData->status.maxProgress = 1;
	}
	    	
	bAtTask = pRobotData->status.atTask;
	m_pSharedData->Unlock();

	// Execute any commands
	if(bWaypoint){
	    
	    float yDelta = yGoal - pRobotData->status.yPosition;
	    float xDelta = xGoal - pRobotData->status.xPosition;
	    float goalAngle = atan2(yDelta, xDelta);
	    float robotAngle = pRobotData->status.orientation;
	    float angleDelta = fabs(goalAngle - robotAngle);
	    bool turnRight;
	    
	    if((robotAngle >= 0 && goalAngle >= 0)||(robotAngle <= 0 && goalAngle <= 0)){
		if(goalAngle < robotAngle) turnRight = true;
		else turnRight = false;
	    }
	    else{
		if(robotAngle > 0){
		    if(angleDelta < 3.14159) turnRight = true;
		    else turnRight = false;
		}
		else{
		    if(angleDelta > 3.14159) turnRight = true;
		    else turnRight = false;
		}
	    }
		
	    if(angleDelta > ANGLE_DIFFERENCE_THRESHOLD){
		xSpeed = 0;
		if(turnRight) yawSpeed = -1*AUTONOMOUS_TURNING_SPEED;
		else yawSpeed = AUTONOMOUS_TURNING_SPEED;
	    }
	    else{
		xSpeed = AUTONOMOUS_DRIVING_SPEED;
		yawSpeed = 0;
	    }
	}
	
	if(!bAtTask){
		if(fabs(desiredX) > 0.10 || fabs(desiredY)  > 0.1){
			xSpeed = desiredX;
			yawSpeed = desiredY;
		}
	}
	
	previousSpeedX = xSpeed;
	previousSpeedYaw = yawSpeed;
	m_Robot.GetPosition2d()->SetSpeed(xSpeed, yawSpeed);
     }
    printf("robot thread shutting down.\n");
    exit();
}
