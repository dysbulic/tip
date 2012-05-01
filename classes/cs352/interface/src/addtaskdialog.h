#ifndef __ADD_TASK_DIALOG__
#define __ADD_TASK_DIALOG__

#include <QDialog>
#include <QPushButton>
#include <QLabel>
#include <QComboBox>
#include <QListWidget>

class SharedData;

class AddTaskDialog : public QDialog{
    Q_OBJECT

 public:
    AddTaskDialog(SharedData * pSharedData);
    ~AddTaskDialog();

    long GetSelectedRobot() {return(m_lSelectedRobot);}
    long GetPrecedingWaypointId();
    long GetSelectedTask() {return(m_lSelectedTask);}

 private Q_SLOTS:
    void UpdateSelectedTask(int index);
    void UpdateWaypointList(int index);
    void MoveUp();
    void MoveDown();

 private:
    SharedData * m_pSharedData;
    int m_iNewWaypointIndex;
    long m_lSelectedRobot;
    long m_lSelectedTask;

    QPushButton * m_pAddButton;
    QPushButton * m_pCancelButton;

    QLabel * m_pRobotLabel;
    QComboBox * m_pRobotList;
    QListWidget * m_pWaypointList;

    QPushButton * m_pUp;
    QPushButton * m_pDown;

    QLabel * m_pTaskLabel;
    QComboBox * m_pTaskList;
};

#endif
