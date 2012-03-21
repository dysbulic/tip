; Loads testing framework and runs team 11's evaluation code against a
; randomizing opponent

;; (load "TEAM11DEL1.lisp")
;; (load "TEAM11DEL2.lisp")
(load "TEAM11DEL3.lisp")
(load "Tournament.lisp")

(othello #'TEAM-11-GO #'random-strategy t)
