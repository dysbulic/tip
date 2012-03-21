#ifndef __ROBOT_THREAD__
#define __ROBOT_THREAD__

#include <QThread>

#include "global.h"
#include "robot.h"
#include "shareddata.h"

const float ANGLE_DIFFERENCE_THRESHOLD = 0.2;


class RobotThread : public QThread{

 public:
    RobotThread();
    long Init(int id, QString name, char * address, long port, int configFlags, SharedData * pSharedData);

 protected: 
    void run();

 private:
    long m_lId;
    Robot m_Robot;
    QString m_Name;
    SharedData * m_pSharedData;
};

#endif
