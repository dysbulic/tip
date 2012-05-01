#include "deletetaskdialog.h"
#include "shareddata.h"

#include <QHBoxLayout>
#include <QVBoxLayout>
#include <QListWidgetItem>

#include <assert.h>

DeleteTaskDialog::DeleteTaskDialog(SharedData* pSharedData, long robotId, std::vector<long> * pIdList) : QDialog(NULL, Qt::Dialog|Qt::WindowSystemMenuHint){

    m_pSharedData = pSharedData;
    m_pIdList = pIdList;

    m_pSharedData->Lock();
    if(m_pSharedData->DoesRobotExist(robotId))
    m_pRobotNameLabel = new QLabel(m_pSharedData->GetRobotData(robotId)->configuration.name);
    m_pSharedData->Unlock();

    m_pWaypointList = new QListWidget;
    m_pDeleteButton = new QPushButton(tr("Delete Task"));
    connect(m_pDeleteButton, SIGNAL(clicked()), this, SLOT(DeleteTask()));

    m_pDoneButton = new QPushButton(tr("Done"));
    m_pCancelButton = new QPushButton(tr("Cancel"));
    connect(m_pDoneButton, SIGNAL(clicked()), this, SLOT(accept()));
    connect(m_pCancelButton, SIGNAL(clicked()), this, SLOT(reject()));

    PopulateTaskList(robotId);

    QHBoxLayout * pNameLayout = new QHBoxLayout;
    pNameLayout->addStretch();
    pNameLayout->addWidget(m_pRobotNameLabel);
    pNameLayout->addStretch();

    QHBoxLayout * pDeleteLayout = new QHBoxLayout;
    pDeleteLayout->addStretch();
    pDeleteLayout->addWidget(m_pDeleteButton);
    pDeleteLayout->addStretch();

    QHBoxLayout * pDoneCancelLayout = new QHBoxLayout;
    pDoneCancelLayout->addStretch();
    pDoneCancelLayout->addWidget(m_pDoneButton);
    pDoneCancelLayout->addWidget(m_pCancelButton);
    pDoneCancelLayout->addStretch();

    QVBoxLayout * pMainLayout = new QVBoxLayout;
    pMainLayout->addLayout(pNameLayout);
    pMainLayout->addWidget(m_pWaypointList);
    pMainLayout->addLayout(pDeleteLayout);
    pMainLayout->addLayout(pDoneCancelLayout);

    setLayout(pMainLayout);
}

void DeleteTaskDialog::PopulateTaskList(long robotId){
    QListWidgetItem * pItem;
    std::vector<WAYPOINT> waypoints;
    char buffer[128];

    m_pWaypointList->clear();

    m_pSharedData->Lock();
    if(!m_pSharedData->DoesRobotExist(robotId)){
	m_pSharedData->Unlock();
	return;
    }
    waypoints = m_pSharedData->GetRobotData(robotId)->status.waypoints;
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
}

void DeleteTaskDialog::DeleteTask(){
    QListWidgetItem * pItem;
 
    pItem = m_pWaypointList->takeItem(m_pWaypointList->currentRow());
    if(pItem == NULL) return;
    m_pIdList->push_back((long)pItem->data(Qt::UserRole).toInt());
    delete pItem;
}
