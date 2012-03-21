#include "manipulationpanel.h"
#include "mainwindow.h"
#include "shareddata.h"

#include <QGridLayout>

ManipulationPanel::ManipulationPanel(MainWindow * pMainWindow, SharedData * pSharedData){

    m_pMainWindow = pMainWindow;
    m_pSharedData = pSharedData;

    m_pOrchestrationButton = new QPushButton("Orchestration");
    m_pOverlayButton2 = new QPushButton("Overlay 2");
    m_pOverlayButton3 = new QPushButton("Overlay 3");
    m_pOverlayButton4 = new QPushButton("Overlay 4");
    m_pOverlayButton5 = new QPushButton("Overlay 5");
    m_pOverlayButton6 = new QPushButton("Overlay 6");

    m_pOrchestrationButton->setCheckable(true);
    m_pOverlayButton2->setCheckable(true);
    m_pOverlayButton3->setCheckable(true);
    m_pOverlayButton4->setCheckable(true);
    m_pOverlayButton5->setCheckable(true);
    m_pOverlayButton6->setCheckable(true);

    m_pOverlayButton2->setDisabled(true);
    m_pOverlayButton3->setDisabled(true);
    m_pOverlayButton4->setDisabled(true);
    m_pOverlayButton5->setDisabled(true);
    m_pOverlayButton6->setDisabled(true);    

    m_pUpButton    = new QPushButton(QIcon("images/up.png"), "");
    m_pDownButton  = new QPushButton(QIcon("images/down.png"), "");
    m_pLeftButton  = new QPushButton(QIcon("images/left.png"), "");
    m_pRightButton = new QPushButton(QIcon("images/right.png"), "");

    connect(m_pUpButton, SIGNAL(pressed()), m_pMainWindow, SLOT(SetMoveUp()));
    connect(m_pUpButton, SIGNAL(released()), m_pMainWindow, SLOT(ClearMoveUp()));
    
    connect(m_pDownButton, SIGNAL(pressed()), m_pMainWindow, SLOT(SetMoveDown()));
    connect(m_pDownButton, SIGNAL(released()), m_pMainWindow, SLOT(ClearMoveDown()));
    
    connect(m_pLeftButton, SIGNAL(pressed()), m_pMainWindow, SLOT(SetMoveLeft()));
    connect(m_pLeftButton, SIGNAL(released()), m_pMainWindow, SLOT(ClearMoveLeft()));
    
    connect(m_pRightButton, SIGNAL(pressed()), m_pMainWindow, SLOT(SetMoveRight()));
    connect(m_pRightButton, SIGNAL(released()), m_pMainWindow, SLOT(ClearMoveRight()));

    m_pCurrentRobotLabel = new QLabel("Current robot: ");
    m_pLabel2 = new QLabel("Label 2");
    m_pLabel3 = new QLabel("Label 3");
    m_pLabel4 = new QLabel("Label 4");

    m_pLabel2->setDisabled(true);
    m_pLabel3->setDisabled(true);
    m_pLabel4->setDisabled(true);

    m_pSwitchToOrchestrationPanel = new QPushButton("Switch to Orchestration");

    QGridLayout * pGridLayout = new QGridLayout;
    pGridLayout->addWidget(m_pOrchestrationButton, 0, 0);
    pGridLayout->addWidget(m_pOverlayButton2, 0, 1);
    pGridLayout->addWidget(m_pOverlayButton3, 0, 2);
    pGridLayout->addWidget(m_pOverlayButton4, 0, 3);
    pGridLayout->addWidget(m_pOverlayButton5, 0, 4);
    pGridLayout->addWidget(m_pOverlayButton6, 0, 5);

    pGridLayout->addWidget(m_pCurrentRobotLabel, 1, 0);
    pGridLayout->addWidget(m_pLabel2, 2, 0);
    pGridLayout->addWidget(m_pLabel3, 3, 0);
    pGridLayout->addWidget(m_pLabel4, 4, 0);

    pGridLayout->addWidget(m_pUpButton, 2, 3);
    pGridLayout->addWidget(m_pLeftButton, 3, 2);
    pGridLayout->addWidget(m_pRightButton, 3, 4);
    pGridLayout->addWidget(m_pDownButton, 4, 3);

    pGridLayout->addWidget(m_pSwitchToOrchestrationPanel, 3, 5);

    setLayout(pGridLayout);

    connect(m_pSwitchToOrchestrationPanel, SIGNAL(clicked()), m_pMainWindow, SLOT(SwitchToOrchestrationPanel()));
    connect(m_pOrchestrationButton, SIGNAL(clicked()), this, SLOT(OrchestrationButtonPressed()));
}

ManipulationPanel::~ManipulationPanel() {}

void ManipulationPanel::Update(){
    char buffer[128];

    sprintf(buffer, "Current robot: %s", m_pSharedData->GetRobotData(m_pSharedData->GetCurrentRobot())->configuration.name);
    QString string1 = QString::fromAscii(buffer);
    m_pCurrentRobotLabel->setText(string1);
}

void ManipulationPanel::OrchestrationButtonPressed(){

    m_pMainWindow->EnableOverlay(CURRENT_INTERFACE_MANIPULATION, MANIPULATION_INTERFACE_ORCHESTRATION_OVERLAY, m_pOrchestrationButton->isChecked());
}
