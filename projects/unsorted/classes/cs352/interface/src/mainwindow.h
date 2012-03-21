#ifndef __MAIN_WINDOW__
#define __MAIN_WINDOW__

const long VIEWPORT_WIDTH = 1280;
const long VIEWPORT_HEIGHT = 960;
const long UPDATE_INTERVAL = 100;

const long ROBOT_THREAD_TERMINATION_WAIT_PERIOD = 5000; // One second

#include "robot_and_sdl.h"

#include <QMainWindow>
#include <QKeyEvent>
#include <QCloseEvent>
#include <QTextEdit>
#include <QMenuBar>
#include <QTimer>
#include <QMutex>
#include <QImage>
#include <QStackedWidget>

#include "global.h"
#include "shareddata.h"
#include "robotthread.h"
#include "viewport.h"
#include "manipulationpanel.h"
#include "orchestrationpanel.h"
#include "robotpanel.h"
#include "bombs.h"

class MainWindow : public QMainWindow{

    Q_OBJECT

 public:
    MainWindow(long mode);
    ~MainWindow();
 
 public Q_SLOTS:
    void Update();
    void SwitchToOrchestrationPanel();
    void SwitchToManipulationPanel();
    void RobotClicked(long robotId);
    void RobotDoubleClicked(long robotId);
    void EnableOverlay(long mode, long overlay, bool value);

    void AddRobot(int id, QString name, int configFlags);
    void AddTask(float x, float y);
    void ChangeTask(long robot, long index);
    void DeleteTask(long robot, long id);
    void DeleteTasks(long robot);
    void EraseTask(long robot, long index);  // Shared data must be locked before calling this function
    void SelectClosestRobot(float x, float y);

    // Shared Data must be locked before calling this function
    void ChangeRobot(long iter);

    void SetMoveUp();
    void SetMoveDown();
    void SetMoveLeft();
    void SetMoveRight();
    void ClearMoveUp();
    void ClearMoveDown();
    void ClearMoveLeft();
    void ClearMoveRight();

 protected:
    void closeEvent(QCloseEvent * event);
    void keyPressEvent(QKeyEvent * event);
    void keyReleaseEvent(QKeyEvent * event);

 private:
    QTimer * m_pTimer;
    QMutex * m_pMutex;

    QMenu * m_pFileMenu;
    QAction * m_pFileMenuExit;

    long m_CurrentInterface;

    bool m_bMoveUp, m_bMoveDown, m_bMoveRight, m_bMoveLeft;

    ViewPort * m_pViewPort;
    OrchestrationPanel * m_pOrchestrationPanel;
    ManipulationPanel * m_pManipulationPanel;
    QStackedWidget * m_pControlPanel;

    QFrame * m_pRobotPanelContainer;
    RobotPanel * m_pRobotPanels[MAX_NUM_ROBOTS];
    long m_lRobotPanelBorderWidth;

    QImage * m_pImage;

    long m_lBombFound[MAX_BOMBS];

    Robot m_SimulationInterface;
    std::vector<RobotThread *> m_RobotThreads;
    SharedData m_SharedData;

    SDL_Joystick * m_pJoystick;
    bool m_bJoystickButtonPressed[6];
};

#endif
