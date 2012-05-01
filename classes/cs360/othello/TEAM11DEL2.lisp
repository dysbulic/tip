; Othello Deliverable #2
; CS360 - Artificial Intelligence
; Vanderbilt University
; Fall 2007
; Robert Halpenny and William Holcomb

; I pledge my honor that I have neither given nor received aid on this
; work beyond what is permitted by the instructor.
(setf *load-compiling* t)

(defconstant all-directions '(-11 -10 -9 -1 1 9 10 11))

; Constants used in identifying the nature of squares on the board
(defconstant empty 0 "An empty square")
(defconstant black 1 "A black piece")
(defconstant white 2 "A white piece")
(defconstant outer 3 "A square outside the 8x8 board")

; Constraints on the board exaluation function
(defconstant MINVALUE -1000000)
(defconstant MAXVALUE 1000000)

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
  "Returns a copy of the board"
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
    ; set the starting piece positions
    (setf (bref board 44) white   (bref board 45) black
          (bref board 54) black   (bref board 55) white)
    board))

(defun TEAM-11-mCopyArray (array)
  "Copy array function for generating new boards"
  (let ((narray (make-array 100 :initial-element empty)))
    (dotimes (n 100 narray)
      (setf (aref narray n) (aref array n))
      )
    )
  )
 
(defun TEAM-11-initArrays ()
  "Sets all board positions to empty"
  (let ((l NIL))
    (dotimes (n 13)
      (setf l (cons (list (make-array 100 :initial-element empty)) l))
      )
    l)
  )

(defun TEAM-11-search (board depth alpha beta player)
  "Performs a depth-limited min-max search with alpha-beta pruning"
  (let ((rplayer (opponent player)))
    (if (TEAM-11-full board) (return-from TEAM-11-search (TEAM-11-didWin rplayer board)))
    (if (TEAM-11-full board) (return-from TEAM-11-search (TEAM-11-didWin rplayer board)))
    ;(if  (>= depth maxDepth) (return-from TEAM-11-search (TEAM-11-Evaluate2 player board)))
    (if  (and (>= depth maxDepth) (= rplayer black)) (return-from TEAM-11-search (TEAM-11-Evaluate2 player board)))
    (if  (and (>= depth maxDepth) (= rplayer white)) (return-from TEAM-11-search (TEAM-11-Evaluate3 player board)))
    (let ((lmoves (TEAM-11-genLegal board player))
          (score 0)
          (value 0)
          (n 0))
      (when (eq lmoves NIL)
        (if DEAD (return-from TEAM-11-search (TEAM-11-didWin rplayer board)))
        (setf DEAD t)
        (return-from TEAM-11-search (TEAM-11-search board depth (* -1 beta) (* -1 alpha) (opponent player)))
        )
      (setf DEAD NIL)
      (setf n (length lmoves))
      (setf lmoves (TEAM-11-orderMoves lmoves board player (car (nth depth sarrays)))) 
      (setf score (* -1 (TEAM-11-search (make-move  (pop lmoves) player (TEAM-11-copyIn board (car (nth depth sarrays)))) (+ depth 1) (* -1 beta) (* -1 alpha) (opponent player))))
      ;(pop lmoves)
      (dolist (m lmoves score)
        (if (>= score beta) (return-from TEAM-11-search score))
        (if (> score alpha) (setf alpha score))
        (setf value (* -1 (TEAM-11-search (make-move  m player (TEAM-11-copyIn board (car (nth depth sarrays)))) (+ depth 1) (- (* -1 alpha) 1) (* -1 alpha) (opponent player))))
        (when (> value score)
          (if (and (< alpha value) (< value beta))
              (setf score (* -1 (TEAM-11-search (make-move  m player (TEAM-11-copyIn board (car (nth depth sarrays)))) (+ depth 1) (* -1 beta) (* -1 value) (opponent player))))
            (setf score value)
            )
          )
        )
      )
    )
  )

(defun TEAM-11-search2 (board depth alpha beta player)
  "Don't know what this function does"
  (if (TEAM-11-full board) (return-from TEAM-11-search2 (TEAM-11-didWin rplayer board)))
  ;(if  (and (>= depth maxDepth) (= player black)) (return-from TEAM-11-search2 (TEAM-11-Evaluate2 player board)))
  ;(if  (and (>= depth maxDepth) (= player white)) (return-from TEAM-11-search2 (TEAM-11-Evaluate3 player board)))
  (if  (>= depth maxDepth) (return-from TEAM-11-search2 (TEAM-11-Evaluate2 player board)))
  (let ((lmoves (TEAM-11-genLegal board player))
	(score MINVALUE)
	(value 0)
	(n 0))
    (when (eq lmoves NIL)
      (if DEAD (return-from TEAM-11-search2 (TEAM-11-didWin rplayer board)))
      (setf DEAD t)
      (return-from TEAM-11-search2 (TEAM-11-search2 board depth (* -1 beta) (* -1 alpha) (opponent player)))
      )
    (setf DEAD NIL)
    (setf n (length lmoves))
    (setf lmoves (TEAM-11-orderMoves lmoves board player (car (nth depth sarrays)))) 
    ;(setf score MINVALUE)
    ;(pop lmoves)
    (dolist (m lmoves score)
      (setf value (* -1 (TEAM-11-search2 (make-move m player (TEAM-11-copyIn board (car (nth depth sarrays)))) (+ depth 1) (* -1 beta) (* -1 (max alpha score)) (opponent player))))
      (if (> value score) (setf score value))
      (if (>= score beta) (return-from TEAM-11-search2 score))
      )
    score)
  )

(defun TEAM-11-full (board)
  "Returns true if a board is full or NIL otherwise"
  (dotimes (i 100)
    (if (= (aref board i) empty) NIL))
  t)

(defun TEAM-11-didWin (rplayer board)
  (if (> (count-difference rplayer board) 0) (return-from TEAM-11-didWin MAXVALUE))
  (if (= (count-difference rplayer board) 0) (return-from TEAM-11-didWin 0))
  MINVALUE
  )

(defun TEAM-11-GO (player board)
  "Make a move for the given player on the given board"
  (let ((legal-moves NIL)
	(bm 0)
	(obm 0)
	(t1 0)
	(t2 0)
	(beta MAXVALUE)
	(alpha MINVALUE)
	(score 0)
	(value 0)
	(otime 0)
	(lmoves2 NIL))
    (defvar rplayer)
    (setf rplayer player)
    (defvar maxDepth)
    ;(setf maxDepth (TEAM-11-getDepth player board))
    (defvar DEAD)
    (setf DEAD NIL)
    (defvar nodes)
    (setf nodes 0)
    (defvar sarrays)
    (setf sarrays (TEAM-11-initArrays))
    (defvar rtime)
    (if (= rplayer black) (setf rtime (aref *clock* 1)) (setf rtime (aref *clock* 2)))
    (setf rtime (/ rtime internal-time-units-per-second))
    (print 'time)
    (print (floor rtime))
    (setf t1 (get-internal-real-time))
    (setf t2 t1)
    (setf otime t1)
    (setf legal-moves (TEAM-11-genLegal board player))
    (if (= (length lmoves) 1) (return-from TEAM-11-GO (car lmoves)))
    (setf lmoves (TEAM-11-orderMoves lmoves board player (car (nth 0 sarrays))))
    
    (setf lmoves2 lmoves)
    (dotimes (n 12)
      (setf alpha MINVALUE)
      (setf lmoves lmoves2)
      (setf score MINVALUE)
      (if (= n 0) (setf n 1))
      (if (and (< *move-number* 8) (> n 4)) (return-from TEAM-11-GO bm))
      (setf obm bm)  ;stash the old best move if we run out of time
      (if (and (> 10 rtime) (> n 4)) (print 'EMERGENCY))
      (if (and (> 10 rtime) (> n 4)) (return-from TEAM-11-GO bm))
      (if (> (/ (- t2 t1) internal-time-units-per-second) 3.5) (return-from TEAM-11-GO bm))
      ;(if (> (/ (- t2 t1) internal-time-units-per-second) 2) (return-from TEAM-11-GO bm))
      (setf t1 t2)
      (setf maxDepth n)
      (print n)
      (setf bm (car lmoves))
      

      (setf score (* -1 (TEAM-11-search (make-move  (car lmoves) player (TEAM-11-copyIn board (car( nth 0 sarrays)))) 1 (* -1 beta) (* -1 alpha) (opponent player))))
      (pop lmoves)
      
      (dolist (m lmoves)
	;(print 'move)
	;(princ m)
	(if (and (> 10 (- rtime (/ (- (get-internal-real-time) otime) internal-time-units-per-second))) (> n 4)) (return-from TEAM-11-GO obm))
	(setf DEAD NIL)
	
	(when (not (>= score beta))
	  (setf alpha (max score alpha))
	  (if (> score alpha) (setf alpha score))
	  (setf value (* -1 (TEAM-11-search (make-move  m player (TEAM-11-copyIn board (car (nth 0 sarrays))))  1 (- (* -1 alpha) 1) (* -1 alpha) (opponent player))))
	  (when (> value score)
	    (if (and (< alpha value) (< value beta)) (progn 
						       (setf score (* -1 (TEAM-11-search (make-move  m player (TEAM-11-copyIn board (car (nth 0 sarrays)))) 1 (* -1 beta) (* -1 value) (opponent player))))
						       (setf bm m))
	      (progn (setf score value) (setf bm m))
	      )
	    )
	  )
	)
      
      (setf t2 (get-internal-real-time))
      (print 'nodes)
      (print nodes)
      ;(print 'time)
      ;(print (round (/ (- t2 t1) internal-time-units-per-second)))
      ;(print (/ (- t2 t1) internal-time-units-per-second))
      
      )

  
    bm
    )
  )

(defun TEAM-11-GO2 (player board)
  "Make a move for the given player on the given board"
  (let (
	(lmoves NIL)
	(bm 0)
	(obm 0)
	(t1 0)
	(t2 0)
	(beta MAXVALUE)
	(alpha MINVALUE)
	(score MINVALUE)
	(value 0)
	(otime 0)
	;(vals '(0))
	;(bv MINVALUE)
	)
    (defvar rplayer)
    (setf rplayer player)
    (defvar maxDepth)
    ;(setf maxDepth (TEAM-11-getDepth player board))
    (defvar DEAD)
    (setf DEAD NIL)
    (defvar nodes)
    (setf nodes 0)
    (defvar sarrays)
    (setf sarrays (TEAM-11-initArrays))
    (defvar rtime)
    (if (= rplayer black) (setf rtime (aref *clock* 1)) (setf rtime (aref *clock* 2)))
    (setf rtime (/ rtime internal-time-units-per-second))
    (print 'time)
    (print (floor rtime))
    (setf t1 (get-internal-real-time))
    (setf t2 t1)
    (setf otime t1)
    (setf lmoves (TEAM-11-genLegal board player))
    (if (= (length lmoves) 1) (return-from TEAM-11-GO2 (car lmoves)))
    (setf lmoves (TEAM-11-orderMoves lmoves board player (car (nth 0 sarrays)))) 
    (dotimes (n 12)
      (setf score MINVALUE)
      (if (= n 0) (setf n 1))
      (setf obm bm)  ;stash the old best move if we run out of time
      (if (and (> 10 rtime) (> n 4)) (print 'EMERGENCY))
      (if (and (> 10 rtime) (> n 4)) (return-from TEAM-11-GO2 bm))
      (if (> (/ (- t2 t1) internal-time-units-per-second) 4) (return-from TEAM-11-GO2 bm))
      ;(if (> (/ (- t2 t1) internal-time-units-per-second) 2) (return-from TEAM-11-GO2 bm))
      (setf t1 t2)
      (setf maxDepth n)
      ;(print n)
      (setf bm (car lmoves))
      (dolist (m lmoves)
	
	(if (and (> 10 (- rtime (/ (- (get-internal-real-time) otime) internal-time-units-per-second))) (> n 4)) (return-from TEAM-11-GO2 obm))
	(setf DEAD NIL)
	;(print m)
	
	(setf value (* -1 (TEAM-11-search2 (make-move m player (TEAM-11-copyIn board (car (nth 0 sarrays)))) 1 (* -1 beta) (* -1 (max score alpha)) (opponent player))))
	;(print value)
	(if (> value score) (progn (setf score value) (setf bm m)))
	
	  
	)
      
      (setf t2 (get-internal-real-time))
      (print 'nodes)
      (print nodes)
            
      )

  
    bm
    )
  )


(defconstant TEAM-11-Corners '(11 18 81 88))
(defconstant TEAM-11-C-Cells '(21 12 17 28 78 87 82 71))
(defconstant TEAM-11-X-Cells '(22 27 77 72))
(defconstant CORNER 50)
(defconstant CCELL 8)
(defconstant XCELL 16)
(defconstant FRONTIER -1)
(defconstant NOTFRONTIER 1)
(defconstant EDGE 8)
(defconstant AX 2)


(defconstant TEAM-11-C1X 22)
(defconstant TEAM-11-C2X 27)
(defconstant TEAM-11-C3X 72)
(defconstant TEAM-11-C4X 77)
(defconstant TEAM-11-C1C '(12 21))
(defconstant TEAM-11-C2C '(17 28))
(defconstant TEAM-11-C3C '(71 82))
(defconstant TEAM-11-C4C '(78 87))
(defconstant TEAM-11-C1 11)
(defconstant TEAM-11-C2 18)
(defconstant TEAM-11-C3 81)
(defconstant TEAM-11-C4 88)
(defconstant TEAM-11-EDGES '(13 14 15 16 31 41 51 61 38 48 58 68 83 84 85 86))
(defconstant TEAM-11-AX '(23 32 26 37 62 73 67 76))
(defconstant TEAM-11-AX1 '(23 32))
(defconstant TEAM-11-AX2 '(26 37))
(defconstant TEAM-11-AX3 '(62 73))
(defconstant TEAM-11-AX4 '(67 76))


(defun TEAM-11-Evaluate2 (player board)
  "Evaluates the strength of a given board for a given player"
  ;(print-board board)
  (setf nodes (+ nodes 1))
  (let ((score 0))
    (loop for row from 1 to 8 do
      (loop for col from 1 to 8 do
	(let ((index (+ (* row 10) col)))
	  (let (
		(cellOwner (bref board index))
		;(play (opponent player))
		(play rplayer)
		)
	    (if  (not (= cellOwner empty))
		;(print index)
		(let ((weight (cond 
			       ((member index TEAM-11-Corners) CORNER)
			       ((= index TEAM-11-C1X) (if(= (aref board TEAM-11-C1) (aref board index)) XCELL (* -1 XCELL))) 
			       ((= index TEAM-11-C2X) (if(= (aref board TEAM-11-C2) (aref board index)) XCELL (* -1 XCELL))) 
			       ((= index TEAM-11-C3X) (if(= (aref board TEAM-11-C3) (aref board index)) XCELL (* -1 XCELL))) 
			       ((= index TEAM-11-C4X) (if(= (aref board TEAM-11-C4) (aref board index)) XCELL (* -1 XCELL))) 
			       ((member index TEAM-11-C1C) (if(= (aref board TEAM-11-C1) (aref board index)) CCELL (* -1 CCELL))) 
			       ((member index TEAM-11-C2C) (if(= (aref board TEAM-11-C2) (aref board index)) CCELL (* -1 CCELL))) 
			       ((member index TEAM-11-C3C) (if(= (aref board TEAM-11-C3) (aref board index)) CCELL (* -1 CCELL))) 
			       ((member index TEAM-11-C4C) (if(= (aref board TEAM-11-C4) (aref board index)) CCELL (* -1 CCELL))) 
			       ((member index TEAM-11-EDGES) EDGE) 
			       
			       ((member index TEAM-11-AX1) (if(= (aref board TEAM-11-C1) (aref board index)) AX (* -1 AX))) 
			       ((member index TEAM-11-AX2) (if(= (aref board TEAM-11-C2) (aref board index)) AX (* -1 AX))) 
			       ((member index TEAM-11-AX3) (if(= (aref board TEAM-11-C3) (aref board index)) AX (* -1 AX))) 
			       ((member index TEAM-11-AX4) (if(= (aref board TEAM-11-C4) (aref board index)) AX (* -1 AX)))


			       (t (if (TEAM-11-frontier index board) FRONTIER NOTFRONTIER))
			       )
			      )
		      )
		  (if (not (= play cellOwner)) (setq weight (* weight -1)))
		  
		  ;;(format t "<~s,~s> (~c) [~s/~s]~%" row col (name-of cellOwner) weight score)
		  (setq score (+ score weight))
		  ;(print 'score)
		  ;(print score)
		  )
		)
	    )
	  )
	)
      )
    ;(progn (print 'score) (print score) (if (= player rplayer) (* -1 score) score))
    (if (= player rplayer) score (* -1 score))
    )
    
  )

(defun TEAM-11-Evaluate3 (player board)
  "Evaluates the strength of a given board for a given player"
  ;(print-board board)
  (setf nodes (+ nodes 1))
  (let ((score 0))
    (loop for row from 1 to 8 do
      (loop for col from 1 to 8 do
	(let ((index (+ (* row 10) col)))
	  (let (
		(cellOwner (bref board index))
		;(play (opponent player))
		(play rplayer)
		)
	    (if  (not (= cellOwner empty))
		;(print index)
		(let ((weight (cond 
			       ((member index TEAM-11-Corners) CORNER)
			       ((= index TEAM-11-C1X) (if(= (aref board TEAM-11-C1) (aref board index)) XCELL (* -1 XCELL))) 
			       ((= index TEAM-11-C2X) (if(= (aref board TEAM-11-C2) (aref board index)) XCELL (* -1 XCELL))) 
			       ((= index TEAM-11-C3X) (if(= (aref board TEAM-11-C3) (aref board index)) XCELL (* -1 XCELL))) 
			       ((= index TEAM-11-C4X) (if(= (aref board TEAM-11-C4) (aref board index)) XCELL (* -1 XCELL))) 
			       ((member index TEAM-11-C1C) (if(= (aref board TEAM-11-C1) (aref board index)) CCELL (* -1 CCELL))) 
			       ((member index TEAM-11-C2C) (if(= (aref board TEAM-11-C2) (aref board index)) CCELL (* -1 CCELL))) 
			       ((member index TEAM-11-C3C) (if(= (aref board TEAM-11-C3) (aref board index)) CCELL (* -1 CCELL))) 
			       ((member index TEAM-11-C4C) (if(= (aref board TEAM-11-C4) (aref board index)) CCELL (* -1 CCELL))) 
			       ((member index TEAM-11-EDGES) EDGE) 
			       
			       ;((member index TEAM-11-AX1) (if(= (aref board TEAM-11-C1) (aref board index)) AX (* -1 AX))) 
			       ;((member index TEAM-11-AX2) (if(= (aref board TEAM-11-C2) (aref board index)) AX (* -1 AX))) 
			       ;((member index TEAM-11-AX3) (if(= (aref board TEAM-11-C3) (aref board index)) AX (* -1 AX))) 
			       ;((member index TEAM-11-AX4) (if(= (aref board TEAM-11-C4) (aref board index)) AX (* -1 AX)))


			       (t (if (TEAM-11-frontier index board) FRONTIER NOTFRONTIER))
			       )
			      )
		      )
		  (if (not (= play cellOwner)) (setq weight (* weight -1)))
		  
		  ;;(format t "<~s,~s> (~c) [~s/~s]~%" row col (name-of cellOwner) weight score)
		  (setq score (+ score weight))
		  ;(print 'score)
		  ;(print score)
		  )
		)
	    )
	  )
	)
      )
    ;(progn (print 'score) (print score) (if (= player rplayer) (* -1 score) score))
    (if (= player rplayer) score (* -1 score))
    )
    
  )


(defun TEAM-11-frontier (index board)
  (dolist (i all-directions)
    (if (= 0 (aref board (+ index i))) (return-from TEAM-11-frontier t))
    )
)

;Need to write a function to order the moves
;This actually makes things slower.  Half the nodes are searched, but it takes a lot longer
(defun TEAM-11-orderMoves (moves board player nboard)
  ;(print 'orderMoves)
  ;(return-from TEAM-11-orderMoves moves)
  (let (
	(ordered '(0))
	(omoves '(0))
	)
    (dolist (m moves)
      ;(print m)
      ;(print (legal-moves (opponent player) (make-move m player (TEAM-11-copyIn board nboard))))
      ;(setf ordered (cons (cons m (* (if (= player rplayer) -1 1) (length (legal-moves (opponent player) (make-move m player (TEAM-11-copyIn board nboard)))))) ordered))
      (setf ordered (cons (cons m (* (if (= player rplayer) -1 1) (length (TEAM-11-genLegal (make-move m player (TEAM-11-copyIn board nboard))(opponent player))))) ordered))
      )
    (setf ordered (cdr (reverse ordered)))
    (sort ordered #'< :key #'cdr)
    ;(print ordered)
    (dolist (m ordered)
      ;(print 'm)
      ;(print m)
      (if (= (car omoves) 0) (setf omoves (list(car m))) (setf omoves (append omoves (list (car m)))))
      ;(print 'omoves)
      ;(print omoves)
      )
    ;(print omoves)
    (return-from TEAM-11-orderMoves omoves)
    )
)

(if (probe-file "c:/lisp/othello2/Tournament.lisp")
    (load "c:/lisp/othello2/Tournament.lisp"))


(defun TEAM-11-copyIn (array1 array2)
  (dotimes (n 100)
    (setf (aref array2 n) (aref array1 n))
    )
  array2
  )



;Checks if a move is legal, returns t or NIL
(defun TEAM-11-LEGAL-MOVE (move board player) 
  ;These are the bounds checks
  ;(print 'legal-move)
  (let ((opp NIL))
    (if (< move 11) (return-from TEAM-11-LEGAL-MOVE NIL))
    (if (> move 88) (return-from TEAM-11-LEGAL-MOVE NIL))
    (if (= (mod move 10) 0) (return-from TEAM-11-LEGAL-MOVE NIL))
    (if (= (mod (- move 9) 10) 0) (return-from TEAM-11-LEGAL-MOVE NIL))
		
    ;(print 'checks)
    ;(princ move)
					;Check if the square is empty
    (if (not(= (aref board move) 0)) (return-from TEAM-11-LEGAL-MOVE NIL))
    
					;Set opposite check
    (if (= player 1) (setf opp 2) (setf opp 1))
    
    ;(print 'checks)
    ;(princ move)
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
 

)

;Checks if a piece can be flipped 
(defun TEAM-11-checkFlip (start player board inc)
  ;(print 'checkflip)
  (if (or (= (aref board start) empty) (= (aref board start) outer)) (return-from TEAM-11-checkFlip NIL))
  (if (= (aref board start) player) t (TEAM-11-checkFlip (+ start inc) player board inc))
)

(defun TEAM-11-genLegal (board player)
  ;(print 'genlegal)
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
    (if (= (car lmoves) 0) (setf lmoves NIL))
    (return-from TEAM-11-genLegal lmoves)
    )
  )

(defun TEAM-11-TEST () 
  (let ((board (initial-board)))
    (print 'GO1)
    (TEAM-11-GO 1 board)
    (TEAM-11-GO 2 board)
        
    (print 'GO2)
    (TEAM-11-GO2 1 board)
    (TEAM-11-GO2 2 board)
    

    (print 'GO3)
    (TEAM-11-GO3 1 board)
    (TEAM-11-GO3 2 board)
    
    )
  )
  

(setf *load-compiling* NIL)
