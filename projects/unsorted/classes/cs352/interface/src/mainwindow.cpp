#include "mainwindow.h"
#include "addtaskdialog.h"
#include "deletetaskdialog.h"

#include <float.h>
#include <QVBoxLayout>
#include <QHBoxLayout>
#include <QInputDialog>
#include <QStringList>
#include <QMessageBox>

// Temp variables for placing objects
float tempX, tempY;

MainWindow::MainWindow(long mode){
    printf("GrabKeyboard\n");
    grabKeyboard();

    m_SharedData.SetMode(mode);

    // File Menu
    m_pFileMenu = new QMenu(tr("File"));
    menuBar()->addMenu(m_pFileMenu);
	
    // File Menu - Exit
    m_pFileMenuExit = m_pFileMenu->addAction(tr("Exit"));
    m_pFileMenuExit->setStatusTip(tr("Exit the application"));
    connect(m_pFileMenuExit, SIGNAL(triggered()), this, SLOT(close()));

    m_pImage = new QImage("images/nosignal.jpg");

    m_pViewPort = new ViewPort(this, &m_SharedData, m_pImage, m_pMutex);
    m_pViewPort->setFixedSize(VIEWPORT_WIDTH, VIEWPORT_HEIGHT);

    printf("Create control panel\n");

    // Create control panel
    m_pOrchestrationPanel = new OrchestrationPanel(this, &m_SharedData);
    m_pManipulationPanel = new ManipulationPanel(this, & m_SharedData);
    m_pControlPanel = new QStackedWidget;
    m_pControlPanel->addWidget(m_pOrchestrationPanel);
    m_pControlPanel->addWidget(m_pManipulationPanel);
    m_CurrentInterface = CURRENT_INTERFACE_MANIPULATION;
    m_pViewPort->SetInterface(m_CurrentInterface);
    m_pControlPanel->setCurrentWidget(m_pManipulationPanel);
    m_pControlPanel->setFixedWidth(VIEWPORT_WIDTH);

    printf("Create robot panels\n");

    // Create Robot Panel Container
    m_pRobotPanelContainer = new QFrame;
    m_pRobotPanelContainer->setFrameStyle(QFrame::Box);
    QVBoxLayout * pRobotPanelContainerLayout = new QVBoxLayout;
    for(int iter = 0; iter < MAX_NUM_ROBOTS; iter++){
	m_pRobotPanels[iter] = new RobotPanel(this, iter);
	pRobotPanelContainerLayout->addWidget(m_pRobotPanels[iter]);
    }
    m_pRobotPanelContainer->setLayout(pRobotPanelContainerLayout);
    m_lRobotPanelBorderWidth = m_pRobotPanels[0]->lineWidth();

    printf("Layout widgets\n");

    // Layout widgets
    QVBoxLayout * pVLayout = new QVBoxLayout;
    pVLayout->addWidget(m_pViewPort);
    pVLayout->addWidget(m_pControlPanel);
    QHBoxLayout * pHLayout = new QHBoxLayout;
    pHLayout->addLayout(pVLayout);
    pHLayout->addWidget(m_pRobotPanelContainer);
    QWidget * pCentralWidget = new QWidget;
    pCentralWidget->setLayout(pHLayout);
    setCentralWidget(pCentralWidget);

    printf("Create robots + simulation interface\n");
    m_SimulationInterface.Init(0, "Simulation", PLAYER_SERVER_IP, PLAYER_SERVER_PORT, ROBOT_SIMULATION);
    m_SimulationInterface.GetSimulation()->SetPose2d("WorldController", 1, 2, 3);

	printf("Creating Bombs\n");
    char bombString[128];
    // Create bombs
    memset(bombString, 0, 128);
    for(long iter = 0; iter < BOMB_COUNTS[mode - 1]; iter++){
	snprintf(bombString, 127, "0bomb%li", iter);
	printf("Creating Bomb: %s\n", bombString);
	m_SimulationInterface.GetSimulation()->SetPose2d(bombString, 0, 0, 0);
	bombString[0] = '1';
	m_SimulationInterface.GetSimulation()->SetPose2d(bombString, BOMB_INFO_X[mode-1][iter], -1*BOMB_INFO_Y[mode-1][iter], BOMB_INFO_Z[mode-1][iter]);
	printf("Positioning Bomb: %s <%f,%f,%f>\n", bombString, BOMB_INFO_X[mode-1][iter], -1*BOMB_INFO_Y[mode-1][iter], BOMB_INFO_Z[mode-1][iter]);
	bombString[0] = '2';
	m_SimulationInterface.GetSimulation()->SetPose2d(bombString, BOMB_INFO_R1[mode-1][iter], BOMB_INFO_R2[mode-1][iter], BOMB_INFO_R3[mode-1][iter]);
	printf("Rotating Bomb: %s <%f,%f,%f>\n", bombString, BOMB_INFO_R1[mode-1][iter], BOMB_INFO_R2[mode-1][iter], BOMB_INFO_R3[mode-1][iter]);
	m_lBombFound[iter] = BOMB_NOT_FOUND;
    }

	AddRobot(1, "Rosie", ROBOT_POSITION2D | ROBOT_CAMERA);
	if(mode >= 2) {
		AddRobot(4, "R2D2", ROBOT_POSITION2D | ROBOT_CAMERA);
		if(mode >= 3) {
			AddRobot(7, "Bender", ROBOT_POSITION2D | ROBOT_CAMERA);
			AddRobot(10, "Cherry2000", ROBOT_POSITION2D | ROBOT_CAMERA);
		}
	}

    // Eventually scan the list of created robots to record robot id's and select a current robot
    // Since the robots are fixed for this version of the interface, this may not be needed
    
    ChangeRobot(0); // Because the robot threads have not been started yet, locking is not needed

    printf("Setting robot names: %d\n", (int)MAX_NUM_ROBOTS);

    // Set robot names
    for(long iter = 0; iter < MAX_NUM_ROBOTS; iter++){
	if(m_SharedData.DoesRobotExist(iter)){
		m_pRobotPanels[iter]->SetName(m_SharedData.GetRobotData(iter)->configuration.name);
		printf("Passing: %s\n", m_SharedData.GetRobotData(iter)->configuration.name);
	}
    }

	for(unsigned long iter = 0; iter < m_RobotThreads.size(); iter++)
		m_RobotThreads[iter]->start();

	m_bMoveUp = false;
	m_bMoveDown = false;
	m_bMoveLeft = false;
	m_bMoveRight = false;

	printf("Disable unsed robot panels\n");
	
    // Disable/Grey out unused robot panels
    for(long iter = m_RobotThreads.size(); iter < MAX_NUM_ROBOTS; iter++)
	m_pRobotPanels[iter]->setDisabled(true);

    printf("Configure timer\n");

    // Configure timer
    m_pTimer = new QTimer;
    m_pTimer->setSingleShot(false);
    m_pTimer->setInterval(UPDATE_INTERVAL);
    connect(m_pTimer, SIGNAL(timeout()), this, SLOT(Update()));
    m_pTimer->start();

    printf("Open joystick\n");

    // Open joystick
    m_pJoystick = SDL_JoystickOpen(0);
    if(!m_pJoystick){
	QMessageBox::warning(this, "Joystick not found",
			     "A joystick was not found.  Manipulation capabilities may be reduced.");
    }
    for(long iter = 0; iter < 6; iter++) m_bJoystickButtonPressed[iter] = false;
}

MainWindow::~MainWindow(){
    m_SharedData.Lock();
    for(long iter = 0; iter < MAX_NUM_ROBOTS; iter++){
	if(m_SharedData.DoesRobotExist(iter)){
	    m_SharedData.GetRobotData(iter)->status.running = false;
	}
    }
    m_SharedData.Unlock();

    // Wait for threads to finish.  If a timeout occurs, forcibly terminate thread
    for(unsigned long iter = 0; iter < m_RobotThreads.size(); iter++){
	if(!m_RobotThreads[iter]->wait(ROBOT_THREAD_TERMINATION_WAIT_PERIOD)){
	    //m_RobotThreads[iter]->terminate();
	}
    }

    // Close joystick
    if(m_pJoystick){
	SDL_JoystickClose(m_pJoystick);
	m_pJoystick = NULL;
    }
}

/**
 * Add a new robot to the interface
 */
void MainWindow::AddRobot(int id, QString name, int configFlags){
	printf("%d: %s (%d)\n", id, name.toLatin1().data(), configFlags);
        RobotThread * pRobotThread = new RobotThread;
	pRobotThread->Init(id, name, PLAYER_SERVER_IP, PLAYER_SERVER_PORT, configFlags, &m_SharedData);
	m_RobotThreads.push_back(pRobotThread);
}

void MainWindow::closeEvent(QCloseEvent * event){
    event->accept();
}

void MainWindow::Update(){
    unsigned int * bits;
    unsigned char imageBuffer[3 * VIDEO_WIDTH * VIDEO_HEIGHT];
    unsigned long index;
    short xAxisRaw = 0, yAxisRaw = 0;
    float xAxis = 0.0, yAxis = 0.0;
    ROBOT_DATA * robotData;
    bool bCopyVideo = false;
    bool bToggleInterface = false;
    bool bIncreaseRobotNumber = false;
    bool bDecreaseRobotNumber = false;
    float distance, distanceX, distanceY;
    long bombMode;
    char bombString[128];

    // check joystick status
    if(m_pJoystick){
	SDL_JoystickUpdate();
	
	// Currently hard-coded for a WingMan Attack 2 joystick
	// Toggle Interface
	if(SDL_JoystickGetButton(m_pJoystick, 0)){
	    if(!m_bJoystickButtonPressed[0]){
		m_bJoystickButtonPressed[0] = true;
		bToggleInterface = true;
	    }
	}
	else m_bJoystickButtonPressed[0] = false;
	
	// Decrease robot number
	if(SDL_JoystickGetButton(m_pJoystick, 1)){
	    if(!m_bJoystickButtonPressed[1]){
		m_bJoystickButtonPressed[1] = true;
		bDecreaseRobotNumber = true;
	    }
	}
	else m_bJoystickButtonPressed[1] = false;
	
	// Increase robot number
	if(SDL_JoystickGetButton(m_pJoystick, 2)){
	    if(!m_bJoystickButtonPressed[2]){
		m_bJoystickButtonPressed[2] = true;
		bIncreaseRobotNumber = true;
	    }
	}
	else m_bJoystickButtonPressed[2] = false;
	
	if(SDL_JoystickGetButton(m_pJoystick, 3)){
	    if(!m_bJoystickButtonPressed[3]){
		m_bJoystickButtonPressed[3] = true;
	    }
	}
	else m_bJoystickButtonPressed[3] = false;
	
	if(SDL_JoystickGetButton(m_pJoystick, 4)){
	    if(!m_bJoystickButtonPressed[4]){
		m_bJoystickButtonPressed[4] = true;
	    }
	}
	else m_bJoystickButtonPressed[4] = false;
	
	if(SDL_JoystickGetButton(m_pJoystick, 5)){
	    if(!m_bJoystickButtonPressed[5]){
		m_bJoystickButtonPressed[5] = true;
	    }
	}
	else m_bJoystickButtonPressed[5] = false;
	
	// Get axis data
	xAxisRaw = SDL_JoystickGetAxis(m_pJoystick, 1);
	yAxisRaw = SDL_JoystickGetAxis(m_pJoystick, 0);
	
	// Scale axis data
	xAxis = -1.0 * (xAxisRaw / 65536.0); // -0.5 <-> 0.5
	yAxis = -1.0 * (yAxisRaw / 32768.0); // -1.0 <-> 1.0
    }
    else{
	xAxis = 0.0;
	yAxis = 0.0;
    }

    // Handle keyboard tele-operation
    if(m_bMoveUp) xAxis++;
    if(m_bMoveDown) xAxis--;
    if(m_bMoveLeft) yAxis += 0.5;
    if(m_bMoveRight) yAxis -= 0.5;

    // Limit speeds
    if(xAxis >  DRIVE_SPEED) xAxis = DRIVE_SPEED;
    if(xAxis < -1*DRIVE_SPEED) xAxis = -1*DRIVE_SPEED;
    if(yAxis >  ROTATE_SPEED) yAxis = ROTATE_SPEED;
    if(yAxis < -1*ROTATE_SPEED) yAxis = -1*ROTATE_SPEED;

    m_SharedData.Lock();

    if(bToggleInterface){
	if(m_CurrentInterface == CURRENT_INTERFACE_MANIPULATION) SwitchToOrchestrationPanel();
	else SwitchToManipulationPanel();
    }

    if(bIncreaseRobotNumber){
	long currentRobot = m_SharedData.GetCurrentRobot() + 1;
	while(!m_SharedData.DoesRobotExist(currentRobot)) currentRobot = (currentRobot + 1) % MAX_NUM_ROBOTS;
	ChangeRobot(currentRobot);
    }

    if(bDecreaseRobotNumber){
	long currentRobot = m_SharedData.GetCurrentRobot() - 1;
	while(!m_SharedData.DoesRobotExist(currentRobot)){
	    currentRobot--;
	    if(currentRobot < 0) currentRobot = MAX_NUM_ROBOTS - 1;
	}
	ChangeRobot(currentRobot);
    }

    bombMode = m_SharedData.GetMode() - 1;

    // Send commands and update per robot GUI items
    for(long iter = 0; iter < m_SharedData.GetRobotCount(); iter++){
	robotData = m_SharedData.GetRobotData(iter);

	robotData->status.xSpeed = 0.0;
	robotData->status.yawSpeed = 0.0;

	for(long bombIter = 0; bombIter < BOMB_COUNTS[bombMode]; bombIter++){
	    if(m_lBombFound[bombIter] == BOMB_NOT_FOUND){
		distanceX = BOMB_INFO_X[bombMode][bombIter] - robotData->status.xPosition;
		distanceY = BOMB_INFO_Y[bombMode][bombIter] - robotData->status.yPosition;
		distance = sqrt(distanceX*distanceX+distanceY*distanceY);
		if(distance < MINIMUM_BOMB_FINDING_DISTANCE){
		    m_lBombFound[iter] = BOMB_FOUND;
		    memset(bombString, 0, 128);
		    snprintf(bombString, 127, "3bomb%li", bombIter);
		    m_SimulationInterface.GetSimulation()->SetPose2d(bombString, 0, 0, 0);
		}
	    }
	}

	m_pRobotPanels[iter]->SetLocked(robotData->status.atTask);
	m_pRobotPanels[iter]->SetBatteryLevel(robotData->status.batteryLevel);
	m_pRobotPanels[iter]->SetPose(robotData->status.xPosition, robotData->status.yPosition, robotData->status.orientation);
	m_pRobotPanels[iter]->SetProgress(robotData->status.overallProgress, robotData->status.maxProgress);
	m_pRobotPanels[iter]->SetStalled(robotData->status.stalled);
    }
    if(m_CurrentInterface == CURRENT_INTERFACE_MANIPULATION && m_SharedData.GetRobotCount() > 0){
	m_SharedData.GetRobotData(m_SharedData.GetCurrentRobot())->status.xSpeed = xAxis;
	m_SharedData.GetRobotData(m_SharedData.GetCurrentRobot())->status.yawSpeed = yAxis;
    }    
    
    if(m_CurrentInterface == CURRENT_INTERFACE_MANIPULATION){
	if(m_SharedData.GetRobotCount() > 0 && m_SharedData.GetRobotData(m_SharedData.GetCurrentRobot())->status.batteryLevel > 0){
	    bCopyVideo = true;
	    memcpy(imageBuffer, m_SharedData.GetVideoData(), 3*VIDEO_WIDTH*VIDEO_HEIGHT);
	}
	else{
	    m_pImage->load("images/nosignal.png");
	}
	m_pManipulationPanel->Update();
    }
    else{
	m_pOrchestrationPanel->Update();
    }
    
    m_SharedData.Unlock();

    for(long bombIter = 0; bombIter < BOMB_COUNTS[bombMode]; bombIter++){
	if(m_lBombFound[bombIter] == BOMB_FOUND){
	    m_lBombFound[bombIter] = BOMB_DEFUSED;
	    QMessageBox::information(this, "Bomb Found", "A bomb has been found and defused.");
	}
    }

    if(bCopyVideo){
	bits = (unsigned int*)m_pImage->bits();
	for(index = 0; index < 3*VIDEO_WIDTH*VIDEO_HEIGHT; index += 3){
	    *bits = (imageBuffer[index + 0] << 16) | (imageBuffer[index + 1] << 8) | imageBuffer[index + 2];
	    bits++;
	}
    }

    m_pViewPort->Update();
}

void MainWindow::keyPressEvent(QKeyEvent * event){

    switch(event->key()){
	case Qt::Key_F1:
	    if(m_CurrentInterface == CURRENT_INTERFACE_MANIPULATION){
		m_SharedData.Lock();
		if(m_SharedData.DoesRobotExist(0)) ChangeRobot(0);
		m_SharedData.Unlock();
	    }
	    break;
	    
	case Qt::Key_F2:
	    if(m_CurrentInterface == CURRENT_INTERFACE_MANIPULATION){
		m_SharedData.Lock();
		if(m_SharedData.DoesRobotExist(1)) ChangeRobot(1);
		m_SharedData.Unlock();
	    }
	    break;

	case Qt::Key_F3:
	    if(m_CurrentInterface == CURRENT_INTERFACE_MANIPULATION){
		m_SharedData.Lock();
		if(m_SharedData.DoesRobotExist(2)) ChangeRobot(2);
		m_SharedData.Unlock();
	    }
	    break;

	case Qt::Key_F4:
	    if(m_CurrentInterface == CURRENT_INTERFACE_MANIPULATION){
		m_SharedData.Lock();
		if(m_SharedData.DoesRobotExist(3)) ChangeRobot(3);
		m_SharedData.Unlock();
	    }
	    break;

	case Qt::Key_Up:
	    if(m_CurrentInterface == CURRENT_INTERFACE_MANIPULATION){
		m_bMoveUp = true;
	    }
	    break;
	    
	case Qt::Key_Down:
	    if(m_CurrentInterface == CURRENT_INTERFACE_MANIPULATION){
		m_bMoveDown = true;
	    }
	    break;

	case Qt::Key_Left:
	    if(m_CurrentInterface == CURRENT_INTERFACE_MANIPULATION){
		m_bMoveLeft = true;
	    }
	    break;
	    
	case Qt::Key_Right:
	    if(m_CurrentInterface == CURRENT_INTERFACE_MANIPULATION){
		m_bMoveRight = true;
	    }
	    break;

	default:
	    QWidget::keyPressEvent(event);
	    break;
    };
}

void MainWindow::keyReleaseEvent(QKeyEvent * event){

    switch(event->key()){
	case Qt::Key_Up:
	    m_bMoveUp = false;
	    break;
	    
	case Qt::Key_Down:
	    m_bMoveDown = false;
	    break;
	    
	case Qt::Key_Left:
	    m_bMoveLeft = false;
	    break;
	    
	case Qt::Key_Right:
	    m_bMoveRight = false;
	    break;

	default:
	    QWidget::keyPressEvent(event);
	    break;
    };
}

void MainWindow::SwitchToOrchestrationPanel(){
    m_CurrentInterface = CURRENT_INTERFACE_ORCHESTRATION;
    m_pViewPort->SetInterface(m_CurrentInterface);
    m_pControlPanel->setCurrentWidget(m_pOrchestrationPanel);
}

void MainWindow::SwitchToManipulationPanel(){
    m_CurrentInterface = CURRENT_INTERFACE_MANIPULATION;
    m_pViewPort->SetInterface(m_CurrentInterface);
    m_pControlPanel->setCurrentWidget(m_pManipulationPanel);
}

void MainWindow::RobotClicked(long robotId){

    m_SharedData.Lock();
    ChangeRobot(robotId);
    m_SharedData.Unlock();
}

void MainWindow::RobotDoubleClicked(long robotId){

    m_SharedData.Lock();
    ChangeRobot(robotId);
    m_SharedData.Unlock();
    SwitchToManipulationPanel();
}

void MainWindow::EnableOverlay(long mode, long overlay, bool value){

    m_pViewPort->EnableOverlay(mode, overlay, value);
}

void MainWindow::AddTask(float x, float y){
    int result;
    std::vector<WAYPOINT> * pWaypoints;
    WAYPOINT waypoint;
    long insertPoint;
    long waypointCost;
    float distance, distanceX, distanceY;
    ROBOT_DATA * pRobotData;

    AddTaskDialog atd(&m_SharedData);
    result = atd.exec();
    if(result != QDialog::Accepted) return;
    
    m_SharedData.Lock();
    
    pRobotData = m_SharedData.GetRobotData(atd.GetSelectedRobot());

    // Populate new waypoint and update robot's next waypoint id
    pWaypoints = &pRobotData->status.waypoints;
    waypoint.id = pRobotData->status.nextWaypointId;
    pRobotData->status.nextWaypointId++;
    waypoint.x = x;
    waypoint.y = y;
    waypoint.task = atd.GetSelectedTask();
    printf("adding task type: %li\n", atd.GetSelectedTask());

    // Find insertion point
    insertPoint = atd.GetPrecedingWaypointId();
    for(long iter = pWaypoints->size()-1; iter >= 0; iter--){
	if((*pWaypoints)[iter].id == insertPoint){
	    insertPoint = iter;
	    break;
	}
    }
    insertPoint++;
    
    if(insertPoint == 0){
	distanceX = waypoint.x - pRobotData->status.xPosition;
	distanceY = waypoint.y - pRobotData->status.yPosition;
    }
    else{
	distanceX = waypoint.x - (*pWaypoints)[insertPoint - 1].x;
	distanceY = waypoint.y - (*pWaypoints)[insertPoint - 1].y;
    }

    distance = sqrt(distanceX*distanceX + distanceY*distanceY);
    printf("new task distance = %f\n", distance);
    waypoint.distance = distance;
    waypointCost = (long)(((float)WAYPOINT_COST) * distance);
    if(atd.GetSelectedTask() != TASK_WAYPOINT) waypointCost += TASK_COST;

    //if(pWaypoints->size() == 0){
    //pRobotData->status.maxProgress = waypointCost;
    //pRobotData->status.overallProgress = 0;
    //}
    //else 
    pRobotData->status.maxProgress += waypointCost;
    
    if((long)pWaypoints->size() > insertPoint + 1){
	if(insertPoint != 0){
	    distanceX = (*pWaypoints)[insertPoint - 1].x - (*pWaypoints)[insertPoint].x;
	    distanceY = (*pWaypoints)[insertPoint - 1].y - (*pWaypoints)[insertPoint].y;
	    distance = sqrt(distanceX*distanceX + distanceY*distanceY);
	    pRobotData->status.maxProgress -= (long)(WAYPOINT_COST * distance);
	}
	else{
	    distanceX = (*pWaypoints)[insertPoint].x - pRobotData->status.xPosition;
	    distanceY = (*pWaypoints)[insertPoint].y - pRobotData->status.yPosition;
	    distance = sqrt(distanceX*distanceX + distanceY*distanceY);
	    distance = pRobotData->status.startingWaypointDistance - distance;
	    pRobotData->status.maxProgress -= (long)(WAYPOINT_COST * distance);
	}
	
	distanceX = (*pWaypoints)[insertPoint].x - waypoint.x;
	distanceY = (*pWaypoints)[insertPoint].y - waypoint.y;
	distance = sqrt(distanceX*distanceX + distanceY*distanceY);
	pRobotData->status.maxProgress += (long)(WAYPOINT_COST * distance);
	
    }

    pWaypoints->insert(pWaypoints->begin()+insertPoint, waypoint);

    m_SharedData.Unlock();
}

void MainWindow::DeleteTasks(long robot){
    int result;
    std::vector<long> idList;
    std::vector<WAYPOINT> * pWaypoints;
    ROBOT_DATA * pRobotData;

    DeleteTaskDialog dtd(&m_SharedData, robot, &idList);
    result = dtd.exec();
    if(result != QDialog::Accepted) return;

    m_SharedData.Lock();
    if(!m_SharedData.DoesRobotExist(robot)){
	m_SharedData.Unlock();
	return;
    }

    pRobotData = m_SharedData.GetRobotData(robot);
    pWaypoints = &(pRobotData->status.waypoints);

    for(unsigned long deleteIter = 0; deleteIter < idList.size(); deleteIter++){
	for(unsigned long waypointIter = 0; waypointIter < pWaypoints->size(); waypointIter++){
	    if(idList[deleteIter] == (*pWaypoints)[waypointIter].id){
		EraseTask(robot, waypointIter);
		break;
	    }
	}
    }

    pRobotData->status.overallProgress = 1;
    pRobotData->status.maxProgress = 1;

    m_SharedData.Unlock();
}

void MainWindow::ChangeTask(long robot, long id){
    QStringList items;
    bool ok = false;
    ROBOT_DATA * pRobotData;
    long index = -1;

    items << "Waypoint" << "Chemical Sample Task" << "Dirt Sample Task";
    QString selection = QInputDialog::getItem(this, tr("Select new task"), tr("Task:                                     "), items, 0, false, &ok);
    if(!ok || selection.isEmpty()) return;

    m_SharedData.Lock();
    if(!m_SharedData.DoesRobotExist(robot)){
	m_SharedData.Unlock();
	return;
    }

    pRobotData = m_SharedData.GetRobotData(robot);

    for(unsigned long iter = 0; iter < pRobotData->status.waypoints.size(); iter++){
	if(pRobotData->status.waypoints[iter].id == id){
	    index = iter;
	    break;
	}
    }

    if(index == -1){
	m_SharedData.Unlock();
	return;
    }

    if(!selection.compare("Waypoint")){
	if(pRobotData->status.waypoints[index].task != TASK_WAYPOINT) pRobotData->status.maxProgress -= TASK_COST;
	pRobotData->status.waypoints[index].task = TASK_WAYPOINT;
    }
    else if(!selection.compare("Chemical Sample Task")){
	if(pRobotData->status.waypoints[index].task == TASK_WAYPOINT) pRobotData->status.maxProgress += TASK_COST;
	pRobotData->status.waypoints[index].task = TASK_CHEMICAL_SAMPLE;
	
    }
    else if(!selection.compare("Dirt Sample Task")){
	if(pRobotData->status.waypoints[index].task == TASK_WAYPOINT) pRobotData->status.maxProgress += TASK_COST;
	pRobotData->status.waypoints[index].task = TASK_DIRT_SAMPLE;
	
    }
    else assert(0);

    m_SharedData.Unlock();
}

void MainWindow::EraseTask(long robot, long index){
    ROBOT_DATA * pRobotData;
    float distance, xDistance, yDistance;

    pRobotData = m_SharedData.GetRobotData(robot);

    if(index < 0 || (unsigned long)index >= pRobotData->status.waypoints.size()) return;

    if(pRobotData->status.waypoints[index].task != TASK_WAYPOINT){
	printf("deleting task cost\n");
	pRobotData->status.maxProgress -= TASK_COST;
    }
    
    if(index == 0){
	printf("index == 0\n");
	xDistance = pRobotData->status.waypoints[index].x - pRobotData->status.xPosition;
	yDistance = pRobotData->status.waypoints[index].y - pRobotData->status.yPosition;
	distance = sqrt(xDistance*xDistance + yDistance*yDistance);
	distance = pRobotData->status.waypoints[index].distance - distance;
	printf("completed distance = %f of %f\n", distance, pRobotData->status.waypoints[index].distance);
	pRobotData->status.overallProgress -= (long)(WAYPOINT_COST * distance);
	
    }
    
    pRobotData->status.maxProgress -= (long)(WAYPOINT_COST * pRobotData->status.waypoints[index].distance);

    if((long)pRobotData->status.waypoints.size() > index + 1){
	pRobotData->status.maxProgress -= (long)(WAYPOINT_COST * pRobotData->status.waypoints[index + 1].distance);
	
	if(index == 0){
	    xDistance = pRobotData->status.waypoints[index + 1].x - pRobotData->status.xPosition;
	    yDistance = pRobotData->status.waypoints[index + 1].y - pRobotData->status.yPosition;
	}
	else{
	    xDistance = pRobotData->status.waypoints[index + 1].x - pRobotData->status.waypoints[index - 1].x;
	    yDistance = pRobotData->status.waypoints[index + 1].y - pRobotData->status.waypoints[index - 1].y;
	}
	distance = sqrt(xDistance*xDistance + yDistance*yDistance);
	printf("changed waypoint distance to %f\n", distance);
	pRobotData->status.waypoints[index + 1].distance = distance;
	pRobotData->status.maxProgress += (long)(WAYPOINT_COST * distance);

	if(index == 0){
	    pRobotData->status.currentWaypointDistance = distance;
	}
    }
    
    pRobotData->status.waypoints.erase(pRobotData->status.waypoints.begin() + index);

    //if(pRobotData->status.waypoints.size() == 0){
    //pRobotData->status.overallProgress = 1;
    //pRobotData->status.maxProgress = 1;
    //}
}

void MainWindow::DeleteTask(long robot, long id){
    ROBOT_DATA * pRobotData;
    long index = -1;

    printf("delete task with id %li\n", id);

    m_SharedData.Lock();
    if(!m_SharedData.DoesRobotExist(robot)){    
	printf("exist%li %li\n", robot, id);
	m_SharedData.Unlock();
	return;
    }

    pRobotData = m_SharedData.GetRobotData(robot);

    for(unsigned long iter = 0; iter < pRobotData->status.waypoints.size(); iter++){
	if(pRobotData->status.waypoints[iter].id == id){
	    index = iter;
	    break;
	}
    }

    if(index != -1){
	printf("erase task[%li]\n", index);
	EraseTask(robot, index);
    }

    m_SharedData.Unlock();
}

void MainWindow::SelectClosestRobot(float x, float y){
    float bestDistance = FLT_MAX;
    float distance;
    long robotId = -1;
    float robotX, robotY;
    
    m_SharedData.Lock();
    if(m_SharedData.GetRobotCount() < 1){
	m_SharedData.Unlock();
	return;
    }
    
    for(long iter = 0; iter < MAX_NUM_ROBOTS; iter++){ 
	if(m_SharedData.DoesRobotExist(iter)){
	    robotX = m_SharedData.GetRobotData(iter)->status.xPosition;
	    robotY = m_SharedData.GetRobotData(iter)->status.yPosition;
	    distance = sqrt((robotX-x)*(robotX-x)+(robotY-y)*(robotY-y));
	    if(distance < bestDistance){
		bestDistance = distance;
		robotId = iter;
	    }
	}
    }
    
    if(robotId != -1){
	ChangeRobot(robotId);
    }
    
    m_SharedData.Unlock();
}

void MainWindow::SetMoveUp(){
    if(m_CurrentInterface == CURRENT_INTERFACE_MANIPULATION) m_bMoveUp = true;
    else m_bMoveUp = false;

    //tempY += 0.1;
    //printf("Tempx %f tempy %f\n", tempX, tempY);
    //m_SimulationInterface.GetSimulation()->SetPose2d("1bomb0", tempX, tempY, 18.24);
}

void MainWindow::SetMoveDown(){
    if(m_CurrentInterface == CURRENT_INTERFACE_MANIPULATION) m_bMoveDown = true;
    else m_bMoveDown = false;

    //tempY -= 0.1;
    //printf("Tempx %f tempy %f\n", tempX, tempY);
    //m_SimulationInterface.GetSimulation()->SetPose2d("1bomb0", tempX, tempY, 18.24);
}

void MainWindow::SetMoveLeft(){
    if(m_CurrentInterface == CURRENT_INTERFACE_MANIPULATION) m_bMoveLeft = true;
    else m_bMoveLeft = false;

    //tempX -= 0.1;
    //printf("Tempx %f tempy %f\n", tempX, tempY);
    //m_SimulationInterface.GetSimulation()->SetPose2d("1bomb0", tempX, tempY, 18.24);
}

void MainWindow::SetMoveRight(){
    if(m_CurrentInterface == CURRENT_INTERFACE_MANIPULATION) m_bMoveRight = true;
    else m_bMoveRight = false;

    //tempX += 0.1;
    //printf("Tempx %f tempy %f\n", tempX, tempY);
    //m_SimulationInterface.GetSimulation()->SetPose2d("1bomb0", tempX, tempY, 18.24);
};

void MainWindow::ClearMoveUp(){
    m_bMoveUp = false;
}

void MainWindow::ClearMoveDown(){
    m_bMoveDown = false;
}

void MainWindow::ClearMoveLeft(){
    m_bMoveLeft = false;
}

void MainWindow::ClearMoveRight(){
    m_bMoveRight = false;
}

void MainWindow::ChangeRobot(long robot){
    for(long iter = 0; iter < MAX_NUM_ROBOTS; iter++){
	if(iter == robot){
	    m_pRobotPanels[iter]->setLineWidth(3*m_lRobotPanelBorderWidth);
	    m_pRobotPanels[iter]->update();
	}
	else{
	    if(m_pRobotPanels[iter]->lineWidth() != m_lRobotPanelBorderWidth){
		m_pRobotPanels[iter]->setLineWidth(m_lRobotPanelBorderWidth);
		m_pRobotPanels[iter]->update();
	    }
	}
    }

    m_SharedData.SetCurrentRobot(robot);
}
