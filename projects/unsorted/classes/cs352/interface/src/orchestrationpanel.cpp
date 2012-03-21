#include "orchestrationpanel.h"
#include "mainwindow.h"
#include "shareddata.h"

#include <QGridLayout>

OrchestrationPanel::OrchestrationPanel(MainWindow * pMainWindow, SharedData * pSharedData){

    m_pMainWindow = pMainWindow;
    m_pSharedData = pSharedData;

    m_pBombOverlayButton = new QPushButton("Bomb");
    m_pChemicalOverlayButton = new QPushButton("Chemical");
    m_pOverlayButton3 = new QPushButton("Overlay 3");
    m_pOverlayButton4 = new QPushButton("Overlay 4");
    m_pOverlayButton5 = new QPushButton("Overlay 5");
    m_pOverlayButton6 = new QPushButton("Overlay 6");

    m_pBombOverlayButton->setCheckable(true);
    m_pChemicalOverlayButton->setCheckable(true);
    m_pOverlayButton3->setCheckable(true);
    m_pOverlayButton4->setCheckable(true);
    m_pOverlayButton5->setCheckable(true);
    m_pOverlayButton6->setCheckable(true);

    m_pOverlayButton3->setDisabled(true);
    m_pOverlayButton4->setDisabled(true);
    m_pOverlayButton5->setDisabled(true);
    m_pOverlayButton6->setDisabled(true);

    m_pCurrentRobotLabel = new QLabel("Current robot: ");
    m_pGroupsLabel = new QLabel("Groups:");
    m_pTasksLabel = new QLabel("Tasks:");

    m_pGroupsLabel->setDisabled(true);
    m_pTasksLabel->setDisabled(true);

    m_pSwitchToManipulationPanel = new QPushButton("Switch to manipulation");

    QGridLayout * pGridLayout = new QGridLayout;
    pGridLayout->addWidget(m_pBombOverlayButton, 0, 0);
    pGridLayout->addWidget(m_pChemicalOverlayButton, 0, 1);
    pGridLayout->addWidget(m_pOverlayButton3, 0, 2);
    pGridLayout->addWidget(m_pOverlayButton4, 0, 3);
    pGridLayout->addWidget(m_pOverlayButton5, 0, 4);
    pGridLayout->addWidget(m_pOverlayButton6, 0, 5);

    pGridLayout->addWidget(m_pCurrentRobotLabel, 1, 0);
    pGridLayout->addWidget(m_pGroupsLabel, 2, 0);
    pGridLayout->addWidget(m_pTasksLabel, 3, 0);

    pGridLayout->addWidget(m_pSwitchToManipulationPanel, 3, 5);

    setLayout(pGridLayout);

    connect(m_pSwitchToManipulationPanel, SIGNAL(clicked()), m_pMainWindow, SLOT(SwitchToManipulationPanel()));
    connect(m_pBombOverlayButton, SIGNAL(clicked()), this, SLOT(BombOverlayButtonPressed()));
    connect(m_pChemicalOverlayButton, SIGNAL(clicked()), this, SLOT(ChemicalOverlayButtonPressed()));
}

OrchestrationPanel::~OrchestrationPanel() {}


void OrchestrationPanel::Update(){
    char buffer[128];

    sprintf(buffer, "Current robot: %s", m_pSharedData->GetRobotData(m_pSharedData->GetCurrentRobot())->configuration.name);
    QString string1 = QString::fromAscii(buffer);
    m_pCurrentRobotLabel->setText(string1);
}

void OrchestrationPanel::ChemicalOverlayButtonPressed(){

    m_pMainWindow->EnableOverlay(CURRENT_INTERFACE_ORCHESTRATION, ORCHESTRATION_INTERFACE_CHEMICAL_OVERLAY, m_pChemicalOverlayButton->isChecked());
}

void OrchestrationPanel::BombOverlayButtonPressed(){

    m_pMainWindow->EnableOverlay(CURRENT_INTERFACE_ORCHESTRATION, ORCHESTRATION_INTERFACE_BOMB_OVERLAY, m_pBombOverlayButton->isChecked());
}
