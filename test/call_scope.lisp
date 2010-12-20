;; I need to know how scoping works in lisp

(setq global 'set)

(defun print-global ()
  (print global))

(print-global)

(defun scope-global ()
  (let ((global 'inner))
    (print-global)))

(scope-global)

(defun print-lex ()
  (print (cond ((boundp 'lex) lex))))

(defun let-lex ()
  (let ((lex 'inner))
    (print-lex)))

(defun setq-lex ()
  (setq lex 'inner)
  (print-lex))

(let-lex)
(setq-lex)
(print-lex)
