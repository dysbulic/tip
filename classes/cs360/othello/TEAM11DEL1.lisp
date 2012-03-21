; Othello Deliverable #2
; CS360 - Artificial Intelligence
; Vanderbilt University
; Fall 2007
; Robert Halpenny and William Holcomb

; I pledge my honor that I have neither given nor received aid on this
; work beyond what is permitted by the instructor.

(defconstant all-directions '(-11 -10 -9 -1 1 9 10 11))

(defconstant empty 0 "An empty square")
(defconstant black 1 "A black piece")
(defconstant white 2 "A white piece")
(defconstant outer 3 "Marks squares outside the 8x8 board")

;;; defines piece to be a value between empty and outer.
(deftype piece () `(integer ,empty ,outer))

;;; when printed the board positions will be empty, @ = black, 0 = white, and 
;;; ? = outer side of board (which should never be printed).
(defun name-of (piece) (char ".@O?" piece))

(defun opponent (player) (if (eql player black) white black))

;;; the board definition
(deftype board () '(simple-array piece (100)))

;;; bref = board reference
(defun bref (board square) (aref board square))
(defsetf bref (board square) (val) 
  `(setf (aref ,board ,square) ,val))

(defun copy-board (board)
  (copy-seq board))

(defconstant all-squares
  (loop for i from 11 to 88 when (<= 1 (mod i 10) 8) collect i))

(defun initial-board ()
  "Return a board, empty except for four pieces in the middle."
  ;; Boards are 100-element vectors, with elements 11-88 used,
  ;; and the others marked with the sentinel OUTER.  Initially
  ;; the 4 center squares are taken, the others empty.
  (let ((board (make-array 100 :element-type 'piece
                           :initial-element outer)))
    (dolist (square all-squares)
      (setf (bref board square) empty))
    (setf (bref board 44) white   (bref board 45) black
          (bref board 54) black   (bref board 55) white)
    board))

;; Redefined in Tournament.lisp
;; (defun print-board (&optional (board *board*))
;;   "Print a board, along with some statistics."
;;   ;; First print the header and the current score
;;   (format t "~2&    a b c d e f g h ")
;;   ;; Print the board itself
;;   (loop for row from 1 to 8 do
;;         (format t "~&  ~d " row)
;;         (loop for col from 1 to 8
;;               for piece = (bref board (+ col (* 10 row)))
;;               do (format t "~c " (name-of piece))))
;;  )

;Checks if a move is legal, returns t or NIL
(defun TEAM-11-LEGAL-MOVE (move board player) 
  ;These are the bounds checks
 (if (< move 11) (return-from TEAM-11-LEGAL-MOVE NIL))
 (if (> move 88) (return-from TEAM-11-LEGAL-MOVE NIL))
 (if (= (mod move 10) 0) (return-from TEAM-11-LEGAL-MOVE NIL))
 (if (= (mod (- move 9) 10) 0) (return-from TEAM-11-LEGAL-MOVE NIL))
 ;Check if the square is empty
 (if (not(= (aref board move) 0)) (return-from TEAM-11-LEGAL-MOVE NIL))
 
 ;Set opposite check
 (if (= player 1) (setf opp 2) (setf opp 1))
 
 ;check for flips
 (if (and (= (aref board (+ move -1)) opp) (TEAM-11-checkFlip (+ move -2) player board -1)) (return-from TEAM-11-LEGAL-MOVE t))
 ;(print 'W0071);
 (if (and (= (aref board (+ move -11)) opp) (TEAM-11-checkFlip (+ move -22) player board -11)) (return-from TEAM-11-LEGAL-MOVE t))
 ;(print 'W0072); 
 (if (and (= (aref board (+ move -10)) opp) (TEAM-11-checkFlip (+ move -20) player board -10)) (return-from TEAM-11-LEGAL-MOVE t))
 ;(print 'W0073);
 (if (and (= (aref board (+ move -9)) opp) (TEAM-11-checkFlip (+ move -18) player board -9)) (return-from TEAM-11-LEGAL-MOVE t))
 ;(print 'W0074);
 (if (and (= (aref board (+ move 1)) opp) (TEAM-11-checkFlip (+ move 2) player board 1)) (return-from TEAM-11-LEGAL-MOVE t))
 ;(print 'W0075);
 (if (and (= (aref board (+ move 11)) opp) (TEAM-11-checkFlip (+ move 22) player board 11)) (return-from TEAM-11-LEGAL-MOVE t))
 ;(print 'W0076);
 (if (and (= (aref board (+ move 10)) opp) (TEAM-11-checkFlip (+ move 20) player board 10)) (return-from TEAM-11-LEGAL-MOVE t))
 ;(print 'W0077);
 (if (and (= (aref board (+ move 9)) opp) (TEAM-11-checkFlip (+ move 18) player board 9)) (return-from TEAM-11-LEGAL-MOVE t))

 NIL
 

)

;Checks if a piece can be flipped 
(defun TEAM-11-checkFlip (start player board inc)
;  (print 'checkflip)
  (if (or (= (aref board start) empty) (= (aref board start) outer)) (return-from TEAM-11-checkFlip NIL))
  (if (= (aref board start) player) t (TEAM-11-checkFlip (+ start inc) player board inc))
)

;Makes the move "move" if it is legal, otherwise it returns NIL
(defun TEAM-11-makeMove (move board player)
  (if (not (TEAM-11-LEGAL-MOVE move board player)) (return-from TEAM-11-makeMove NIL))
  

					;Set opposite check
  (if (= player 1) (setf opp 2) (setf opp 1))

  (let ((nboard (TEAM-11-mCopyArray board)))

    (if (= (aref board (+ move -1)) opp) (TEAM-11-tryFlip board nboard (+ move -1) player opp -1))
  ;  (print 'W007a);
    (if (= (aref board (+ move -11)) opp) (TEAM-11-tryFlip board nboard (+ move -11) player opp -11))
   ; (print 'W007b); 
    (if (= (aref board (+ move -10)) opp) (TEAM-11-tryFlip board nboard (+ move -10) player opp -10))
    ;(print 'W007c);
    (if (= (aref board (+ move -9)) opp) (TEAM-11-tryFlip board nboard (+ move -9) player opp -9))
    ;(print 'W007d);
    (if (= (aref board (+ move 1)) opp) (TEAM-11-tryFlip board nboard (+ move 1) player opp 1))
    ;(print 'W007e);
    (if (= (aref board (+ move 11)) opp) (TEAM-11-tryFlip board nboard (+ move 11) player opp 11))
    ;(print 'W007f);
    (if (= (aref board (+ move 10)) opp) (TEAM-11-tryFlip board nboard (+ move 10) player opp 10))
    ;(print 'W007g);
    (if (= (aref board (+ move 9)) opp) (TEAM-11-tryFlip board nboard (+ move 9) player opp 9))
    ;(print 'W007h)
    (setf (aref nboard move) player)
    (return-from TEAM-11-makeMove nboard)
  )
)

;Tries to flip a piece along a row, column or diagonal
(defun TEAM-11-tryFlip (board nboard move player opp inc)
  ;(print move)
  ;(princ 'tryFlip)
  (if (or (= (aref board move) empty) (= (aref board move) outer)) (return-from TEAM-11-tryFlip NIL))
  (when (= (aref board move) opp)
    (when (TEAM-11-tryFlip board nboard (+ move inc) player opp inc)
      (setf (aref nboard move) player)
      ;(print-board nboard)
      (return-from TEAM-11-tryFlip t)
      )
    )
  (when (= (aref board move) player) (return-from TEAM-11-tryFlip t))
)

;My copy array function for generating new boards as we head down the search tree
(defun TEAM-11-mCopyArray (array)
  (let ((narray (make-array 100 :initial-element empty)))
    (dotimes (n 100 narray)
      (setf (aref narray n) (aref array n))
      )
    )
)
 

;Returns 0 if there are no legal moves, otherwise a list of all legal moves
(defun TEAM-11-genLegal (board player)
  (let ((lmoves '(0)))
    (do ((n 0 (+ n 1)))
	((= n 100) lmoves)
      (when (TEAM-11-LEGAL-MOVE n board player) 
	;(print 'legal)
	(if (equal (car lmoves) 0) (setf lmoves (list n)) (setf lmoves (cons n lmoves)))
	)
      ;(print n)
      ;(print 'nextMove)
      )
    (return-from TEAM-11-genLegal lmoves)
    )
  )
   
;Test function, broken because of variable scoping     
(defun TEAM-11-test (move board player)

  (setf board2 (TEAM-11-makeMove move board player))
  (if (equal NIL board2) (print NIL) (print-board board2))
  ;(if (not(equal NIL board2)) (setf board board2))
  (setf board board2)
  (if (= player 1) (setf opp 2) (setf opp 1))
  (print 'MOVES)
  (print (TEAM-11-genLegal board opp))
)
