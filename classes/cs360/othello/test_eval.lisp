(load "TEAM11DEL2.lisp")
(load "Tournament.lisp")
(load "board_evaluation.lisp")

(setq player black)
(setq board (initial-board))

(defun move (row col)
  (make-move (+ (* row 10) col) player board)
  (print-board board)
  (format t "~%")
  (format t "Board Score: <~S/~S>" (TEAM-11-Evaluate black board) (TEAM-11-Evaluate white board))
  (setq player (opponent player)))

(move 4 3)
(move 3 3)
(move 3 4)
(move 3 5)

(stable-positions board)
