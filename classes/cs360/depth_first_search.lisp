;;; DFS.LISP
;;; depth-first search algorithm
;;; CS260 Fall 2007


;;; DEPTH-FIRST-SEARCH function performs a depth-first search.
;;; It maintains two lists OPEN and CLOSED, for more details about the algorith
;;; refer to the following:
;;; 1. Put the start node on a list OPEN and associate a null pointer with the node.
;;; 2. If OPEN is empty, output "FAILURE" and stop.
;;; 3. Select the first node on OPEN and call it N. Delete it from OPEN and put it
;;;    on a list CLOSED. If N is a goal node, output the list obtained by following
;;;    the chain of pointers beginning with the pointer associated with N.
;;; 4. Generate the list L of successors of N and delete from L those nodes already
;;;    appearing on list CLOSED.
;;; 5. Delete any members of OPEN which occur on L. Concatenate L onto the **FRONT**
;;;    of OPEN, and for each node in L associate a pointer to N.
;;; 6. Go to step 2.

;;; NOTE: Before calling this function, you should load the lisp source file which
;;; sets the map graph topology. Also, you should set the variable counter 
;;; OPEN-COUNT to 0.

(defun depth-first-search (start-node goal-node)
  (let ((open (list start-node))          ; *step1*
        (closed nil)
        n l)
    (setf (get start-node 'pointer) nil)
    (loop
     (if (null open) (return 'failure))  ; *step2*
     (setq n (car open))                 ; *step3*
     (incf open-count)                   ; OPEN-COUNT should be set to 0 before calling this function
     (setq open (cdr open))
     (setq closed (cons n closed))
     (print "CLOSED List: ")
     (print closed)
     (if (eq n goal-node) (return (print (extract-path n))))
     (setq l (get-successors n))         ; *step4*
     (setq l (set-difference l closed))
     (setq open (append l (set-difference open l))) ; *step5*
     (print "OPEN List: ")
     (print open)
     (dolist (x l)
       (setf (get x 'pointer) n))
     
                                        ; end of loop                       ; this is implicitly *step6*
     )))

;;; The helping function EXTRACT-PATH follows the pointers to produce a list of nodes
;;; on the path found.
(defun extract-path (n)
  (cond ((null n) nil)
        (t (append (extract-path (get n 'pointer))
                   (list n)))))

;;; The helping function GET-SUCCESSORS gets the cities adjacent to node N
(defun get-successors (n)
  (get n 'adjdst))
