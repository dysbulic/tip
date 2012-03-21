#include "global.h"
#include "viewport.h"
#include "mainwindow.h"
#include <assert.h>

#include <QHelpEvent>

ViewPort::ViewPort(MainWindow * pMainWindow, SharedData * pSharedData, QImage * pImage, QMutex * pMutex){
    m_pMainWindow = pMainWindow;
    m_pSharedData = pSharedData;
    m_pVideoImage = pImage;
    m_pMutex = pMutex;

    m_pAddTaskAction = new QAction(tr("Add Task"), this);
    m_pSelectClosestRobotAction = new QAction(tr("Select Closest Robot"), this);
    m_pChangeTaskAction = new QAction(tr("Change Task Type"), this);
    m_pDeleteTaskAction = new QAction(tr("Delete Task"), this);
    m_pDeleteTaskDialogAction = new QAction(tr("Delete Task(s)"), this);

    for(long interfaceIter = 0; interfaceIter < MAX_INTERFACE_MODES; interfaceIter++){
	for(long overlayIter = 0; overlayIter < MAX_OVERLAYS_PER_INTERFACE; overlayIter++){
	    m_bEnabledOverlays[interfaceIter][overlayIter] = false;
	}
    }

    m_lClickedRobot = -1;

    for(long iter = 0; iter < MAX_NUM_ROBOTS; iter++){
	memset(&m_RobotLocations[iter], 0, sizeof(LOCATION));
	m_RobotLocations[iter].selected = NOT_SELECTED;
    }

    // Fake values until a real map is created
    m_fMapOriginOffsetX = 390;//450;//640;//-40.0;
    m_fMapOriginOffsetY = 425;//480;//-55.0;
    m_fMapScaleFactorX = 33.0;//1.0/15.0;
    m_fMapScaleFactorY = 34.0;

    m_ChemicalTimer.setInterval(30*1000);
    m_ChemicalTimer.setSingleShot(true);
    m_ChemicalTimer.start();
    m_bFirstChemicalImage = true;
    connect(&m_ChemicalTimer, SIGNAL(timeout()), this, SLOT(ChemicalTimer()));

}

ViewPort::~ViewPort() {}

/**
 * Pre-initialization function that collects a list of overlays
 */
//void ViewPort::AddOverlay(QString name, QString file){
//}
	
void ViewPort::initializeGL(){
    QImage * pImage = new QImage;
    QImage * pImageo = new QImage;
    long mode = 1;

    printf("Create textures\n");

	m_pSharedData->Lock();
    mode = m_pSharedData->GetMode();
    m_pSharedData->Unlock();

    if(mode < 1 || mode > 3){
	printf("Bad mode of %li in ViewPort::initializeGL\n", mode);
	QMessageBox::warning(this, "Bad Mode", "Bad mode in ViewPort::initializeGL");
	//return;
    }

    // Create textures
    pImage->load("images/map.png");
    m_MapTexture[ALPHA_100] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);
    pImage->load("images/map-o.png");
    m_MapTexture[ALPHA_20] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);

    if(mode == 1){
	pImage->load("images/1-chem.png");
	pImageo->load("images/1-chem-o.png");
    }
    else if(mode == 2){
	pImage->load("images/2-chem.png");
	pImageo->load("images/2-chem-o.png");
    }
    else{
	pImage->load("images/3-chem.png");
	pImageo->load("images/3-chem-o.png");
    }
    m_ChemicalOverlayTextures[ALPHA_100] = bindTexture(*pImage,  GL_TEXTURE_2D, GL_RGBA);
    m_ChemicalOverlayTextures[ALPHA_20] = bindTexture(*pImageo, GL_TEXTURE_2D, GL_RGBA);

    if(mode == 1){
	pImage->load("images/1-bomb.png");
	pImageo->load("images/1-bomb-o.png");
    }
    else if(mode == 2){
	pImage->load("images/2-bomb.png");
	pImageo->load("images/2-bomb-o.png");
    }
    else{
	pImage->load("images/3-bomb.png");
	pImageo->load("images/3-bomb-o.png");
    }
    m_BombOverlayTextures[ALPHA_100] = bindTexture(*pImage,  GL_TEXTURE_2D, GL_RGBA);
    m_BombOverlayTextures[ALPHA_20] = bindTexture(*pImageo, GL_TEXTURE_2D, GL_RGBA);

    m_VideoTexture = bindTexture(*m_pVideoImage, GL_TEXTURE_2D, GL_RGBA);

    pImage->load("images/waypoint.png");
    m_WaypointTexture[ALPHA_100] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);
    pImage->load("images/waypoint-o.png");
    m_WaypointTexture[ALPHA_20] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);

    pImage->load("images/dirt_task.png");
    m_DirtTaskTexture[ALPHA_100] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);
    pImage->load("images/dirt_task-o.png");
    m_DirtTaskTexture[ALPHA_20] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);

    pImage->load("images/chemical_task.png");
    m_ChemicalTaskTexture[ALPHA_100] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);
    pImage->load("images/chemical_task-o.png");
    m_ChemicalTaskTexture[ALPHA_20] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);

    // Load in robot textures
    pImage->load("images/robot.png");
    m_RobotTextures[ALPHA_100][NOT_SELECTED] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);
    pImage->load("images/robot-o.png");
    m_RobotTextures[ALPHA_20][NOT_SELECTED] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);

    pImage->load("images/robot-s.png");
    m_RobotTextures[ALPHA_100][SELECTED] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);
    pImage->load("images/robot-so.png");
    m_RobotTextures[ALPHA_20][SELECTED] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);

    // Load in robot number textures
    pImage->load("images/1.png");
    m_RobotNumbers[ALPHA_100][0] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);
    pImage->load("images/1-o.png");
    m_RobotNumbers[ALPHA_20][0] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);

    pImage->load("images/2.png");
    m_RobotNumbers[ALPHA_100][1] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);
    pImage->load("images/2-o.png");
    m_RobotNumbers[ALPHA_20][1] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);

    pImage->load("images/3.png");
    m_RobotNumbers[ALPHA_100][2] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);
    pImage->load("images/3-o.png");
    m_RobotNumbers[ALPHA_20][2] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);

    pImage->load("images/4.png");
    m_RobotNumbers[ALPHA_100][3] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);
    pImage->load("images/4-o.png");
    m_RobotNumbers[ALPHA_20][3] = bindTexture(*pImage, GL_TEXTURE_2D, GL_RGBA);

    printf("Configure OpenGL\n");

    glMatrixMode(GL_PROJECTION);
    glLoadIdentity();
    gluOrtho2D(0, 1280, 0, 960);
    glMatrixMode(GL_MODELVIEW);

    glClearColor(0, 0, 0, 0);
    glDisable(GL_DEPTH_TEST);
    glEnable(GL_TEXTURE_2D);
    //glDisable(GL_BLEND);
    glEnable(GL_BLEND);
    glBlendFunc(GL_SRC_ALPHA, GL_ONE_MINUS_SRC_ALPHA);
}

void ViewPort::resizeGL(int width, int height){
    (void)width;
    (void)height;
}


void ViewPort::paintGL(){
    Draw();
}

void ViewPort::Update(){
    UpdateLocations();
    Draw();
    updateGL();
}

void ViewPort::Draw(){
    if(m_CurrentInterface == CURRENT_INTERFACE_MANIPULATION){
	RenderManipulationInterface();
	if(m_bEnabledOverlays[CURRENT_INTERFACE_MANIPULATION][MANIPULATION_INTERFACE_ORCHESTRATION_OVERLAY])
	    RenderOrchestrationInterface(ALPHA_20);
    }
    else if(m_CurrentInterface == CURRENT_INTERFACE_ORCHESTRATION) RenderOrchestrationInterface(ALPHA_100);
    else assert(0);   
}

void ViewPort::DrawWaypoints(long alpha){
    glColor4f(1.0, 1.0, 1.0, 1.0);

    glDisable(GL_BLEND);
    for(long robotIter = 0; robotIter < MAX_NUM_ROBOTS; robotIter++){
	
	// Draw connecting lines
	if(m_RobotWaypoints[robotIter].size() > 0){
	    glColor4f(0.0, 0.0, 1.0, 1.0);
	    glBegin(GL_LINE_STRIP);
	    
	    glVertex2f(m_RobotLocations[robotIter].x, m_RobotLocations[robotIter].y);
	    
	    for(unsigned long iter = 0; iter < m_RobotWaypoints[robotIter].size(); iter++){
		glVertex2f(m_RobotWaypoints[robotIter][iter].x, m_RobotWaypoints[robotIter][iter].y);
	    }
	    glEnd();
	}
    }
    
    glEnable(GL_BLEND);
    
    for(long robotIter = 0; robotIter < MAX_NUM_ROBOTS; robotIter++){
	for(unsigned long iter = 0; iter < m_RobotWaypoints[robotIter].size(); iter++){
	    
	    switch(m_RobotWaypoints[robotIter][iter].task){
		
		case TASK_WAYPOINT:
		    glBindTexture(GL_TEXTURE_2D, m_WaypointTexture[alpha]);		    
		    break;
		    
		case TASK_DIRT_SAMPLE:
		    glBindTexture(GL_TEXTURE_2D, m_DirtTaskTexture[alpha]);
		    break;
		    
		case TASK_CHEMICAL_SAMPLE:
		    glBindTexture(GL_TEXTURE_2D, m_ChemicalTaskTexture[alpha]);
		    break;
		    
		default:
		    assert(0);
		    break;
	    };
	    
	    glBegin(GL_QUADS);
	    glColor4f(1.0, 1.0, 1.0, 1);
	    
	    glTexCoord2f(0, 1);
	    glVertex2f(m_RobotWaypoints[robotIter][iter].x - WAYPOINT_SIZE/2.0, m_RobotWaypoints[robotIter][iter].y - WAYPOINT_SIZE/2.0);
	    
	    glTexCoord2f(1, 1);
	    glVertex2f(m_RobotWaypoints[robotIter][iter].x + WAYPOINT_SIZE/2.0, m_RobotWaypoints[robotIter][iter].y - WAYPOINT_SIZE/2.0);
	    
	    glTexCoord2f(1, 0);
	    glVertex2f(m_RobotWaypoints[robotIter][iter].x + WAYPOINT_SIZE/2.0, m_RobotWaypoints[robotIter][iter].y + WAYPOINT_SIZE/2.0);
	    
	    glTexCoord2f(0, 0);
	    glVertex2f(m_RobotWaypoints[robotIter][iter].x - WAYPOINT_SIZE/2.0, m_RobotWaypoints[robotIter][iter].y + WAYPOINT_SIZE/2.0);
	    glEnd();
	}
	
    } 
}

void ViewPort::DrawRobots(long alpha){

    glEnable(GL_BLEND);
    glColor4f(1.0, 1.0, 1.0, 1.0);
    
    for(long iter = 0; iter < MAX_NUM_ROBOTS; iter++){
	if(!m_RobotLocations[iter].exists) continue;

	glPushMatrix();
	glLoadIdentity();
	glTranslatef(m_RobotLocations[iter].x, m_RobotLocations[iter].y, 0);
	glRotatef(m_RobotLocations[iter].orientation, 0, 0, 1);

	glBindTexture(GL_TEXTURE_2D, m_RobotTextures[alpha][m_RobotLocations[iter].selected]);
	
	glBegin(GL_QUADS);
	glTexCoord2f(0, 0);
	glVertex2f(-1*ROBOT_RENDER_SIZE/2.0, -1*ROBOT_RENDER_SIZE/2.0);
	
	glTexCoord2f(1,0);
	glVertex2f(ROBOT_RENDER_SIZE/2.0, -1*ROBOT_RENDER_SIZE/2.0);
	
	glTexCoord2f(1, 1);
	glVertex2f(ROBOT_RENDER_SIZE/2.0, ROBOT_RENDER_SIZE/2.0);
	
	glTexCoord2f(0, 1);
	glVertex2f(-1*ROBOT_RENDER_SIZE/2.0, ROBOT_RENDER_SIZE/2.0);
	glEnd();

	glPopMatrix();

	glBindTexture(GL_TEXTURE_2D, m_RobotNumbers[alpha][iter]);

	glBegin(GL_QUADS);
	glTexCoord2f(0, 0);
	glVertex2f(m_RobotLocations[iter].x - ROBOT_RENDER_SIZE/2.0, m_RobotLocations[iter].y - ROBOT_RENDER_SIZE/2.0);
	
	glTexCoord2f(1,0);
	glVertex2f(m_RobotLocations[iter].x + ROBOT_RENDER_SIZE/2.0, m_RobotLocations[iter].y - ROBOT_RENDER_SIZE/2.0);
	
	glTexCoord2f(1, 1);
	glVertex2f(m_RobotLocations[iter].x + ROBOT_RENDER_SIZE/2.0, m_RobotLocations[iter].y + ROBOT_RENDER_SIZE/2.0);
	
	glTexCoord2f(0, 1);
	glVertex2f(m_RobotLocations[iter].x - ROBOT_RENDER_SIZE/2.0, m_RobotLocations[iter].y + ROBOT_RENDER_SIZE/2.0);
	glEnd();
    }
}

void ViewPort::mousePressEvent(QMouseEvent * event){
    QPoint point;
    long robotId;

    if(event->button() & Qt::LeftButton){
	point = event->pos();

	robotId = TestForRobotHit(point.x(), point.y());
	m_lClickedRobot = robotId;
    }
}

void ViewPort::mouseReleaseEvent(QMouseEvent * event){
    QPoint point;
    long robotId;

    if(event->button() & Qt::LeftButton){
	point = event->pos();

	robotId = TestForRobotHit(point.x(), point.y());
	if(robotId != -1 && m_lClickedRobot == robotId) m_pMainWindow->RobotClicked(robotId);
    }

    m_lClickedRobot = -1;
}

void ViewPort::mouseDoubleClickEvent(QMouseEvent * event){
    QPoint point;
    long robotId;

    if(event->button() & Qt::LeftButton){
	point = event->pos();

	robotId = TestForRobotHit(point.x(), point.y());
	if(robotId != -1) m_pMainWindow->RobotDoubleClicked(robotId);
    }
}

long ViewPort::TestForRobotHit(long x, long y){
    long tolerenceX, tolerenceY;  
    long testY = height() - y;

    tolerenceX = (long)(ROBOT_SIZE / 2.0);
    tolerenceY = (long)(ROBOT_SIZE / 2.0);

    for(long iter = 0; iter < MAX_NUM_ROBOTS; iter++){
	if(!m_RobotLocations[iter].exists) continue;
	if((x >= m_RobotLocations[iter].x - tolerenceX) && (x <= m_RobotLocations[iter].x + tolerenceX)){
	    if((testY >= m_RobotLocations[iter].y - tolerenceY) && (testY <= m_RobotLocations[iter].y + tolerenceY)){
		return(iter);
	    }
	}
    }

    return(-1);
}

bool ViewPort::TestForTaskHit(long x, long y, long * robot, long * id){
    long tolerenceX, tolerenceY;  
    long testY = height() - y;

    tolerenceX = (long)(WAYPOINT_SIZE / 2.0);
    tolerenceY = (long)(WAYPOINT_SIZE / 2.0);

    for(long robotIter = 0; robotIter < MAX_NUM_ROBOTS; robotIter++){
	if(!m_RobotLocations[robotIter].exists) continue;
	for(unsigned long iter = 0; iter < m_RobotWaypoints[robotIter].size(); iter++){
	    if((x >= m_RobotWaypoints[robotIter][iter].x - tolerenceX) && (x <= m_RobotWaypoints[robotIter][iter].x + tolerenceX)){
		if((testY >= m_RobotWaypoints[robotIter][iter].y - tolerenceY) && (testY <= m_RobotWaypoints[robotIter][iter].y + tolerenceY)){
		    *robot = robotIter;
		    *id = m_RobotWaypoints[robotIter][iter].id;
		    return(true);
		}
	    }
	}
    }

    return(false);
}

void ViewPort::EnableOverlay(long mode, long overlay, bool value){

    if(mode < 0 || overlay < 0) return;
    if(mode >= MAX_INTERFACE_MODES || overlay >= MAX_OVERLAYS_PER_INTERFACE) return;

    m_bEnabledOverlays[mode][overlay] = value;
}

void ViewPort::contextMenuEvent(QContextMenuEvent * event){
    long robot, id;
    
    if(m_CurrentInterface == CURRENT_INTERFACE_ORCHESTRATION){
	if((robot = TestForRobotHit(event->pos().x(), event->pos().y())) != -1){
	    RobotContextMenu(event, robot);
	}
	else if(TestForTaskHit(event->pos().x(), event->pos().y(), &robot, &id)){
	    TaskContextMenu(event, robot, id);
	}
	else{
	    StandardContextMenu(event);
	}
    }
}

void ViewPort::StandardContextMenu(QContextMenuEvent * event){
    QAction * pSelectedAction;
    float x, y;
    
     QMenu menu(this);
     menu.addAction(m_pAddTaskAction);
     menu.addAction(m_pSelectClosestRobotAction);
     pSelectedAction = menu.exec(event->globalPos());
     
     
     x = (event->pos().x() - m_fMapOriginOffsetX) / m_fMapScaleFactorX;
     y = ((height() - event->pos().y()) - m_fMapOriginOffsetY) / m_fMapScaleFactorY;
     
     if(pSelectedAction == m_pAddTaskAction) m_pMainWindow->AddTask(x, y);
     else if(pSelectedAction == m_pSelectClosestRobotAction) m_pMainWindow->SelectClosestRobot(x, y);     
}

void ViewPort::RobotContextMenu(QContextMenuEvent * event, long robot){
    QAction * pSelectedAction;
    
    QMenu menu(this);
    menu.addAction(m_pDeleteTaskDialogAction);
    pSelectedAction = menu.exec(event->globalPos());

    if(pSelectedAction == m_pDeleteTaskDialogAction) m_pMainWindow->DeleteTasks(robot);
}

 void ViewPort::TaskContextMenu(QContextMenuEvent * event, long robot, long id){
    QAction * pSelectedAction;
     
     QMenu menu(this);
     menu.addAction(m_pChangeTaskAction);
     menu.addAction(m_pDeleteTaskAction);
     pSelectedAction = menu.exec(event->globalPos());
          
     if(pSelectedAction == m_pChangeTaskAction) m_pMainWindow->ChangeTask(robot, id);
     else if(pSelectedAction == m_pDeleteTaskAction) m_pMainWindow->DeleteTask(robot, id);     
 }

void ViewPort::UpdateLocations(){
    ROBOT_DATA * robotData;
    WAYPOINT waypoint;
    long currentRobot;

    for(long iter = 0; iter < MAX_NUM_ROBOTS; iter++){
	m_RobotLocations[iter].exists = false;
	m_RobotWaypoints[iter].clear();
    }

    m_pSharedData->Lock();
    currentRobot = m_pSharedData->GetCurrentRobot();

    for(long iter = 0; iter < MAX_NUM_ROBOTS; iter++){

	if(!m_pSharedData->DoesRobotExist(iter)) continue;
    
	robotData = m_pSharedData->GetRobotData(iter);
	m_RobotLocations[iter].exists = true;
	m_RobotLocations[iter].x = robotData->status.xPosition * m_fMapScaleFactorX + m_fMapOriginOffsetX;
	m_RobotLocations[iter].y = robotData->status.yPosition * m_fMapScaleFactorY + m_fMapOriginOffsetY;
	m_RobotLocations[iter].orientation = (180.0/3.14159)*robotData->status.orientation + ROBOT_ANGLE_OFFSET;
	if(iter == currentRobot) m_RobotLocations[iter].selected = SELECTED;
	else m_RobotLocations[iter].selected = NOT_SELECTED;

	for(unsigned long waypointIter = 0; waypointIter < robotData->status.waypoints.size(); waypointIter++){
	    waypoint.id = robotData->status.waypoints[waypointIter].id;
	    waypoint.x = (robotData->status.waypoints[waypointIter].x * m_fMapScaleFactorX) + m_fMapOriginOffsetX;
	    waypoint.y = (robotData->status.waypoints[waypointIter].y * m_fMapScaleFactorY) + m_fMapOriginOffsetY;
	    waypoint.task = robotData->status.waypoints[waypointIter].task;
	    m_RobotWaypoints[iter].push_back(waypoint);
	}
    }

    m_pSharedData->Unlock();
}

bool ViewPort::event(QEvent * event){
    long robotId;
    QHelpEvent * helpEvent;
    char buffer[128];
    QString tipString;
    ROBOT_DATA * pRobotData;

    if(event->type() == QEvent::ToolTip){

	helpEvent = static_cast<QHelpEvent*>(event);
	robotId = TestForRobotHit(helpEvent->pos().x(), helpEvent->pos().y());
	if(robotId != -1){

	    m_pSharedData->Lock();
	    pRobotData = m_pSharedData->GetRobotData(robotId);
	    snprintf(buffer, 128, "Robot Name: %s", pRobotData->configuration.name);
	    tipString.append(buffer);

	    for(unsigned long iter = 0; iter < pRobotData->status.waypoints.size(); iter++){
		switch(pRobotData->status.waypoints[iter].task){
		    case TASK_WAYPOINT:
			snprintf(buffer, 128, "\nWaypoint (%.2f, %.2f)", pRobotData->status.waypoints[iter].x, pRobotData->status.waypoints[iter].y);
			break;
		    case TASK_CHEMICAL_SAMPLE:
			snprintf(buffer, 128, "\nChemical sample (%.2f, %.2f)", pRobotData->status.waypoints[iter].x, pRobotData->status.waypoints[iter].y);
			break;
		    case TASK_DIRT_SAMPLE:
			snprintf(buffer, 128, "\nDirt Sample (%.2f, %.2f)", pRobotData->status.waypoints[iter].x, pRobotData->status.waypoints[iter].y); 
			break;
		    default:
			assert(0);
			break;

		};
		tipString.append(buffer);
	    }	    

	    m_pSharedData->Unlock();

	    QToolTip::showText(helpEvent->globalPos(), tipString);
	}else QToolTip::hideText();
	    
    }
    return(QGLWidget::event(event));
}


void ViewPort::RenderOrchestrationInterface(long alpha){
    
    if(alpha == ALPHA_100){
	glBindTexture(GL_TEXTURE_2D, m_MapTexture[ALPHA_100]);
	glClear(GL_COLOR_BUFFER_BIT);	
	glLoadIdentity();
	glDisable(GL_BLEND);
    }
    else{
	glEnable(GL_BLEND);
	glBindTexture(GL_TEXTURE_2D, m_MapTexture[ALPHA_20]);
    }

    glColor4f(1.0, 1.0, 1.0, 1.0);
    
    // Draw main texture
    glBegin(GL_QUADS);
    glColor3f(255, 255, 255);
    
    glTexCoord2f(0, 1);
    glVertex2f(0, 960);
    
    glTexCoord2f(1, 1);
    glVertex2f(1280, 960);
    
    glTexCoord2f(1, 0);
    glVertex2f(1280, 0);
    
    glTexCoord2f(0, 0);
    glVertex2f(0, 0);
    
    glEnd(); 
    
    DrawWaypoints(alpha);
    DrawRobots(alpha);	 
    
    glEnable(GL_BLEND);
    if(m_bEnabledOverlays[CURRENT_INTERFACE_ORCHESTRATION][ORCHESTRATION_INTERFACE_CHEMICAL_OVERLAY]){
	// Draw chemical overlay texture
	glBindTexture(GL_TEXTURE_2D, m_ChemicalOverlayTextures[alpha]);

	glBegin(GL_QUADS);
	glColor3f(255, 255, 255);
	
	glTexCoord2f(0, 1);
	glVertex2f(0, 960);
	
	glTexCoord2f(1, 1);
	glVertex2f(1280, 960);
	
	glTexCoord2f(1, 0);
	glVertex2f(1280, 0);
	
	glTexCoord2f(0, 0);
	glVertex2f(0, 0);
	
	glEnd(); 
    }

    glEnable(GL_BLEND);
    if(m_bEnabledOverlays[CURRENT_INTERFACE_ORCHESTRATION][ORCHESTRATION_INTERFACE_BOMB_OVERLAY]){
	// Draw bomb overlay texture
	glBindTexture(GL_TEXTURE_2D, m_BombOverlayTextures[alpha]);

	glBegin(GL_QUADS);
	glColor3f(255, 255, 255);
	
	glTexCoord2f(0, 1);
	glVertex2f(0, 960);
	
	glTexCoord2f(1, 1);
	glVertex2f(1280, 960);
	
	glTexCoord2f(1, 0);
	glVertex2f(1280, 0);
	
	glTexCoord2f(0, 0);
	glVertex2f(0, 0);
	
	glEnd(); 
    }
}

void ViewPort::RenderManipulationInterface(){

    deleteTexture(m_VideoTexture);
    m_VideoTexture = bindTexture(*m_pVideoImage, GL_TEXTURE_2D, GL_RGBA);
	    
    glClear(GL_COLOR_BUFFER_BIT);
    glLoadIdentity();
    glDisable(GL_BLEND);

    // Draw main texture
    glColor4f(1.0, 1.0, 1.0, 1.0);
    glBegin(GL_QUADS);
    
    glTexCoord2f(0, 1);
    glVertex2f(0, 960);
    
    glTexCoord2f(1, 1);
    glVertex2f(1280, 960);
    
    glTexCoord2f(1, 0);
    glVertex2f(1280, 0);
    
    glTexCoord2f(0, 0);
    glVertex2f(0, 0);
    
    glEnd(); 
}

void ViewPort::ChemicalTimer(){
    m_bFirstChemicalImage = false;
}
