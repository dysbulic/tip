#include "global.h"
#include "addtaskdialog.h"
#include "shareddata.h"

#include <QVBoxLayout>
#include <QHBoxLayout>
#include <QGridLayout>
#include <QVariant>
#include <QIcon>
#include <QListWidgetItem>

#include <vector>
#include <assert.h>

const int NEW_WAYPOINT_MARKER = -1;

AddTaskDialog::AddTaskDialog(SharedData * pSharedData) : QDialog(NULL, Qt::Dialog|Qt::WindowSystemMenuHint){
    int currentRobot;
    QString name;
    bool bAdd;

    setModal(true);
    m_pSharedData = pSharedData;

    m_pAddButton = new QPushButton(tr("Add Task"));
    m_pCancelButton = new QPushButton(tr("Cancel"));

    connect(m_pAddButton, SIGNAL(clicked()), this, SLOT(accept()));
    connect(m_pCancelButton, SIGNAL(clicked()), this, SLOT(reject()));

    m_pRobotLabel = new QLabel(tr("Robot:"));
    m_pRobotList = new QComboBox();
    m_pRobotList->setEditable(false);
    connect(m_pRobotList, SIGNAL(currentIndexChanged(int)), this, SLOT(UpdateWaypointList(int)));

    m_pWaypointList = new QListWidget;

    m_pUp = new QPushButton(QIcon("images/up.png"), "");
    m_pDown = new QPushButton(QIcon("images/down.png"), "");
    connect(m_pUp, SIGNAL(clicked()), this, SLOT(MoveUp()));
    connect(m_pDown, SIGNAL(clicked()), this, SLOT(MoveDown()));

    // Add robots to list    
    for(int iter = 0; iter < MAX_NUM_ROBOTS; iter++){
	
	bAdd = false;
	m_pSharedData->Lock();
	if(m_pSharedData->DoesRobotExist(iter)){
	    bAdd = true;
	    name.clear();
	    name.append(m_pSharedData->GetRobotData(iter)->configuration.name);
	}
	m_pSharedData->Unlock();

	if(bAdd){
	    m_pRobotList->addItem(name, QVariant(iter));
	}
    }

    // Set the current robot as the active list item
    m_pSharedData->Lock();
    currentRobot = (int)m_pSharedData->GetCurrentRobot();
    m_pSharedData->Unlock();
    m_pRobotList->setCurrentIndex(m_pRobotList->findData(currentRobot));

    UpdateWaypointList(m_pRobotList->currentIndex());

    m_pTaskLabel = new QLabel(tr("Task:"));
    m_pTaskList = new QComboBox;
    connect(m_pTaskList, SIGNAL(currentIndexChanged(int)), this, SLOT(UpdateSelectedTask(int)));
    m_pTaskList->addItem(tr("Waypoint"), QVariant((int)TASK_WAYPOINT));
    m_pTaskList->addItem(tr("Take chemical sample"), QVariant((int)TASK_CHEMICAL_SAMPLE));
    m_pTaskList->addItem(tr("Take dirt sample"), QVariant((int)TASK_DIRT_SAMPLE));
    m_pTaskList->setCurrentIndex(TASK_WAYPOINT);
    m_lSelectedTask = TASK_WAYPOINT;

    QHBoxLayout * pRobotLayout = new QHBoxLayout;
    pRobotLayout->addStretch();
    pRobotLayout->addWidget(m_pRobotLabel);
    pRobotLayout->addWidget(m_pRobotList);
    pRobotLayout->addStretch();

    QHBoxLayout * pWaypointLayout = new QHBoxLayout;
    pWaypointLayout->addWidget(m_pWaypointList);
    QVBoxLayout * pUpDownLayout = new QVBoxLayout;
    pUpDownLayout->addStretch();
    pUpDownLayout->addWidget(m_pUp);
    pUpDownLayout->addWidget(m_pDown);
    pUpDownLayout->addStretch();
    pWaypointLayout->addLayout(pUpDownLayout);

    QHBoxLayout * pAddCancelLayout = new QHBoxLayout;
    pAddCancelLayout->addWidget(m_pAddButton);
    pAddCancelLayout->addWidget(m_pCancelButton);

    QHBoxLayout * pTaskLayout = new QHBoxLayout;
    pTaskLayout->addWidget(m_pTaskLabel);
    pTaskLayout->addWidget(m_pTaskList);

    QVBoxLayout * pMainLayout = new QVBoxLayout;
    pMainLayout->addLayout(pRobotLayout);
    pMainLayout->addLayout(pWaypointLayout);
    pMainLayout->addLayout(pTaskLayout);
    pMainLayout->addLayout(pAddCancelLayout);

    setLayout(pMainLayout);
}

AddTaskDialog::~AddTaskDialog() {}

void AddTaskDialog::UpdateWaypointList(int index){
    QListWidgetItem * pItem;
    std::vector<WAYPOINT> waypoints;
    char buffer[128];

    m_lSelectedRobot = m_pRobotList->itemData(index).toInt();
    m_pWaypointList->clear();

    m_pSharedData->Lock();
    if(!m_pSharedData->DoesRobotExist(m_lSelectedRobot)){
	m_pSharedData->Unlock();
	return;
    }
    waypoints = m_pSharedData->GetRobotData(m_lSelectedRobot)->status.waypoints;
    m_pSharedData->Unlock();

    
    for(unsigned long iter = 0; iter < waypoints.size(); iter++){
	switch(waypoints[iter].task){
	    case TASK_WAYPOINT:
		sprintf(buffer, "Waypoint (%.2f, %.2f)", waypoints[iter].x, waypoints[iter].y);
		break;
		
	    case TASK_DIRT_SAMPLE:
		sprintf(buffer, "Dirt Sample (%.2f, %.2f)", waypoints[iter].x, waypoints[iter].y);
		break;
		
	    case TASK_CHEMICAL_SAMPLE:
		sprintf(buffer, "Chemical Sample (%.2f, %.2f)", waypoints[iter].x, waypoints[iter].y);
		break;

	    default:
		assert(0);
		break;
	};

	pItem = new QListWidgetItem(buffer);
	pItem->setData(Qt::UserRole, QVariant((int)waypoints[iter].id));
	m_pWaypointList->addItem(pItem);
    }

    m_iNewWaypointIndex = m_pWaypointList->count();
    
    pItem = new QListWidgetItem(tr("New Task"));
    pItem->setData(Qt::UserRole, NEW_WAYPOINT_MARKER);
    m_pWaypointList->addItem(pItem);
}

void AddTaskDialog::MoveUp(){
    QListWidgetItem * pItem;

    if(m_iNewWaypointIndex < 1) return;

    pItem = m_pWaypointList->takeItem(m_iNewWaypointIndex);
    m_iNewWaypointIndex--;
    m_pWaypointList->insertItem(m_iNewWaypointIndex, pItem);
}

void AddTaskDialog::MoveDown(){
    QListWidgetItem * pItem;

    if(m_iNewWaypointIndex >= m_pWaypointList->count()) return;

    pItem = m_pWaypointList->takeItem(m_iNewWaypointIndex);
    m_iNewWaypointIndex++;
    m_pWaypointList->insertItem(m_iNewWaypointIndex, pItem);
}

long AddTaskDialog::GetPrecedingWaypointId(){
    QListWidgetItem * pItem;
    
    if(m_iNewWaypointIndex == 0) return(-1);

    pItem = m_pWaypointList->item(m_iNewWaypointIndex - 1);
    return((long)pItem->data(Qt::UserRole).toInt());
}

void AddTaskDialog::UpdateSelectedTask(int index){

    m_lSelectedTask = m_pTaskList->itemData(index).toInt();
}
