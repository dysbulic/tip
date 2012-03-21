#include "robotpanel.h"
#include "mainwindow.h"

#include <QLabel>
#include <QHBoxLayout>
#include <QVBoxLayout>
#include <QString>

RobotPanel::RobotPanel(MainWindow * pMainWindow, long id){

    m_pMainWindow = pMainWindow;
    m_lId = id;

    setFrameStyle(QFrame::Box);

    m_bClicked = false;
    m_pRobotName = new QLabel("Robot Name");
    m_pLocation = new QLabel("Loc: ");
    m_pOrientation = new QLabel("Ori: "); 

    m_pTaskProgressLabel = new QLabel("Task Progress:");
    m_pTaskProgress = new QProgressBar;
    //m_pTaskProgress->setRange(0, MAX_ROBOT_TASK_PROGRESS);
    m_pTaskProgress->setTextVisible(true);

    // Load Lock images
    m_LockImages[LOCK_IMAGE_NOT_LOCKED].load("images/no-lock.png");
    m_LockImages[LOCK_IMAGE_LOCKED].load("images/lock.png");

    m_pLockIcon = new QLabel("Lock");
    m_pLockIcon->setPixmap(m_LockImages[LOCK_IMAGE_NOT_LOCKED]);

    // Load battery images
    m_BatteryImages[BATTERY_IMAGE_100].load("images/battery-100.png");
    m_BatteryImages[BATTERY_IMAGE_50].load("images/battery-50.png");
    m_BatteryImages[BATTERY_IMAGE_25].load("images/battery-25.png");
    m_BatteryImages[BATTERY_IMAGE_10].load("images/battery-10.png");
    m_BatteryImages[BATTERY_IMAGE_0].load("images/battery-0.png");

    m_pBatteryIcon = new QLabel("Battery");
    m_pBatteryIcon->setPixmap(m_BatteryImages[INITIAL_BATTERY_IMAGE]);

    m_StallImages[NOT_STALLED_IMAGE].load("images/notstalled.png");
    m_StallImages[STALLED_IMAGE].load("images/stalled.png");
    m_pStallLabel = new QLabel("S");
    m_pStallLabel->setPixmap(m_StallImages[NOT_STALLED_IMAGE]);

    QHBoxLayout * pNameStallBatteryLayout = new QHBoxLayout;
    pNameStallBatteryLayout->addWidget(m_pRobotName);
    pNameStallBatteryLayout->addStretch();
    pNameStallBatteryLayout->addWidget(m_pLockIcon);
    pNameStallBatteryLayout->addWidget(m_pStallLabel);
    pNameStallBatteryLayout->addWidget(m_pBatteryIcon);

    QHBoxLayout * pPoseLayout = new QHBoxLayout;
    pPoseLayout->addWidget(m_pLocation);
    pPoseLayout->addWidget(m_pOrientation);
    
    QHBoxLayout * pProgressLayout = new QHBoxLayout;
    pProgressLayout->addWidget(m_pTaskProgressLabel);
    pProgressLayout->addWidget(m_pTaskProgress);


    QVBoxLayout * pMainLayout = new QVBoxLayout;
    pMainLayout->addLayout(pNameStallBatteryLayout);
    pMainLayout->addLayout(pPoseLayout);
    pMainLayout->addLayout(pProgressLayout);

    setLayout(pMainLayout);
}

RobotPanel::~RobotPanel() {}

void RobotPanel::SetName(char * name){
    char buffer[128];

    sprintf(buffer, "%li. %s", m_lId+1, name);
    QString string1(buffer);
    m_pRobotName->setText(string1);
}

void RobotPanel::SetPose(float x, float y, float orientation){
    char buffer[128];

    sprintf(buffer, "Pos: %.2f, %.2f", x, y);
    QString string1 = QString::fromAscii(buffer);
    m_pLocation->setText(string1);

    sprintf(buffer, "Ori: %.2f", 180.0*orientation/3.14159);
    QString string2 = QString::fromAscii(buffer);
    m_pOrientation->setText(string2);
}

void RobotPanel::SetProgress(long progress, long maxProgress){

    //m_pTaskProgress->setDisabled(false);
    m_pTaskProgress->setRange(0, maxProgress);
    m_pTaskProgress->setValue(progress);
}

void RobotPanel::SetBatteryLevel(long level){
    float scaledLevel;

    scaledLevel = (float)level / (float)STARTING_BATTERY_LEVEL;

    if(scaledLevel == 0){
	if(m_pBatteryIcon->pixmap() != &m_BatteryImages[BATTERY_IMAGE_0]){
	    m_pBatteryIcon->setPixmap(m_BatteryImages[BATTERY_IMAGE_0]);
	    m_pBatteryIcon->update();
	}
    }
    else if(scaledLevel <= .10){
	if(m_pBatteryIcon->pixmap() != &m_BatteryImages[BATTERY_IMAGE_10]){
	    m_pBatteryIcon->setPixmap(m_BatteryImages[BATTERY_IMAGE_10]);
	    m_pBatteryIcon->update();
	}
    }
    else if(scaledLevel <= .25){
	if(m_pBatteryIcon->pixmap() != &m_BatteryImages[BATTERY_IMAGE_25]){
	    m_pBatteryIcon->setPixmap(m_BatteryImages[BATTERY_IMAGE_25]);
	    m_pBatteryIcon->update();
	}
    }
    else if(scaledLevel <= .5){
	if(m_pBatteryIcon->pixmap() != &m_BatteryImages[BATTERY_IMAGE_50]){
	    m_pBatteryIcon->setPixmap(m_BatteryImages[BATTERY_IMAGE_50]);
	    m_pBatteryIcon->update();
	}
    }
    else{
	if(m_pBatteryIcon->pixmap() != &m_BatteryImages[BATTERY_IMAGE_100]){
	    m_pBatteryIcon->setPixmap(m_BatteryImages[BATTERY_IMAGE_100]);
	    m_pBatteryIcon->update();
	}
    }
}

void RobotPanel::SetLocked(bool locked){

    if(locked)   m_pLockIcon->setPixmap(m_LockImages[LOCK_IMAGE_LOCKED]);
    else m_pLockIcon->setPixmap(m_LockImages[LOCK_IMAGE_NOT_LOCKED]);
}

void RobotPanel::SetStalled(bool stalled){

    if(stalled)   m_pStallLabel->setPixmap(m_StallImages[STALLED_IMAGE]);
    else m_pStallLabel->setPixmap(m_StallImages[NOT_STALLED_IMAGE]);
}

void RobotPanel::mousePressEvent(QMouseEvent * event){
    (void)event;

    m_bClicked = true;
}
 
void RobotPanel::mouseReleaseEvent(QMouseEvent * event){
    
    if(!m_bClicked) return;
    m_bClicked = false;
    if(event->pos().x() < 0 || event->pos().y() < 0) return;
    if(event->pos().x() >= width() || event->pos().y() >= height()) return;
    m_pMainWindow->RobotClicked(m_lId);
}

void RobotPanel::mouseDoubleClickEvent(QMouseEvent * event){
    (void)event;
    
    m_pMainWindow->RobotDoubleClicked(m_lId);
}
