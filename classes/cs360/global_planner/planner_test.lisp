; Global Planner Test Scenarios
;
; CS-360 - Artificial Intelligence
; Vanderbilt University
; Fall 2007
; Will Holcomb <wholcomb@gmail.com>

;; This file is sometimes combined with the main function definitions
(if (and (not (fboundp 'GPS)) (probe-file "planner.lisp"))
    (load "planner.lisp"))

(defun test-gps ((title "Untitled") start goal ops)
  (format t "Scenario: ~S~%" title)
  (format t " Start: ~S~%" start)
  (format t "  Goal: ~S~%" goal)
  (let ((procedure (GPS start goal ops)))
    (cond ((null procedure) (format t "  No solution found"))
          ((equal procedure t) (format t "  Goal satisfied by initial state"))
          (t (format t "  Procedure: ~S" (map 'list #'op-action procedure))))
    (format t "~%~%")))

; Drive son to school:
;
; I want to take my son to nursery school. What's the difference
; between what I have and what I want? One of distance. What changes
; distance? My automobile. My automobile will not work. What is needed
; to make it work? A new battery. What has new batteries? An auto
; repair shop. I want the repair shop to put in a new battery; but the
; shop does not know I need one. What is the difficulty? One of
; communication. What allows communication? A telephone. I have a
; telephone but do not know the phone number. How do I find out the
; phone number? I need a phonebook. I have a phone but I have to look
; up the number before I know it.

(defparameter *school-ops*
  (list
   (make-op :action 'drive-son-to-school
            :preconds '(son-at-home car-works)
            :add-list '(son-at-school)
            :del-list '(son-at-home))
   (make-op :action 'shop-installs-battery
            :preconds '(car-needs-battery shop-knows-problem shop-has-money)
            :add-list '(car-works)
            :del-list '(car-needs-battery shop-knows-problem shop-contacted shop-has-money))
   (make-op :action 'telephone-shop
            :preconds '(know-shop-number have-phone)
            :add-list '(shop-contacted)
            :del-list '())
   (make-op :action 'tell-shop-problem
            :preconds '(shop-contacted)
            :add-list '(shop-knows-problem)
            :del-list '())
   (make-op :action 'look-up-number
            :preconds '(have-phonebook)
            :add-list '(know-shop-number)
            :del-list '())
   (make-op :action 'ask-phone-number
            :preconds '(have-phone)
            :add-list '(know-shop-number)
            :del-list '())
   (make-op :action 'give-shop-money
            :preconds '(car-needs-battery shop-contacted)
            :add-list '(shop-has-money)
            :del-list '())))

(test-gps "Achievable goal"
          '(son-at-home car-at-home car-needs-battery have-money have-phone have-phonebook)
          '(son-at-school)
          *school-ops*)

(test-gps "Achievable goal - No phonebook"
          '(son-at-home car-at-home car-needs-battery have-money have-phone)
          '(son-at-school car-works)
          *school-ops*)

(test-gps "Already achieved goal"
          '(son-at-home car-at-home car-needs-battery have-money have-phone)
          '(son-at-home)
          *school-ops*)

(test-gps "Unachievable goal - Contratictory"
          '(son-at-home car-at-home car-needs-battery have-money have-phone have-phonebook)
          '(son-at-school son-at-home)
          *school-ops*)

(test-gps "Simple goal"
          '(son-at-home have-phone have-phonebook)
          '(know-shop-number)
          *school-ops*)

; Monkey and Bananas:
;
; A hungry monkey is standing at the doorway to a room. In the middle
; of the room is a bunch of bananas suspended from the ceiling by a
; rope, well out of the monkey's reach. There is a chair near the
; door, which is light enough for the monkey to push and tall enough
; to reach the bananas. Assume the monkey is holding a toy ball and
; can only hold one thing at a time.

(defparameter *banana-ops*
  (list
   (make-op :action 'climb-on-chair-in-middle
            :preconds '(in-middle chair-in-middle)
            :add-list '(on-chair)
            :del-list '(on-floor))
   ; the monkey is assumed to either be at the door or in the middle of the room
   (make-op :action 'walk-to-middle
            :preconds '(at-door)
            :add-list '(in-middle)
            :del-list '(at-door))
   (make-op :action 'push-chair-from-door-to-middle
            :preconds '(chair-at-door at-door)
            :add-list '(chair-in-middle in-middle)
            :del-list '(chair-at-door at-door))
   (make-op :action 'grasp-bananas
            :preconds '(hands-empty chair-in-middle in-middle on-chair)
            :add-list '(holding-banana)
            :del-list '(hands-empty))
   (make-op :action 'drop-ball
            :preconds '(holding-ball)
            :add-list '(hands-empty)
            :del-list '(holding-ball))
   (make-op :action 'pickup-ball
            :preconds '(hands-empty)
            :add-list '(holding-ball)
            :del-list '(hands-empty))
   (make-op :action 'eat-bananas
            :preconds '(holding-banana)
            :add-list '(full hands-empty)
            :del-list '(holding-banana hungry))))

(test-gps "Achievable goal"
          '(hungry at-door chair-at-door holding-ball)
          '(full)
          *banana-ops*)

(test-gps "Achievable goal"
          '(holding-banana at-door chair-in-middle)
          '(hands-empty on-chair)
          *banana-ops*)

; Even though there is a logic sequence of actions to acheive this
; goal (pickup-ball has been added), it is not possible because the
; monkey is disallowed from having his hands empty twice.
(test-gps "Unachievable goal - Repeated states"
          '(hungry at-door chair-at-door holding-ball)
          '(full holding-ball on-chair)
          *banana-ops*)

; In the problem, three blocks (labeled A, B, and C) rest on a table.
; The agent must stack the blocks such that A is atop B is atop C.
; However, it may only move one block at a time. The problem starts
; with B on the table, C atop A, and A on the table:

;; Generate all permutations of the operations for moving blocks

(defparameter *block-ops* ())
(let ((bases '(A B C floor)))
  (dolist (blk (remove 'floor bases))
    (dolist (to bases)
      (dolist (from bases)
        (if (and (not (equal to from)) (not (equal blk to)) (not (equal blk from)))
            (let ((op (make-op :action (intern (format nil "put~S_~S-~S" blk from to))
                               :preconds (list (intern (format nil "ON-~S_~S" blk from))
                                               (intern (format nil "CLEAR-~S" blk))
                                               (intern (format nil "CLEAR-~S" to)))
                               :add-list (list (intern (format nil "ON-~S_~S" blk to)))
                               :del-list (list (intern (format nil "ON-~S_~S" blk from))
                                               (intern (format nil "CLEAR-~S" to))))))
              (push op *block-ops*)))))))

(test-gps "Acheivable Goal"
          '(on-A_floor clear-A on-B_floor clear-B on-C_floor clear-C)
          '(on-A_B on-B_C)
          *block-ops*)

(test-gps "Unacheivable Goal"
          '(on-A_floor clear-A on-B_floor clear-B on-C_floor clear-C)
          '(on-A_B on-B_A)
          *block-ops*)


;(setq *debug-logging* t)

;; The Sussman anomaly will still be present because the state on-B_C
;; must be dealt with twice which is disallowed.
(test-gps "Sussman Anomaly"
          '(on-A_B clear-A on-B_floor on-C_floor clear-C)
          '(on-A_B on-B_C)
          *block-ops*)
