#ifndef __MANIPULATION_PANEL__
#define __MANIPULATION_PANEL__

#include <QWidget>
#include <QPushButton>
#include <QLabel>

class MainWindow;
class SharedData;

class ManipulationPanel : public QWidget{
    Q_OBJECT

 public:
    ManipulationPanel(MainWindow * pMainWindow, SharedData * pSharedData);
    ~ManipulationPanel();

    void Update();

 protected Q_SLOTS:
    void OrchestrationButtonPressed();

 private:
    MainWindow * m_pMainWindow;
    SharedData * m_pSharedData;

    QPushButton * m_pOrchestrationButton;
    QPushButton * m_pOverlayButton2;
    QPushButton * m_pOverlayButton3;
    QPushButton * m_pOverlayButton4;
    QPushButton * m_pOverlayButton5;
    QPushButton * m_pOverlayButton6;

    QPushButton * m_pUpButton;
    QPushButton * m_pDownButton;
    QPushButton * m_pLeftButton;
    QPushButton * m_pRightButton;

    QLabel * m_pCurrentRobotLabel;
    QLabel * m_pLabel2;
    QLabel * m_pLabel3;
    QLabel * m_pLabel4;

    QPushButton * m_pSwitchToOrchestrationPanel;
};

#endif
