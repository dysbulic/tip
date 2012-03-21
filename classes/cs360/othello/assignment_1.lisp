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


(defun print-board (&optional (board *board*))
  "Print a board, along with some statistics."
  ;; First print the header and the current score
  (format t "~2&    a b c d e f g h ")
  ;; Print the board itself
  (loop for row from 1 to 8 do
        (format t "~&  ~d " row)
        (loop for col from 1 to 8
              for piece = (bref board (+ col (* 10 row)))
              do (format t "~c " (name-of piece))))
 )

;;; The purpose of the first deliverable is to get your code to the point
;;; where it can successfully generate all possible moves for a given 
;;; player on a given board. Please take a good look at the description of 
;;; the board representation or email Larry (Larry.Thomas@vanderbilt.edu) 
;;; if you have questions. It is essential that you understand the board 
;;; representation, as on Game Day the tournament engine will use the 
;;; representation given in the assignment.

;;; Deliverable 1
;;; You are to write a function (you may name it anything but prefix it
;;; with "TEAM-N-" to make it unique) which given a board, a player and 
;;; a move checks if the move is in the proper format i.e between 11 and 88 
;;; and ends with 1..8, and is valid within the rules of othello, which 
;;; includes flipping at least one opponent's stone. You also must update 
;;; the board with a the verified move or output that the move is invalid. 

;;; You may structure your code as you see fit but your code needs to be 
;;; modular to facilitate reuse for later deliverables. Therefore, think about
;;; modularity that will divide the functionality into smaller cohesive units. 
;;; However, make sure that any functions you write have your team number
;;; prefixed. 

;;; You are also to write a function (again you may name it anything but  
;;; prefix "TEAM-N-" to make it unique) that given a board and a player,
;;; generates a list of all the valid moves (see outline of move generation
;;; below). The tournament engine expects your move to be in the native
;;; board format i.e between 11 and 88 and ending with 1..8 so you are to
;;; generate the moves in the same format although you may change it suit 
;;; your purposes if you wish (but your strategy function for Deliverables 2 
;;; and 3 must return a single move in the format the engine expects). If no
;;; moves can be generated, you are to ouput that there are no possible moves.
 
;;; Outline of Move Generation 
;;; The basic steps involved in move generation are 
;;; 1) being able to determine whether a given move is valid for a given board
;;; 2) being able to apply the valid check to all possible situations that 
;;; could occur from a given valid board hence generate all legal moves
;;; 3) being able to apply a given valid move to a board and determining the 
;;; resultant board


