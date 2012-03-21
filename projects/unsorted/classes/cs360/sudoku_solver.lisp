; Program to solve sudoku problems.
; Author: Will Holcomb <wholcomb@gmail.com>
;         Morgan Halpenny <theatomicbob@gmail.com>
; Date: 1 October 2007
;
; Sudoku is a game played in a 9x9 grid where the goal is to place the
; numbers 1-9 such that no number is repeated on any column, row, or
; 3x3 subdivision. The 3x3 subdivisions divide the 9x9 grid into a 3x3
; grid of 3x3 subgrids.

;; Loading dependency from Morgan
(load 'solve_list.lisp)

;; A sudoku state space is a complete solution if each cell has
;; exactly one value
(defun is-complete (state-space) (= 81 (count-atoms state-space)))

;; Ranks the available spaces in order of their size from smallest to
;; largest. Items with a cardinality of 1 are not considered
(defun cardinality-rank (lists-list)
  (let ((cardinalities ()))
    (dotimes (list-index (length lists-list))
      (let ((len (length (car (nthcdr list-index lists-list)))))
        (if (< 1 len)
            (push (cons list-index len) cardinalities))))
    (sort cardinalities #'< :key #'cdr)
    (mapcar #'(lambda (card) (car card)) cardinalities)))

;; Examines the potential constraints on a particular space and orders
;; the potential numbers by the number of atoms in each child
(defun atom-count-rank (state-space space-index)
  (let ((counts ())
        (cell-space (car (nthcdr space-index state-space))))
    (dolist (num cell-space)
      (let ((len (count-atoms (constrain state-space space-index num))))
        (if (not (null len))
            (push (cons num len) counts))))
    (sort counts #'> :key #'cdr)
    (mapcar #'(lambda (count) (car count)) counts)))

(if (not (fboundp 'count-atoms))
    ;; Counts the total number of atoms. Nil is not counted
    (defun count-atoms (list)
      (let ((count 0))
        (cond ((listp list) (dolist (x list) (setq count (+ count (count-atoms x)))))
              (t (setq count 1)))
        count)))

(defun print-board (space)
  (let ((count 0))
    (dolist (val space)
      (setq count (1+ count))
      (cond ((> (length val) 1) (format t "<~S>" (length val)))
            ((< (length val) 1) (format t " x "))
            (t                  (format t " ~S " (car val))))
      (if (= 0 (mod count 9)) (format t "~%" ())))))

;; The state space is a list of 81 lists, each element is a list of
;; the permissible numbers for that cell. If a list is ever empty the
;; space is invalid.
;;
;; An optional puzzle definition may be specified that allows
;; prepopulating the constrained cells.
(defun generate-state-space (&optional puzzle)
  (let ((space ()))
    (cond ((null puzzle)
           (dotimes (i 81)
             (push '(1 2 3 4 5 6 7 8 9) space)))
          (t
           (dolist (cell puzzle)
             (push (if (zerop cell) '(1 2 3 4 5 6 7 8 9) (list cell))
                   space))))
    (reducePuzzle (reverse space))))

(defun solve-sudoku (problem)
  (let ((state-space (generate-state-space problem)))
    (format t "Initial Problem Space:~%")
    (print-board state-space)
    (setq solution (find-sudoku-solution state-space))
    (format t "Solution:~%")
    (print-board solution)
    solution))

(defun find-sudoku-solution (state-space)
  (if (is-complete state-space)
      state-space
    ;; First determine the order the cells will be tried
    (let ((cell-order (cardinality-rank state-space)))
      (dolist (cell-index cell-order)
        ;; Next determine the order particular numbers will be tried
        (let ((num-order (atom-count-rank state-space cell-index)))
          (dolist (num num-order)
            ;; Check each potential number to see if it leads to a solution
            (let ((constrained-space (constrain state-space cell-index num)))
              (format t "Attempting Constraint: <~S,~S> to ~S~%"
                      (1+ (floor (/ cell-index 9.0))) (mod cell-index 9) num)
              (print-board constrained-space)
              (setq solution (find-sudoku-solution constrained-space))
              ;; find-sudoku-solution only returns valid solutions
              (if (not (null solution)) (return-from find-sudoku-solution solution))))))
      nil)))


(format t "Easy Sudoku:~%")
(solve-sudoku (list 0 0 3 0 2 1 0 6 7
                    4 6 2 5 0 0 1 0 0
                    0 0 0 0 6 0 0 0 5
                    0 7 0 0 1 5 0 0 2
                    1 0 0 0 0 0 0 0 9
                    8 0 0 7 9 0 0 4 0
                    2 0 0 0 4 0 0 0 0
                    0 0 6 0 0 7 2 1 4
                    9 4 0 6 3 0 7 0 0))

(format t "~%Very Hard Sudoku:~%")
(solve-sudoku (list 1 0 0 0 2 0 0 0 4
                    0 6 0 0 0 5 8 3 0
                    0 7 0 9 8 0 0 0 0
                    0 5 0 0 0 0 9 0 0
                    4 0 3 0 0 0 1 0 8
                    0 0 6 0 0 0 0 5 0
                    0 0 0 0 4 3 0 2 0
                    0 3 7 5 0 0 0 8 0
                    5 0 0 0 6 0 0 0 7))

(format t "~%Very Hard Sudoku:~%")
(solve-sudoku (list 0 4 0 5 2 3 0 6 0
                    8 0 0 0 0 0 0 0 7
                    0 0 5 0 0 0 4 0 0
                    1 9 0 6 0 5 0 8 3
                    0 0 0 0 7 0 0 0 0
                    6 8 0 9 0 2 0 7 5
                    0 0 3 0 0 0 7 0 0
                    5 0 0 0 0 0 0 0 4
                    0 7 0 2 9 1 0 5 0))

(format t "~%Indeterminate Sudoku:~%")
(solve-sudoku (list 0 0 3 0 2 1 0 6 7
                    4 6 2 5 0 0 1 0 0
                    0 0 0 0 0 0 0 0 5
                    0 0 0 0 1 5 0 0 2
                    1 0 0 0 0 0 0 0 9
                    8 0 0 0 9 0 0 4 0
                    2 0 0 0 4 0 0 0 0
                    0 0 6 0 0 7 2 1 4
                    9 4 0 6 3 0 0 0 0))
