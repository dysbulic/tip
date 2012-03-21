; CS-360 - Artificial Intelligence
; Vanderbilt University
; Fall 2007
; Will Holcomb <wholcomb@gmail.com>
;
; GPS - The General Purpose Solver
; 
; GPS was developed by Alan Newell and Herb Simon in 1957. Its modus
; operandi is means-end analysis. At any given step the program
; searches an action that will provide for an unsatisfied goal.

;; Enables more in-depth logging messages
(defvar *debug-logging* nil)

(defstruct op "An operation in the planning process"
  (action nil)    ; the name of the action
  (preconds nil)  ; a list of preconditions necessary for performing the action
  (add-list nil)  ; a set of conditions added by the action
  (del-list nil)) ; a set of conditions removed by the action

(defun member-equal (item list)
  "Tests if item is a member of list using the 'equal' function."
  (member item list :test #'equal))

(if (not (fboundp 'complement))
    (defun complement (fn)
      "If f(x) returns (y), then (complement f(x)) returns (not y)."
      #'(lambda (&reset args) (not (apply fn args)))))

(defun find-all (item sequence &rest keyword-args &key (test #'eql))
  "Non-destructively find all elements of sequence that match item, according to the keywords."
  (apply #'remove item sequence :test (complement test) keyword-args))

(defun print-message (format &rest args)
  "Prints a debug message if debug-logging is on."
  (if (not (null *debug-logging*)) (apply #'format (append '(t) (list format) args))))

; achieve-all is a regression planner that takes the unsatisfied goals
; and works backward using a depth-first search. At any given time the
; algorithm is attempting to satisfy a single set of goals. Initially
; these are the specified goals, but if a given subgoal has
; unspecified prerequisites then those goals will be added to the
; goals being examined. Seach down the tree is limited by only
; allowing a given state to be visited once.
;
; An example of a limitation this places on plans is present in the
; tests. A monkey can only hold one thing at once. He wants to eat, so
; he puts down the ball he is holding. He can never pick up the ball
; again because the planner will not reenter the situation where his
; hands are empty.
;
; This implementation differs slightly from the algorithm described in
; the handout. The algorithm described in class uses recuion to
; describe the application of an operator. Returning from the
; recursion however does not necessarily mean that that operation has
; been undone (the return from achieve to achieve-all). A test for
; feasability is done when the elements are combined. This allows for
; the satisfaction of a later goal to overwrite the state of a
; previous one. This implementation only returns from recursion when
; an operator is undone. It is always, therefore, possible to get from
; the current state to the goal by tracing the path to the root.
;
; The path to the root is maintained in a separate global variable and
; a sequence does not return as satisfying a goal unless it is
; verified as valid at the leaf. This should, I think, remove the
; possibility of goals trampling the satisfaction of later goals.

(defvar *current-path*)
(defvar *start-state*)
(defvar *final-goal*)

(defun achieve-all (state goals &optional (ops *ops*) (attempted-goals ()))
  "Achieve each goal in turn, and make sure they still hold at the end."
         ;; If at a solution, return true
  (cond ((current-procedure-is-valid goals state) t)
        (t
         ;; Already attempted goals are removed from consideration
         (let ((available-goals (set-difference goals attempted-goals)))
           ;; Unsatisfied goals are those whose preconditions are not in the present state.
           (let ((unsatisfied-goals (set-difference available-goals state)))
             (print-message "~%Unsatisfied: ~S + ~S~%" unsatisfied-goals (intersection goals attempted-goals))
             ;; Find an action to satisfy a particular goal
             (some #'(lambda (goal)
                       ;; The operators that might potentially be executed add an unsatisfied goal to the state
                       (let ((possible-ops (find-all goal ops :test #'appropriate-p)))
                         ;; Find an operation which leads to a solution path
                         (some #'(lambda (op) 
                                   ;; Modify the stored path
                                   (setq *current-path* (cons op *current-path*))
                                   (print-message "Applying: ~S/~S for ~S~%" (op-action op) (map 'list #'op-action possible-ops) goal)
                                   (print-message "Current: ~S~%" (map 'list #'op-action *current-path*))
                                   (let ((success (achieve-all state
                                                               (union (op-preconds op) (set-difference goals (op-add-list op)))
                                                               ops
                                                               (cons goal attempted-goals))))
                                     (if (not success) (setq *current-path* (cdr *current-path*)))
                                     success))
                               possible-ops)))
                       unsatisfied-goals))))))

(defun current-procedure-is-valid (&optional goals state)
  "Checks if the current procedure is valid."
        ;; If all the current goals aren't met then this isn't possibly a solution
  (cond ((not (subsetp goals state)) nil)
        ;; Otherwise, run through the current path and check its validity
        (t (let ((state *start-state*))
             (if (every #'(lambda (op) (setq state (apply-op op state))) *current-path*)
                 (subsetp *final-goal* state))))))

(defun apply-op (op state)
  "Returns the state produced by the application of op."
  (cond ((subsetp (op-preconds op) state) (union (set-difference state (op-del-list op)) (op-add-list op)))
        (t nil)))

(defun appropriate-p (goal op)
  "An op is appropriate to a goal if it is in its add list."
  (member-equal goal (op-add-list op)))

(defun GPS (state goals &optional ops)
  "General Problem Solver: from state, achieve goals using ops.
   Returns the list of operations performed or true if the goal is already satisfied."
  (setq *start-state* state)
  (setq *final-goal* goals)
  (setq *current-path* ())
  (cond ((current-procedure-is-valid goals state) t)
        ((achieve-all state goals ops) *current-path*)
        (t nil)))
