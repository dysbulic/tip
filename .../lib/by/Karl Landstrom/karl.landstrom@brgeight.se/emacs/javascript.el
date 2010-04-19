;;; javascript.el --- Major mode for editing JavaScript source text

;; Copyright (C) 2008 Free Software Foundation, Inc.

;; Author: Karl Landstrom <karl.landstrom@brgeight.se>
;; Maintainer: Karl Landstrom <karl.landstrom@brgeight.se>
;; Version: 2.2.1
;; Date: 2008-12-27
;; Keywords: languages, oop

;; This file is free software; you can redistribute it and/or modify
;; it under the terms of the GNU General Public License as published by
;; the Free Software Foundation; either version 2, or (at your option)
;; any later version.

;; This file is distributed in the hope that it will be useful,
;; but WITHOUT ANY WARRANTY; without even the implied warranty of
;; MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
;; GNU General Public License for more details.

;; You should have received a copy of the GNU General Public License
;; along with GNU Emacs; see the file COPYING.  If not, write to
;; the Free Software Foundation, Inc., 59 Temple Place - Suite 330,
;; Boston, MA 02111-1307, USA.

;;; Commentary:
;;
;; The main features of this JavaScript mode are syntactic
;; highlighting (enabled with `font-lock-mode' or
;; `global-font-lock-mode'), automatic indentation and filling of
;; comments.
;;
;; This package has (only) been tested with GNU Emacs 21.4 (the latest
;; stable release).
;;
;; Installation:
;;
;; Put this file in a directory where Emacs can find it (`C-h v
;; load-path' for more info). Then add the following lines to your
;; Emacs initialization file:
;; 
;;    (add-to-list 'auto-mode-alist '("\\.js\\'" . javascript-mode))
;;    (autoload 'javascript-mode "javascript" nil t)
;;    
;; General Remarks:
;; 
;; This mode assumes that block comments are not nested inside block
;; comments and that strings do not contain line breaks.
;; 
;; Exported names start with "javascript-" whereas private names start
;; with "js-".
;; 
;; Changes:
;;
;; See javascript.el.changelog.

;;; Code:

(require 'cc-mode)
(require 'font-lock)
(require 'newcomment)

(defgroup javascript nil 
  "Customization variables for `javascript-mode'."
  :tag "JavaScript"
  :group 'languages)

(defcustom javascript-indent-level 4
  "Number of spaces for each indentation step."
  :type 'integer
  :group 'javascript)

(defcustom javascript-expr-indent-offset 0
  "Number of additional spaces used for indentation of continued
expressions. The value must be no less than minus
`javascript-indent-level'."
  :type 'integer
  :group 'javascript)

(defcustom javascript-auto-indent-flag t
  "Automatic indentation with punctuation characters. If non-nil, the
current line is indented when certain punctuations are inserted."
  :type 'boolean
  :group 'javascript)


;; --- Keymap ---

(defvar javascript-mode-map nil 
  "Keymap used in JavaScript mode.")

(unless javascript-mode-map 
  (setq javascript-mode-map (make-sparse-keymap)))

(when javascript-auto-indent-flag
  (mapc (lambda (key) 
	    (define-key javascript-mode-map key 'javascript-insert-and-indent))
	'("{" "}" "(" ")" ":" ";" ",")))

(defun javascript-insert-and-indent (key)
  "Run command bound to key and indent current line. Runs the command
bound to KEY in the global keymap and indents the current line."
  (interactive (list (this-command-keys)))
  (call-interactively (lookup-key (current-global-map) key))
  (indent-according-to-mode))


;; --- Syntax Table And Parsing ---

(defvar javascript-mode-syntax-table
  (let ((table (make-syntax-table)))
    (c-populate-syntax-table table)
    (modify-syntax-entry ?$ "_" table)
    table)
  "Syntax table used in JavaScript mode.")

(defvar js-ident-as-word-syntax-table
  (let ((table (copy-syntax-table javascript-mode-syntax-table)))
    (modify-syntax-entry ?$ "w" table)
    (modify-syntax-entry ?_ "w" table)
    table)
  "Alternative syntax table used internally to simplify detection
  of identifiers and keywords and its boundaries.")


(defun js-re-search-forward-inner (regexp &optional bound count)
  "Auxiliary function for `js-re-search-forward'."
  (let ((parse)
        (saved-point (point-min)))
    (while (> count 0)
      (re-searc "transient" "try" "typeof" "var" "void" 
                "volatile" "while" "with") 'words)
  "Regular expression mefun js-end-of-do-while-loop-p ()
  "Returns non-nil if word after point is `while' of a do-while
statement, else re*$")))
    (forward-line -1))
  (when (not (bobp)) (forward-line 1)))


(defun js-forward-paragraph ()
  "Move foxcursion
        (save-restriction
          (narrow-to-region start end)
          (goto-char (point-min))
 