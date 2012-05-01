#ifndef __ROBOT_PANEL__
#define __ROBOT_PANEL__

#include <QFrame>
#include <QMouseEvent>
#include <QProgressBar>

const long NUM_BATTERY_IMAGES = 5;
const long BATTERY_IMAGE_100 = 0;
const long BATTERY_IMAGE_50  = 1;
const long BATTERY_IMAGE_25  = 2;
const long BATTERY_IMAGE_10  = 3;
const long BATTERY_IMAGE_0   = 4;

const long NUM_LOCK_IMAGES = 2;
const long LOCK_IMAGE_NOT_LOCKED = 0;
const long LOCK_IMAGE_LOCKED = 1;

const long INITIAL_BATTERY_IMAGE = BATTERY_IMAGE_100;

const long NUM_STALL_IMAGES = 2;
const long NOT_STALLED_IMAGE = 0;
const long STALLED_IMAGE = 1;

class QLabel;
class MainWindow;

class RobotPanel : public QFrame{

    //Q_OBJECT

 public:
    RobotPanel(MainWindow * pMainWindow, long id);
    ~RobotPanel();

    void SetName(char * name);
    void SetPose(float x, float y, float orientation);
    void SetProgress(long progress, long maxProgress);
    void SetBatteryLevel(long level);
    void SetStalled(bool stalled);
    void SetLocked(bool locked);

 protected:
    void mousePressEvent(QMouseEvent * event);
    void mouseReleaseEvent(QMouseEvent * event);
    void mouseDoubleClickEvent(QMouseEvent * event);

 private:
    MainWindow * m_pMainWindow;
    long m_lId;
    bool m_bClicked;
    QLabel * m_pRobotName;
    QLabel * m_pLocation;
    QLabel * m_pOrientation;
    QLabel * m_pTaskProgressLabel;
    QProgressBar * m_pTaskProgress;
    QLabel * m_pBatteryIcon;
    QLabel * m_pLockIcon;
    
    QLabel * m_pStallLabel;
    QPixmap m_StallImages[NUM_STALL_IMAGES];

    QPixmap m_BatteryImages[NUM_BATTERY_IMAGES];
    QPixmap m_LockImages[NUM_LOCK_IMAGES];
};

#endif
