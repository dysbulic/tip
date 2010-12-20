; testing what adding items during iteration does
; appended items are not iterated over

(setq list '(1 2 3))
(dolist (num list)
  (format t "~D ~S~%" num list)
  (if (< num 6) (setq list (append list (list (+ num 1))))))

