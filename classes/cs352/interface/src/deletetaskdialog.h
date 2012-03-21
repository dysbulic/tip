#ifndef __DELETE_TASK_DIALOG__
#define __DELETE_TASK_DIALOG__

#include <QDialog>
#include <QLabel>
#include <QListWidget>
#include <QPushButton>

#include <vector>

class SharedData;

class DeleteTaskDialog : public QDialog{
    Q_OBJECT

 public:
    DeleteTaskDialog(SharedData * pSharedData, long robotId, std::vector<long> * pIdList);
    ~DeleteTaskDialog() {}
    
 protected:
    void PopulateTaskList(long robotId);
    
 private Q_SLOTS:
    void DeleteTask();

 private:
    SharedData * m_pSharedData;
    std::vector<long> * m_pIdList;

    QLabel * m_pRobotNameLabel;

    QListWidget * m_pWaypointList;
    QPushButton * m_pDeleteButton;

    QPushButton * m_pDoneButton;
    QPushButton * m_pCancelButton;
};

#endif
