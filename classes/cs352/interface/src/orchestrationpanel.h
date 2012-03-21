#ifndef __ORCHESTRATION_PANEL__
#define __ORCHESTRATION_PANEL__

#include <QWidget>
#include <QPushButton>
#include <QLabel>

class MainWindow;
class SharedData;

class OrchestrationPanel : public QWidget{
    Q_OBJECT

 public:
    OrchestrationPanel(MainWindow * pMainWindow, SharedData * pSharedData);
    ~OrchestrationPanel();

    void Update();

 protected Q_SLOTS:
    void BombOverlayButtonPressed();
    void ChemicalOverlayButtonPressed();

 private:
    MainWindow * m_pMainWindow;
    SharedData * m_pSharedData;

    // Overlay buttons
    QPushButton * m_pBombOverlayButton;
    QPushButton * m_pChemicalOverlayButton;
    QPushButton * m_pOverlayButton3;
    QPushButton * m_pOverlayButton4;
    QPushButton * m_pOverlayButton5;
    QPushButton * m_pOverlayButton6;

    QLabel * m_pCurrentRobotLabel;
    QLabel * m_pGroupsLabel;
    QLabel * m_pTasksLabel;

    QPushButton * m_pSwitchToManipulationPanel;
};

#endif
