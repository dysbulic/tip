#include "robot.h"
#include <player-2.0/libplayerc++/playererror.h>

Robot::Robot(){
    Clear();
}

Robot::~Robot(){
    Destroy();
}

void Robot::Read(){
    if(m_pPlayerClient) m_pPlayerClient->Read();
}

long Robot::Init(int id, QString name, const char * hostName, unsigned long portNumber,  int configFlags){
    Destroy();
    try{
	m_pPlayerClient = new PlayerClient(hostName, portNumber);

	if(configFlags & ROBOT_SIMULATION){
		m_pSimulation = new SimulationProxy(m_pPlayerClient, id);
	}
	if(configFlags & ROBOT_POSITION2D){
		m_pPosition2d = new Position2dProxy(m_pPlayerClient, id);
	}
	if(configFlags & ROBOT_LASER){
		m_pLaser = new LaserProxy(m_pPlayerClient, id);
	}
	if(configFlags & ROBOT_SONAR){
		m_pSonar = new SonarProxy(m_pPlayerClient, id);
	}
	if(configFlags & ROBOT_VFH){
		m_pVfh = new Position2dProxy(m_pPlayerClient, id + 1);
	}
	if(configFlags & ROBOT_FAKE){
		m_pFake = new Position2dProxy(m_pPlayerClient, id + 2);
	}
	if(configFlags & ROBOT_FIDUCIAL){
		m_pFiducial = new FiducialProxy(m_pPlayerClient, id);
	}
	if(configFlags & ROBOT_CAMERA){
		m_pCamera = new CameraProxy(m_pPlayerClient, id);
	}
	if(configFlags & ROBOT_PTZ){
		m_pPtz = new PtzProxy(m_pPlayerClient, id);
	}
	if(configFlags & ROBOT_BOLBFINDER){
		m_pBlobfinder = new BlobfinderProxy(m_pPlayerClient, id);
	}
	if(configFlags & ROBOT_FRONT_BUMPER){
		m_pFrontBumper = new BumperProxy(m_pPlayerClient, id);
	}
	if(configFlags & ROBOT_BACK_BUMPER){
		m_pBackBumper = new BumperProxy(m_pPlayerClient, id + 1);
	}
    }
    catch(PlayerError e){
	Destroy();
	return((long)(e.GetErrorCode()));
    }

    //m_pContext = context;
    
#ifdef USE_BEHAVIORS
    RobotBehavior::LoadBehaviors(m_pBehaviors, this);
#endif
    
    return(ROBOT_SUCCESS);
}

void Robot::Clear(){

#ifdef USE_BEHAVIORS
    for(long iter = 0; iter < NUM_BEHAVIORS; iter ++){
	m_pBehaviors[iter] = NULL; 
    }
#endif

    m_pPlayerClient = NULL;
    m_pPosition2d = NULL;
    m_pLaser = NULL;
    m_pSonar = NULL;
    m_pVfh = NULL;
    m_pFake = NULL;
    m_pFiducial = NULL;
    m_pCamera = NULL;
    m_pPtz = NULL;
    m_pBlobfinder = NULL;
    m_pFrontBumper = NULL;
    m_pBackBumper = NULL;
}

void Robot::Destroy(){

#ifdef USE_BEHAVIORS
    for(long iter = 0; iter < NUM_BEHAVIORS; iter ++){
	if(m_pBehaviors[iter]) free(m_pBehaviors[iter]); 
    }
#endif

    if(m_pBackBumper)   delete(m_pBackBumper);
    if(m_pFrontBumper)  delete(m_pFrontBumper);
    if(m_pBlobfinder)   delete(m_pBlobfinder);
    if(m_pPtz)          delete(m_pPtz);
    if(m_pCamera)       delete(m_pCamera);
    if(m_pFiducial)     delete(m_pFiducial);
    if(m_pVfh)          delete(m_pVfh);
    if(m_pFake)         delete(m_pFake);
    if(m_pSonar)        delete(m_pSonar);
    if(m_pLaser)        delete(m_pLaser);
    if(m_pPosition2d)   delete(m_pPosition2d);
    if(m_pPlayerClient) delete(m_pPlayerClient);

    Clear();
}

#ifdef USE_BEHAVIORS
RobotBehavior * Robot::GetBehavior(long behavior){
    
    if(behavior >= NUM_BEHAVIORS || behavior < 0){
	return(NULL);
    }
    else{

	return(m_pBehaviors[behavior]);
    }
}

long Robot::Run(){
    
    for(;;){
	  
	Read();

	// Test for Quit behavior
	if(m_pBehaviors[QUIT_BEHAVIOR]->GetStatus() == BEHAVIOR_RUNNING){
	    m_pBehaviors[QUIT_BEHAVIOR]->Execute();
	    break;
	}

	// User defined behaviors
	for(long iter = QUIT_BEHAVIOR+1; iter < NUM_BEHAVIORS; iter++){
	   if(m_pBehaviors[iter]->GetStatus() == BEHAVIOR_RUNNING)
	       m_pBehaviors[iter]->Execute();
	}
	
    }

    return(ROBOT_SUCCESS);
}

void Robot::StopAllBehaviors(){

    for(long iter = 0; iter < NUM_BEHAVIORS; iter++){
	m_pBehaviors[iter]->SetStatus(BEHAVIOR_STOPPED);
    }
}
#endif
