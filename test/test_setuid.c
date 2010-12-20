/**
 * Not sure how to have these kernel space functions
 *  work in a userspace program. Would it be possible to
 *  have the progam in a kernel module?
 */

/**
 * This is a C program to test if it is possible to have
 *  a program that is suid root that a user can run and
 *  have it set the userid of the process that called it.
 * In liunx 2.4 there is no standard call for doing this
 *  straight out, so it takes getting a reference to the
 *  process in the process table and editing the
 *  information directly.
 * I have had to piece together how to do this by hunting
 *  through the kernel code and much googling. Not sure
 *  if it will work or if it will bring down the system.
 */

/**
 * Information about a process is stored in a structure
 *  of type task_struct. This structure is defined in
 *  linux/sched.h and the fields that are important are:
 *    task.uid -> the real user id
 *    task.euid -> the effective user id
 *    task.fsuid -> the filesystem user id
 *    task.suid -> the saved user id
 *    (and the corresponding group ids (gid)
 * The user ids are of type uid_t and the gids are gid_t
 *  and their definitions are in "linux/types.h"
 */
#ifdef LINUX
#include "linux/sched.h"
#endif

/**
 * The kill_proc function in kernel/signal.c is useful
 *  for seeing how to get access to a task_struct from
 *  a process id:
 *    kill_proc_info(int sig, struct siginfo *info,
 *                   pid_t pid) {
 *      int error;
 *      struct task_struct *p;
 *      read_lock(&tasklist_lock);
 *      p = find_task_by_pid(pid);
 *      error = -ESRCH;
 *      if(p)
 *        error = send_sig_info(sig, info, p);
 *      read_unlock(&tasklist_lock);
 *      return error;
 *    }
 * find_task_by_pid is also defined in sched.h as
 *  is tasklist_lock. ESRCH is defined in errno.h
 *  and means "No Such Process"
 */
#include "errno.h"

/**
 * The only part left really is getting the parent
 *  process id and there is a standard function for
 *  that in unistd.h: getppid()
 */
#include <unistd.h>
#include <stdio.h>

#ifdef LINUX
int main(void)
{
  struct task_struct *task;
  pid_t ppid = getppid();

  read_lock(&tasklist_lock);
  task = find_task_by_pid(ppid);
  if(!task)
    {
      fprintf(stderr, "No such process: %d\n", ppid);
      return -ESRCH;
    }
  task->suid = task->uid;
  task->uid = task->euid = task->fsuid = 549;
  read_unlock(&tasklist_lock);
  return 0;
}
#else
int main(void) { return -1; }
#endif
