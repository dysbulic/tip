; Othello Deliverable #3
; CS360 - Artificial Intelligence
; Vanderbilt University
; Fall 2007
; Robert Halpenny and William Holcomb

; I pledge my honor that I have neither given nor received aid on this
; work beyond what is permitted by the instructor.
(setf *load-compiling* t)

(defconstant all-directions '(-11 -10 -9 -1 1 9 10 11))

(defconstant empty 0 "An empty square")
(defconstant black 1 "A black piece")
(defconstant white 2 "A white piece")
(defconstant outer 3 "Marks squares outside the 8x8 board")

(defconstant MINVALUE -1000000)
(defconstant MAXVALUE 1000000)

;Globals
(defvar TEAM-11-rplayer) ;real player color
(defvar TEAM-11-maxDepth) ;maximum depth to search (for iterative deepening)
(defvar TEAM-11-DEAD) ;Used to detect deadlock
(defvar TEAM-11-nodes) ;nodes searched
(defvar TEAM-11-sarrays) ;preallocaed arrays
(defvar TEAM-11-rtime) ;time

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

;Inits the arrays used in the search
;Preallocate the arrays so we don't have to use the garbage collector as much 
(defun TEAM-11-initArrays ()
  (let (( l NIL))
    (dotimes (n 25)
      (setf l (cons (list (make-array 100 :initial-element empty)) l))
      )
    (return-from TEAM-11-initArrays l)
    )
)

;The general recursive search function
;Employs Principle Variation Search
(defun TEAM-11-search (board depth alpha beta player)
  ;GAME OVER MAN
  (if (TEAM-11-full board) (return-from TEAM-11-search (TEAM-11-didWin board)))
  
  ;Have reached max depth, return the evaluation function
  (if  (>= depth TEAM-11-maxDepth) (return-from TEAM-11-search (TEAM-11-Evaluate7 player board)))
  ;Used for testing two different eval functions
  
  ;Define some local variables
  (let (
	(lmoves (TEAM-11-genLegal board player)) ;The legal moves
	(score 0) ;Best score
	(value 0) ;temp score
	(alp alpha) ;alpha for this search
	)
    
    ;if no moves, go back to the other player
    (when (eq lmoves NIL)
      (if TEAM-11-DEAD 
	  (progn 
	    ;If dead was already set, no one can move, game over
	    (return-from TEAM-11-search (TEAM-11-didWin board))))
					;set a temp variable so we can tell if we hit a dead end
      (setf TEAM-11-DEAD t)
      (return-from TEAM-11-search (TEAM-11-search board depth (* -1 beta) (* -1 alp) (opponent player)))
      )
    (setf TEAM-11-DEAD NIL)
					;Order the moves to try to improve pruning
    (setf lmoves (TEAM-11-orderMoves lmoves board player (car (nth depth TEAM-11-sarrays)))) 
    ;get score for first move.
    (setf score (* -1 (TEAM-11-search (make-move  (pop lmoves) player (TEAM-11-copyIn board (car (nth depth TEAM-11-sarrays)))) (+ depth 1) (* -1 beta) (* -1 alp) (opponent player))))
    
    ;for rest of moves, do search with pruning
    (dolist (m lmoves score)
      (setf TEAM-11-DEAD NIL)
      (if (>= score beta) (return-from TEAM-11-search score)) ;Cutoff
      (if (> score alp) (setf alp score))
      (setf value (* -1 (TEAM-11-search (make-move  m player (TEAM-11-copyIn board (car (nth depth TEAM-11-sarrays)))) (+ depth 1) (- (* -1 alp) 1) (* -1 alp) (opponent player))))
      (when (> value score)
	(if (and (< alp value) (< value beta))
	    (setf score (* -1 (TEAM-11-search (make-move  m player (TEAM-11-copyIn board (car (nth depth TEAM-11-sarrays)))) (+ depth 1) (* -1 beta) (* -1 value) (opponent player))))
	  (setf score value)
	  )
	)
      )  
    )
  )


;This function tells you if the board is full
(defun TEAM-11-full (board)
  (dotimes (i 100)
    (if (= (aref board i) 0) (return-from TEAM-11-full NIL))
    
    )
  t
  )

;Evaluates if TEAM-11-rplayer won or lost
(defun TEAM-11-didWin (board)
  (let ((dif (count-difference TEAM-11-rplayer board)))
    (if (> dif 0) (return-from TEAM-11-didWin (+ MAXVALUE dif)))
    (if (= (count-difference TEAM-11-rplayer board) 0) (return-from TEAM-11-didWin 0))
    (+ MINVALUE dif)
    )
)

;Main function for playing
;Performs PVS, but also holds the logic for iterative deepening, initialization, some other time-saving stuff
(defun TEAM-11-GO (player board)
  "Make a move for the given player on the given board"
  (let (
	(lmoves NIL) ;legal-moves
	(bm 0) ;current best move
	(obm 0);old best move in case we had to bail when we're out of time
	(t1 0) ;time started
	(t2 0) ;temp time
	(beta MAXVALUE) 
	(alpha MINVALUE)
	(score 0)
	(value 0)
	(otime 0) ;old time
	(lmoves2 NIL) ;backup of legal moves
	
	)
    
    (setf TEAM-11-rplayer player) ;set the rplayer, this is used later by a bunch of stuff
    (setf TEAM-11-DEAD NIL) ;variable to tell if deadlock reached before end of game
    (setf TEAM-11-nodes 0) ;Count for the number of nodes evaluated, useful for tuning searches
    (setf TEAM-11-sarrays (TEAM-11-initArrays)) ;pre-allocated search arrays

    ;Get time
    (if (= TEAM-11-rplayer black) (setf TEAM-11-rtime (aref *clock* 1)) (setf TEAM-11-rtime (aref *clock* 2)))
    (setf TEAM-11-rtime (/ TEAM-11-rtime internal-time-units-per-second))
    (setf t1 (get-internal-real-time))
    (setf t2 t1)
    (setf otime t1)

    ;Get legal moves
    (setf lmoves (TEAM-11-genLegal board player))
    
    ;no point in searching
    (if (= (length lmoves) 1) (return-from TEAM-11-GO (car lmoves)))
    (setf lmoves (TEAM-11-orderMoves lmoves board player (car (nth 0 TEAM-11-sarrays))))    
    (setf lmoves2 lmoves)
    
    ;Search maximum depth of 20
    (dotimes (n 20)
      (setf alpha MINVALUE)
      (setf lmoves lmoves2)
      (setf score MINVALUE)
      (if (= n 0) (setf n 2))
      ;Only search even levels
      (if (and (= TEAM-11-rplayer black) (= (mod n 2) 1)) (setf n (+ n 1)))
      (if (and (= TEAM-11-rplayer white) (= (mod n 2) 1)) (setf n (+ n 1)))
      
      (if (and (< *move-number* 8) (> n 4)) (return-from TEAM-11-GO bm))
      (setf obm bm)  ;stash the old best move if we run out of time
      (if (and (> 2 TEAM-11-rtime) (> n 2)) (return-from TEAM-11-GO bm))
      (if (and (> 10 TEAM-11-rtime) (> n 4)) (return-from TEAM-11-GO bm))
      ;If we've been search for 3.5 seconds, don't do another iteration
      ;*CHANGE THIS BACK*
      (if (> (/ (- t2 t1) internal-time-units-per-second) 1) (return-from TEAM-11-GO bm))
      (setf t1 t2)
      (setf TEAM-11-maxDepth n)
      (setf bm (car lmoves))
      
      ;get score for first move
      (setf score (* -1 (TEAM-11-search (make-move  (car lmoves) player (TEAM-11-copyIn board (car( nth 0 TEAM-11-sarrays)))) 1 (* -1 beta) (* -1 alpha) (opponent player))))
      (pop lmoves)
      
      (dolist (m lmoves)
	;If we are out of time, bail
	(if (and (> 10 (- TEAM-11-rtime (/ (- (get-internal-real-time) otime) internal-time-units-per-second))) (> n 4)) (return-from TEAM-11-GO obm))
	;if we are taking too long, bail
	(if (> (/ (- (get-internal-real-time) t1) internal-time-units-per-second) 20) (return-from TEAM-11-GO obm))
	(setf TEAM-11-DEAD NIL)
	
	(when (not (>= score beta))
	  
	  (setf alpha (max score alpha))
	  (if (> score alpha) (setf alpha score))
	  (setf value (* -1 (TEAM-11-search (make-move  m player (TEAM-11-copyIn board (car (nth 0 TEAM-11-sarrays))))  1 (- (* -1 alpha) 1) (* -1 alpha) (opponent player))))
	  (when (> value score)
	    (if (and (< alpha value) (< value beta)) (progn 
						       (setf score (* -1 (TEAM-11-search (make-move  m player (TEAM-11-copyIn board (car (nth 0 TEAM-11-sarrays)))) 1 (* -1 beta) (* -1 value) (opponent player))))
						      
						       (setf bm m))
	      (progn (setf score value) (setf bm m))
	      )
	    )
	  )
	)
      
      (setf t2 (get-internal-real-time))
      
      )
    bm
    )
  )


;These are all the constants used by the eval functions
;For 7+8
(defconstant TEAM-11-CORNER3 50)
(defconstant TEAM-11-CCELLGOOD 8)
(defconstant TEAM-11-CCELLBAD -50)
(defconstant TEAM-11-XCELLGOOD 4)
(defconstant TEAM-11-XCELLBAD -50)
(defconstant TEAM-11-FRONTIER3 -2)
(defconstant TEAM-11-NOTFRONTIER3 1)
(defconstant TEAM-11-EDGE3 10)
(defconstant TEAM-11-AX3 2)

(defconstant TEAM-11-CORNER4 5)
(defconstant TEAM-11-CCELL4 2)
(defconstant TEAM-11-XCELL4 2)
(defconstant TEAM-11-STABLE4 1)
(defconstant TEAM-11-EDGE4 2)


(defconstant TEAM-11-OPP-MOBILITY 10)
(defconstant TEAM-11-PLY-MOBILITY 10)
(defconstant TEAM-11-STABLEWEIGHT 10)

;Sets of squares
(defconstant TEAM-11-Corners '(11 18 81 88))
(defconstant TEAM-11-C-Cells '(21 12 17 28 78 87 82 71))
(defconstant TEAM-11-X-Cells '(22 27 77 72))
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
(defconstant TEAM-11-AXL '(23 32 26 37 62 73 67 76))
(defconstant TEAM-11-AXL1 '(23 32))
(defconstant TEAM-11-AXL2 '(26 37))
(defconstant TEAM-11-AXL3 '(62 73))
(defconstant TEAM-11-AXL4 '(67 76))

;BASE array for the stability calculation
(defvar TEAM-11-BASESTABLE (make-array 100 :initial-contents '(1 1 1 1 1 1 1 1 1 1
							     1 0 0 0 0 0 0 0 0 1
							     1 0 0 0 0 0 0 0 0 1
							     1 0 0 0 0 0 0 0 0 1
							     1 0 0 0 0 0 0 0 0 1
							     1 0 0 0 0 0 0 0 0 1
							     1 0 0 0 0 0 0 0 0 1
							     1 0 0 0 0 0 0 0 0 1
							     1 0 0 0 0 0 0 0 0 1	    
							     1 1 1 1 1 1 1 1 1 1)))

;Evaluation function
;Computes three things
;1) Value based on the stable squares (not complete)
;2) Value based on mobility
;3) Value based on Eval8
;Combines those
(defun TEAM-11-Evaluate7 (player board)
  "Evaluates the strength of a given board for a given player"
  ;(setf TEAM-11-nodes (+ TEAM-11-nodes 1))
  
  (let ((score 0)
	(sboard (TEAM-11-buildStable board (TEAM-11-CopyIn TEAM-11-BASESTABLE (car (nth 23 TEAM-11-sarrays)))))
	)
    (loop for row from 1 to 8 do
      (loop for col from 1 to 8 do
	(let ((index (+ (* row 10) col)))
	  (let (
		
		(cellOwner (bref board index))
		(play TEAM-11-rplayer)
		)
	    (if  (and (not (= cellOwner empty)) (= (aref sboard index) 1))
		(let ((weight (cond 
			       ((member index TEAM-11-Corners) TEAM-11-CORNER4)
			       ((member index TEAM-11-X-Cells) TEAM-11-XCELL4)
			       ((member index TEAM-11-C-Cells) TEAM-11-CCELL4)
			       ((member index TEAM-11-EDGES) TEAM-11-EDGE4)
			       (t TEAM-11-STABLE4)
			       )
			      )
		      )
		  
		  (if (not (= play cellOwner)) (setq weight (* weight -1)))
		  (setq score (+ score weight))
		  )
		)
	    )
	  )
	)
      )
    
    (setf score (* TEAM-11-STABLEWEIGHT score)) 
    (setf score (- score (* TEAM-11-OPP-MOBILITY (length (TEAM-11-genLegal board (opponent TEAM-11-rplayer) )))))
    (setf score (+ score (* TEAM-11-PLY-MOBILITY (length (TEAM-11-genLegal board TEAM-11-rplayer )))))
    
    (if (= player TEAM-11-rplayer) t (setf score (* -1 score)))
    (setf score (+ score (TEAM-11-EVALUATE8 player board sboard)))
    
      
    )
    
  )

;Basic eval function for, uses weights for all the squares
(defun TEAM-11-Evaluate8 (player board sboard)
  "Evaluates the strength of a given board for a given player"
  ;(setf TEAM-11-nodes (+ TEAM-11-nodes 1))
  (let ((score 0))
    (loop for row from 1 to 8 do
      (loop for col from 1 to 8 do
	(let ((index (+	(* row 10) col)))
	  (let ((cellOwner (bref board index))
		(play TEAM-11-rplayer)
		)
	    ;make sure the cell isn't empty and we aren't double-counting it with eval7
	    (if  (and (not (= cellOwner empty)) (= (aref sboard index) 0))
		
		(let ((weight (cond 
			       
			       ((member index TEAM-11-Corners) TEAM-11-CORNER3)
			       ((= index TEAM-11-C1X) (if(not (= (aref board TEAM-11-C1) empty)) TEAM-11-XCELLGOOD TEAM-11-XCELLBAD)) 
			       ((= index TEAM-11-C2X) (if(not (= (aref board TEAM-11-C2) empty)) TEAM-11-XCELLGOOD TEAM-11-XCELLBAD)) 
			       ((= index TEAM-11-C3X) (if(not (= (aref board TEAM-11-C3) empty)) TEAM-11-XCELLGOOD TEAM-11-XCELLBAD)) 
			       ((= index TEAM-11-C4X) (if(not (= (aref board TEAM-11-C4) empty)) TEAM-11-XCELLGOOD TEAM-11-XCELLBAD)) 
			       
			       ((member index TEAM-11-C1C) (if(not (= (aref board TEAM-11-C1) empty)) TEAM-11-CCELLGOOD TEAM-11-CCELLBAD)) 
			       ((member index TEAM-11-C2C) (if(not (= (aref board TEAM-11-C2) empty)) TEAM-11-CCELLGOOD TEAM-11-CCELLBAD)) 
			       ((member index TEAM-11-C3C) (if(not (= (aref board TEAM-11-C3) empty)) TEAM-11-CCELLGOOD TEAM-11-CCELLBAD)) 
			       ((member index TEAM-11-C4C) (if(not (= (aref board TEAM-11-C4) empty)) TEAM-11-CCELLGOOD TEAM-11-CCELLBAD)) 

			       
			       ;((member index TEAM-11-AXL1) (if(not (= (aref board TEAM-11-C1) empty)) TEAM-11-AX2 (* -1 TEAM-11-AX2))) 
			       ;((member index TEAM-11-AXL2) (if(not (= (aref board TEAM-11-C2) empty)) TEAM-11-AX2 (* -1 TEAM-11-AX2))) 
			       ;((member index TEAM-11-AXL3) (if(not (= (aref board TEAM-11-C3) empty)) TEAM-11-AX2 (* -1 TEAM-11-AX2))) 
			       ;((member index TEAM-11-AXL4) (if(not (= (aref board TEAM-11-C4) empty)) TEAM-11-AX2 (* -1 TEAM-11-AX2)))

			       ((member index TEAM-11-EDGES) TEAM-11-EDGE3)
			       (t (if (TEAM-11-frontier index board) TEAM-11-FRONTIER3 TEAM-11-NOTFRONTIER3))
			       )
			      )
		      )
		  (if (not (= play cellOwner)) (setq weight (* weight -1)))
		
		  (setq score (+ score weight))
		  )
		)
	    )
	  )
	)
      )
    
   (if (= player TEAM-11-rplayer) score (* -1 score))
    )
    
  )


;Constant sets to define corners to check for stablity
(defconstant TEAM-11-STABLEDIRECTIONS '((-1 -11 -10) (-10 -9 1) (1 11 10) (-1 9 10)))
;Constant sets to define opposite sides to check for pseudo-stability
(defconstant TEAM-11-OPPOSITES '((-1 1) (-11 11) (-10 10) (-9 9)))

;This function returns whether a piece is
;or(
;hard-stable - cannot possibly be flipped - Incomplete
;pseudo-stable - cannot be flipped this turn - Incomplete
;)
(defun TEAM-11-isStable (board sboard square)
  ;(print 'isstable)
  ;four cases, if a corner is stable, this square is stable
  (if (= (aref board square) empty) (return-from TEAM-11-isStable NIL))
  (dolist (dirs TEAM-11-STABLEDIRECTIONS)
    (if (and 
	 (not (= (aref board square) empty))
	 (or 
	  (and (= 1 (aref sboard (+ square (nth 0 dirs)))) 
	       (= (aref board square) (aref board (+ square (nth 0 dirs)))))
	  (= (aref board (+ square (nth 0 dirs))) outer))
	 (or 
	  (and (= 1 (aref sboard (+ square (nth 1 dirs)))) 
	       (= (aref board square) (aref board (+ square (nth 1 dirs)))))
	  (= (aref board (+ square (nth 1 dirs))) outer))
	 (or 
	  (and (= 1 (aref sboard (+ square (nth 2 dirs)))) 
	       (= (aref board square) (aref board (+ square (nth 2 dirs)))))
	  (= (aref board (+ square (nth 2 dirs))) outer))
	 ) 
	(return-from TEAM-11-isStable t)
      )
    )

  ;(if (= TEAM-11-rplayer white) 
  ;(if (TEAM-11-inFullEdge board square) (return-from TEAM-11-isStable t))
  ;  )
  ;A number of other ways a square can be stable.  I think this is not complete
  (dolist (opps TEAM-11-OPPOSITES)
    
    (if (or
	 ;(if (= TEAM-11-rplayer white) (TEAM-11-surOpp board square (car opps) (car (cdr opps))) NIL);Surrounded by opposites
	 ;(TEAM-11-surOpp board square (car opps) (car (cdr opps)))
	 ;(and 
	 ; (= (aref board (+ (car opps) square)) (opponent (aref board square))) 
	 ; (= (aref board (+ (car (cdr opps)) square)) (opponent (aref board square)))
	 ; )
;Surrounded by stable friendly pieces
	 (or 
	  (and
	   (= (aref board (+ (car opps) square)) (aref board square))
	   (= (aref sboard (+ (car opps) square)) 1)
	   )
	  (and
	   (= (aref board (+ (car (cdr opps)) square)) (aref board square))
	   (= (aref sboard (+ (car (cdr opps)) square)) 1)
	   )
	  )
	 ;one side is an outer square
	 (or
	  (= (aref board (+ (car opps) square)) outer)
	  (= (aref board (+ (car (cdr opps)) square)) outer)
	  )
	 )
	t (return-from TEAM-11-isStable NIL))
    

    )
  
  t
  )

(defconstant TEAM-11-fEDGE1 '(11 12 13 14 15 16 17 18))
(defconstant TEAM-11-fEDGE2 '(18 28 38 48 58 68 78 88))
(defconstant TEAM-11-fEDGE3 '(88 87 86 85 84 83 82 81))
(defconstant TEAM-11-fEDGE4 '(81 71 61 51 41 31 21 11))

;decides if a piece is in a full edge, and therefore stable
(defun TEAM-11-inFullEdge (board square)
  (if (member square TEAM-11-fEDGE1) (return-from TEAM-11-inFullEdge (TEAM-11-fullEdge board TEAM-11-fEDGE1)))
  (if (member square TEAM-11-fEDGE2) (return-from TEAM-11-inFullEdge (TEAM-11-fullEdge board TEAM-11-fEDGE2)))
  (if (member square TEAM-11-fEDGE3) (return-from TEAM-11-inFullEdge (TEAM-11-fullEdge board TEAM-11-fEDGE3)))
  (if (member square TEAM-11-fEDGE4) (return-from TEAM-11-inFullEdge (TEAM-11-fullEdge board TEAM-11-fEDGE4)))
)

;decides if an edge is full
(defun TEAM-11-fullEdge (board squares)
  (dolist (s squares)
    (if (= (aref board s) empty) (return-from TEAM-11-fullEdge NIL))
    )
  t
)

;This function figures out if a square is surrouned on d1 and d2 byopponent pieces
;For some reason, this makes things worse
(defun TEAM-11-surOPP (board square d1 d2)
  ;(print 'square)
  ;(print square)
  ;(print d1)
  ;(print d2)
  (do ((index (+ square d1) (+ index d1)))
      ( (= (aref board index) (opponent (aref board square))) t)
    ;(print 'index)
    (if (= (aref board index) outer) (return-from TEAM-11-surOPP NIL))
    )
  (do ((index (+ square d2) (+ index d2)))
      ( (= (aref board index) (opponent (aref board square))) t)
    ;(print index)
    (if (= (aref board index) outer) (return-from TEAM-11-surOPP NIL))
    )
)

;Order to evaluate squares for stability
(defconstant TEAM-11-STABLEORDER '(11 18 81 88 
				   21 12 
				   17 28
				   71 82
				   78 87
				   31 22 13
				   16 27 38
				   61 72 83
				   86 77 68
				   41 32 23 14
				   15 26 37 48
				   51 62 73 84
				   58 67 76 85
				   42 33 24 
				   25 36 47
				   52 63 74
				   57 66 75
				   43 34
				   35 46
				   53 64
				   56 65
				   44 45
				   54 55
				      ))

;Builds a board of all the stable pieces.
;This is used to decide whether you should get a lot of points for that square
(defun TEAM-11-buildStable (board sboard)
					;continue until no new stable squares added
  (do ((newStable NIL NIL))
      (NIL)
    (dolist (square TEAM-11-STABLEORDER)
      (if (not (= (aref sboard square) 1)) (if (TEAM-11-isStable board sboard square) (progn 
										(setf (aref sboard square) 1)
										(setf newStable t)
										)
					     ))
      )
    (if (not newStable) (return-from TEAM-11-buildStable sboard))
    )
  sboard
  )


;Decides if a square is a frontier square
(defun TEAM-11-frontier (index board)
  (dolist (i all-directions)
    (if (= 0 (aref board (+ index i))) (return-from TEAM-11-frontier t))
    )
)

;This function basically orders the moves by minimizing mobility for the opponent
(defun TEAM-11-orderMoves (moves board player nboard)
  (let (
	(ordered '(0))
	(omoves '(0))
	)
    (dolist (m moves)
      
      (setf ordered (cons (cons m (* (if (= player TEAM-11-rplayer) -1 1) (length (TEAM-11-genLegal (make-move m player (TEAM-11-copyIn board nboard))(opponent player))))) ordered))
      )
    (setf ordered (cdr (reverse ordered)))
    (sort ordered #'< :key #'cdr)
    (dolist (m ordered)
      (if (= (car omoves) 0) (setf omoves (list(car m))) (setf omoves (append omoves (list (car m)))))
      )
    (return-from TEAM-11-orderMoves omoves)
    )
)

;Copies array1 to array2
(defun TEAM-11-copyIn (array1 array2)
  (dotimes (n 100)
    (setf (aref array2 n) (aref array1 n))
    )
  array2
  )



;Checks if a move is legal, returns t or NIL
(defun TEAM-11-LEGAL-MOVE (move board player) 
  ;These are the bounds checks
   (let ((opp NIL))
    (if (< move 11) (return-from TEAM-11-LEGAL-MOVE NIL))
    (if (> move 88) (return-from TEAM-11-LEGAL-MOVE NIL))
    (if (= (mod move 10) 0) (return-from TEAM-11-LEGAL-MOVE NIL))
    (if (= (mod (- move 9) 10) 0) (return-from TEAM-11-LEGAL-MOVE NIL))
		
    (if (not(= (aref board move) 0)) (return-from TEAM-11-LEGAL-MOVE NIL))
    
    (if (= player 1) (setf opp 2) (setf opp 1))
    
    (if (and (= (aref board (+ move -1)) opp) (TEAM-11-checkFlip (+ move -2) player board -1)) (return-from TEAM-11-LEGAL-MOVE t))
	
    (if (and (= (aref board (+ move -11)) opp) (TEAM-11-checkFlip (+ move -22) player board -11)) (return-from TEAM-11-LEGAL-MOVE t))
	
    (if (and (= (aref board (+ move -10)) opp) (TEAM-11-checkFlip (+ move -20) player board -10)) (return-from TEAM-11-LEGAL-MOVE t))
	
    (if (and (= (aref board (+ move -9)) opp) (TEAM-11-checkFlip (+ move -18) player board -9)) (return-from TEAM-11-LEGAL-MOVE t))
	
    (if (and (= (aref board (+ move 1)) opp) (TEAM-11-checkFlip (+ move 2) player board 1)) (return-from TEAM-11-LEGAL-MOVE t))
	
    (if (and (= (aref board (+ move 11)) opp) (TEAM-11-checkFlip (+ move 22) player board 11)) (return-from TEAM-11-LEGAL-MOVE t))
	
    (if (and (= (aref board (+ move 10)) opp) (TEAM-11-checkFlip (+ move 20) player board 10)) (return-from TEAM-11-LEGAL-MOVE t))
	
    (if (and (= (aref board (+ move 9)) opp) (TEAM-11-checkFlip (+ move 18) player board 9)) (return-from TEAM-11-LEGAL-MOVE t))
    
    NIL
    )
 

)

;Checks if a piece can be flipped 
(defun TEAM-11-checkFlip (start player board inc)
  (if (or (= (aref board start) empty) (= (aref board start) outer)) (return-from TEAM-11-checkFlip NIL))
  (if (= (aref board start) player) t (TEAM-11-checkFlip (+ start inc) player board inc))
)

;Generates a list of all legal moves
(defun TEAM-11-genLegal (board player)

  (let ((lmoves '(0)))
    (do ((n 0 (+ n 1)))
	((= n 100) lmoves)
      (when (TEAM-11-LEGAL-MOVE n board player) 
	(if (equal (car lmoves) 0) (setf lmoves (list n)) (setf lmoves (cons n lmoves)))
	)
      )
    (if (= (car lmoves) 0) (setf lmoves NIL))
    (return-from TEAM-11-genLegal lmoves)
    )
  )

(setf *load-compiling* NIL)

