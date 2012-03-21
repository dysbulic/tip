#ifndef __VIEWPORT__
#define __VIEWPORT__

#include <QtOpenGL/QtOpenGL>
#include <QMutex>
#include <QContextMenuEvent>
#include <QAction>
#include <QEvent>
#include <QTimer>

#include "global.h"
#include "shareddata.h"

const float ROBOT_RENDER_SIZE = 72;
const float ROBOT_SIZE = 24;
const float ROBOT_ANGLE_OFFSET = -90.0;
const long ROBOT_COLOR_RED = 255;
const long ROBOT_COLOR_GREEN = 0;
const long ROBOT_COLOR_BLUE = 0;

const float WAYPOINT_SIZE = 12;

const long ALPHA_100 = 0;
const long ALPHA_20 = 1;

const long SELECTED = 0;
const long NOT_SELECTED = 1;

class MainWindow;

typedef struct tagLOCATION{
    bool exists;
    long selected;
    float x, y, orientation;
} LOCATION;

class ViewPort : public QGLWidget{
    
    Q_OBJECT

 public:
    ViewPort(MainWindow * pMainWindow, SharedData * pSharedData, QImage * pImage, QMutex * pMutex);
    ~ViewPort();

    bool event(QEvent * event);

    // OpenGL functions
    void initializeGL();
    void resizeGL(int width, int height);
    void paintGL();

 public Q_SLOTS:
    void Update();
    void SetInterface(long mode) {m_CurrentInterface = mode;}
    void Draw();
    void DrawWaypoints(long alpha);
    void DrawRobots(long alpha);
    void EnableOverlay(long mode, long overlay, bool value);
    void ChemicalTimer();
    
 protected:
    bool TestForTaskHit(long x, long y, long * robot, long * index);
    long TestForRobotHit(long x, long y);
    void mousePressEvent(QMouseEvent * event);
    void mouseReleaseEvent(QMouseEvent * event);
    void mouseDoubleClickEvent(QMouseEvent * event);
    void contextMenuEvent(QContextMenuEvent * event);
    void UpdateLocations();
    void StandardContextMenu(QContextMenuEvent * event);
    void RobotContextMenu(QContextMenuEvent * event, long robot);
    void TaskContextMenu(QContextMenuEvent * event, long robot, long index);
    void RenderOrchestrationInterface(long alpha);
    void RenderManipulationInterface();

 private:
    MainWindow * m_pMainWindow;
    SharedData * m_pSharedData;
    long m_CurrentInterface;

    bool m_bFirstChemicalImage;
    QTimer m_ChemicalTimer;

    // Context menu options
    QAction * m_pAddTaskAction;
    QAction * m_pSelectClosestRobotAction;
    QAction * m_pChangeTaskAction;
    QAction * m_pDeleteTaskAction;
    QAction * m_pDeleteTaskDialogAction;

    bool m_bEnabledOverlays[MAX_INTERFACE_MODES][MAX_OVERLAYS_PER_INTERFACE];

    GLuint m_ChemicalOverlayTextures[2];
    GLuint m_BombOverlayTextures[2];
    GLuint m_VideoTexture;
    GLuint m_MapTexture[2];
    GLuint m_WaypointTexture[2];
    GLuint m_ChemicalTaskTexture[2];
    GLuint m_DirtTaskTexture[2];
    GLuint m_RobotNumbers[2][4];  // need to change
    GLuint m_RobotTextures[2][2];
    QImage * m_pVideoImage;
    QMutex * m_pMutex;
    long m_lClickedRobot;

    LOCATION m_RobotLocations[MAX_NUM_ROBOTS];
    std::vector<WAYPOINT> m_RobotWaypoints[MAX_NUM_ROBOTS];

    float m_fMapOriginOffsetX, m_fMapOriginOffsetY;
    float m_fMapScaleFactorX, m_fMapScaleFactorY;
};

#endif
