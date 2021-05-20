;; $Id: .emacs,v 1.62 2004/08/13 20:30:19 speedblue Exp $

(setq load-path (nconc '( "~/emacs-config/"
			  "~/emacs-config/tuareg/"
			  "~/emacs-config/sieve/") load-path))

(defvar c-mode-map)

(require 'tiger)
(when (>= emacs-major-version 21)
  (load "~/emacs-config/sieve/sieve"))
(add-to-list 'auto-mode-alist '("\\.tig$" . tiger-mode))
(add-to-list 'auto-mode-alist '("\\.css$" . html-mode))
(add-to-list 'auto-mode-alist '("\\.cfm$" . html-mode))
(when (>= emacs-major-version 21)
  (add-to-list 'auto-mode-alist '("\\.siv$" . sieve-mode))
  (add-to-list 'auto-mode-alist '("\\.sieve$" . sieve-mode)))

;;auto-template
(setq auto-template-dir "~/emacs-config/templates/")
(require 'auto-template "~/emacs-config/auto-template.el")


;;empeche creation de nouvelles ligne apres EOF
(setq next-line-add-newlines		nil)

;;mode d'indentation gnu pour java uniquement
(setq
 c-mode-hook '(lambda ()
		(auto-fill-mode 1))
 c++-mode-hook '(lambda ()
		  (auto-fill-mode 1))
 text-mode-hook '(lambda ()
		   (auto-fill-mode 1))
 java-mode-hook '(lambda ()
		   (c-set-style "gnu")))

;;editer les fichiers caml en touareg mode
(setq load-path (cons "tuareg" load-path))
(setq auto-mode-alist (cons '("\\.ml\\w?" . tuareg-mode) auto-mode-alist))
(autoload 'tuareg-mode "tuareg" "Major mode for editing Caml code" t)
(autoload 'camldebug "camldebug" "Run the Caml debugger" t)
(if (and (boundp 'window-system) window-system)
    (if (string-match "XEmacs" emacs-version)
	(require 'sym-lock)
      (require 'font-lock)))

;;editer les fichiers eiffel
(add-to-list 'auto-mode-alist '("\\.e\\'" . eiffel-mode))
(autoload 'eiffel-mode "eiffel-mode" "Major mode for Eiffel programs" t)

;;editer les fichiers ada
(add-to-list 'auto-mode-alist '("\\.adb\\'" . ada-mode))
(add-to-list 'auto-mode-alist '("\\.ads\\'" . ada-mode))
(autoload 'ada-mode "ada-mode" "Major mode for Ada programs" t)

;;enleve la barre de menu .. alt-x , c mieux :)
(menu-bar-mode nil)

;;enleve la scroll bar ... c encore mieux :)
(set-scroll-bar-mode nil)

;;met correctement la coloration de la selection
(setq transient-mark-mode t)

;;ne coupe pas les ligne avec des $
(set-variable 'truncate-partial-width-windows nil)

;;permet la completion
;;(require 'completion)
;;(initialize-completions)

;; Completion des noms de fichiers/repertoire
(define-key global-map (read-kbd-macro "M-\\") 'hippie-expand)
(add-hook 'message-setup-hook 'mail-abbrevs-setup)


;; ajoutez Time-stamp: <> ou Time-stamp: " " n'importe ou dans les 8 premieres
;; lignes d'un fichier, la date sera mise a jour automatiquement
;; a chaque sauvegarde
(add-hook 'write-file-hooks 'time-stamp)
(setq time-stamp-active t)
(setq time-stamp-format "%02d-%3b-%:y %02H:%02M:%02S %u")

;;font lock
(setq font-lock-use-default-fonts nil)
(setq font-lock-use-default-colors nil)

(require 'font-lock)
(global-font-lock-mode nil)


;;la fonction devenv permet d'avoir une "ide"
;;avec la speedbar, la fenetre de compil, etc ..
;; ajoutez la ligne : alias ide="emacs --geometry 145x70+155+0 -f devenv"
;;au .bashrc
(defun devenv()
  (interactive)
  (split-window-vertically 55)
  (find-file "ChangeLog")
  (split-window-horizontally)
  (other-window 2)
  (split-window-horizontally)
  (other-window 1)
  (ansi-term "/bin/sh")
  (other-window 1)
  (find-file "main.cc")
  ;;on active l'auto-scrolling ohorizontal:
  (hscroll-global-mode)
  ;;on lance la speedar
  (speedbar)
  )


;;permet de mettre en couleur les types persos:
(require 'ctypes)
(setq ctypes-write-types-at-exit t)
(ctypes-read-file nil nil t t)
(ctypes-auto-parse-mode 1)

;;scanne le dossier courant a la recherche de nouveau type
(lambda()
  (interactive)
  (ctypes-dir "."))


;;activation de cparse: c en anglais, mais tt est dit ..
(require 'cparse)
;;(setq load-path (cons "~/cparse" load-path))
(autoload 'cparse-listparts "cparse"
  "List all the parts in the current buffer in another buffer." t)
(autoload 'cparse-open-on-line "cparse"
  "Grab the object under the cursor and find it's definition." t)
(autoload 'cpc-insert-function-comment "cpcomment"
  "Starting at pnt, look for a function definition.  If the definition
exists, parse for the name, else, fill everything in as null.  Then
insert the variable cpc-function-comment, and fill in the %s with the
parts determined.
If the comment already exists, this function will try to update only
the HISTORY part." t)
(autoload 'cpc-insert-new-file-header "cpcomment"
  "Insert a new comment describing this function based on the format
in the variable cpc-file-comment.  It is a string with sformat tokens
for major parts.  Optional HEADER is the header to use for the cpr
token" t)
(autoload 'cpr-store-in-header "cproto"
  "Grab the header from current position, load in the header file, and
make any needed substitutions to update the header file.  If the
function is static, then create needed stuff in this c file for the
prototype." t)

;; encore kkes key binding pour utiliser cparse..
(defun cparse-setup-keybindings ()
  (define-key c-mode-map "\C-cp" 'cparse-listparts)
  (define-key c-mode-map "\C-co" 'cparse-open-on-line)
  (define-key c-mode-map "\C-cf" 'cpc-insert-new-file-header)
  (define-key c-mode-map "\C-c\C-h" 'cpr-store-in-header)
  (define-key c-mode-map "\C-c\C-d" 'cpr-insert-function-comment)
  )

(add-hook 'c-mode-common-hook 'cparse-setup-keybindings)


;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;; slow scrolling
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

(defvar slow-scroll-mode 0)

(defun toggle-slow-scroll-mode (&optional arg)
  "Toggle slow scroll mode at each call of this function.
If slow-scroll-mode is 1, the cursor stays on current line after each scroll; else not."
  (interactive "P")
  (if (= slow-scroll-mode 0)
      (setq slow-scroll-mode 1)
    (setq slow-scroll-mode 0)))

(defun scroll-up-slowly (&optional arg)
  "Scroll text of current window upward ARG lines; or one line if no ARG.
This depend of slow scroll mode (keeping cursor on current line or not).
When calling from a program, supply a number as argument or nil."
  (interactive "P")
  (progn (if arg
	     (scroll-up arg)
	   (scroll-up 1))
	 (if (= slow-scroll-mode 1)
	     (next-line 1))))

(defun do_insert_time ()
  (interactive)
  (insert-string (current-time-string)))

(defun scroll-down-slowly (&optional arg)
  "Scroll text of current window downward ARG lines; or one line if no ARG.
This depend of slow scroll mode (keeping cursor on current line or not).
When calling from a program, supply a number as argument or nil."
  (interactive "P")
  (progn (if arg
	     (scroll-down arg)
	   (scroll-down 1))
	 (if (= slow-scroll-mode 1)
	     (previous-line 1))))

(set-variable 'slow-scroll-mode 1)


;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;; versions control
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
(setq vc-handle-cvs nil)
(set-variable 'version-control t)
(set-variable 'kept-old-versions 5)
(set-variable 'kept-new-versions 20)
(set-variable 'delete-old-versions t)
(set-variable 'auto-save-interval 50)
(set-variable 'auto-save-timeout 10)


;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;; mouse wheel
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

(defun up-slightly () (interactive) (scroll-up 5))
(defun down-slightly () (interactive) (scroll-down 5))
(global-set-key [mouse-4] 'down-slightly)
(global-set-key [mouse-5] 'up-slightly)
(defun up-one () (interactive)
  (scroll-up 1))
(defun down-one () (interactive) (scroll-down 1))
(global-set-key [S-mouse-4] 'down-one)
(global-set-key [S-mouse-5] 'up-one)
(defun up-a-lot () (interactive)
  (scroll-up))
(defun down-a-lot () (interactive) (scroll-down))
(global-set-key [C-mouse-4] 'down-a-lot)
(global-set-key [C-mouse-5] 'up-a-lot)


;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;; colors
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

(custom-set-faces
  ;; custom-set-faces was added by Custom -- don't edit or cut/paste it!
  ;; Your init file should contain only one such instance.
 '(background "blue")
 '(font-lock-builtin-face ((((class color) (background dark)) (:foreground "Turquoise"))))
 '(font-lock-comment-face ((t (:foreground "MediumAquamarine"))))
 '(font-lock-constant-face ((((class color) (background dark)) (:bold t :foreground "DarkOrchid"))))
 '(font-lock-doc-string-face ((t (:foreground "green2"))))
 '(font-lock-function-name-face ((t (:foreground "SkyBlue"))))
 '(font-lock-keyword-face ((t (:bold t :foreground "CornflowerBlue"))))
 '(font-lock-preprocessor-face ((t (:italic nil :foreground "CornFlowerBlue"))))
 '(font-lock-reference-face ((t (:foreground "DodgerBlue"))))
 '(font-lock-string-face ((t (:foreground "LimeGreen"))))
 '(font-lock-type-face ((t (:foreground "#9290ff"))))
 '(font-lock-variable-name-face ((t (:foreground "PaleGreen"))))
 '(font-lock-warning-face ((((class color) (background dark)) (:foreground "yellow" :background "red"))))
 '(highlight ((t (:background "CornflowerBlue"))))
 '(list-mode-item-selected ((t (:background "gold"))))
 '(makefile-space-face ((t (:background "wheat"))))
 '(modeline ((t (:background "Navy"))))
 '(paren-match ((t (:background "darkseagreen4"))))
 '(region ((t (:background "DarkSlateBlue"))))
 '(show-paren-match-face ((t (:foreground "black" :background "wheat"))))
 '(show-paren-mismatch-face ((((class color)) (:foreground "white" :background "red"))))
 '(speedbar-button-face ((((class color) (background dark)) (:foreground "green4"))))
 '(speedbar-directory-face ((((class color) (background dark)) (:foreground "khaki"))))
 '(speedbar-file-face ((((class color) (background dark)) (:foreground "cyan"))))
 '(speedbar-tag-face ((((class color) (background dark)) (:foreground "Springgreen"))))
 '(vhdl-speedbar-architecture-selected-face ((((class color) (background dark)) (:underline t :foreground "Blue"))))
 '(vhdl-speedbar-entity-face ((((class color) (background dark)) (:foreground "darkGreen"))))
 '(vhdl-speedbar-entity-selected-face ((((class color) (background dark)) (:underline t :foreground "darkGreen"))))
 '(vhdl-speedbar-package-face ((((class color) (background dark)) (:foreground "black"))))
 '(vhdl-speedbar-package-selected-face ((((class color) (background dark)) (:underline t :foreground "black"))))
 '(widget-field-face ((((class grayscale color) (background light)) (:background "DarkBlue")))))


;; parseur mulot
;;(add-hook 'c-mode-hook '(lambda ()
;;(autoload 'norme      "~/emacs-config/norme.el" nil t)))

;;(if (file-exists-p "~/emacs-config/ocaml.emacs")
;;    (load-file "~/emacs-config/ocaml.emacs")
;;)
;;auto-fill-mode (70 colonnes)

(custom-set-variables
  ;; custom-set-variables was added by Custom -- don't edit or cut/paste it!
  ;; Your init file should contain only one such instance.
 '(speedbar-directory-unshown-regexp "^\\(CVS\\|RCS\\|SCCS\\|.deps\\)\\'")
 '(speedbar-frame-parameters (quote ((minibuffer) (width . 20) (border-width . 0) (menu-bar-lines . 0) (tool-bar-lines . 0) (unsplittable . t) (set-background-color "black"))))
 '(speedbar-supported-extension-expressions (quote (".[ch]\\(\\+\\+\\|pp\\|c\\|h\\|xx\\)?" ".tex\\(i\\(nfo\\)?\\)?" ".el" ".emacs" ".l" ".lsp" ".p" ".java" ".f\\(90\\|77\\|or\\)?" ".ad*" ".p[lm]" ".tcl" ".m" ".scm" ".pm" ".py" ".g" ".s?html" "[Mm]akefile\\(\\.in\\|am\\)?" "configure.ac" ".ml*" ".tig" ".\\(ll\\|yy\\)"))))

;;Set windows title
(setq frame-title-format '(buffer-file-name "HappyEmacs: %b (%f)" "HappyEmacs: %b"))

;; Retour a la ligne

;;   ne fonctionne que si on ne fait pas de split horizontal
(setq truncate-lines nil)
;; fonctionne avec le split horizontal
(setq truncate-partial-width-windows nil)

;; Ajout de l'heure ds la barre et d'un msg qd il y a des mails

(setq display-time-string-forms
      ;;   '((format "[%s/%s/%s]-[%s:%s] " day month year 24-hours minutes )
      '((format "[%s/%s]-[%s:%s] " day month 24-hours minutes )))
;; (if mail "==Mail==" load)))

;; permet d'ouvrir les gz a la volee
(auto-compression-mode t)

;; completion du nom du buffer a selectionner en tapant une partie du nom
;; seulement et pas uniquement un prefixe
(require 'iswitchb)
(iswitchb-default-keybindings)

;; multi-mode used for JSP & PHP
(autoload 'multi-mode
  "multi-mode"
  "Allowing multiple major modes in a buffer."
  t)

;; multi-mode used for CSS
(autoload 'css-mode  "css-mode"  t)
(setq auto-mode-alist
      (cons '("\\.css$" . css-mode)
	    auto-mode-alist))


;; JSP mode
(defun jsp-mode () (interactive)
  (multi-mode 1
	      'html-mode
	      ;;your choice of modes for java and html
	      ;;'("<%" java-mode)
	      '("<%" java-mode)
	      '("%>" html-mode)))

(setq auto-mode-alist
      (cons '("\\.jsp$" . jsp-mode)
	    auto-mode-alist))


(defun c-php-mode()
  (font-lock-add-keywords 'c++-mode
			  '("[$*]{?\\(\\sw+\\)" 1
			    font-lock-variable-name-face))
  (c++-mode)
)


;; PHP mode
(defun php-mode () (interactive)
  (multi-mode 1
	      'html-mode
	      '("<?", c-php-mode)
	      '("?>", html-mode)))

(setq auto-mode-alist
      (cons '("\\.php$" . php-mode)
	    auto-mode-alist))

;; Marmot mode
(defun marmot-mode () (interactive)
  (multi-mode 1
	      'html-mode
	      '("%{", c++-mode)
	      '("%}", text-mode)))

(setq auto-mode-alist
      (cons '("\\.mm$" . marmot-mode)
 	    auto-mode-alist))


(put 'downcase-region 'disabled nil)

(put 'upcase-region 'disabled nil)

(put 'narrow-to-region 'disabled nil)

;;; Autoload of the configuration

(autoload 'reindent-file "~/emacs-config/reindent.el")
(autoload 'setget "~/emacs-config/interface.el")

;; loads post style with mutt ou slrn
(setq post-mail-message "mutt-[A-z]+-[0-9]+-[0-9]+\\'")
(load "~/emacs-config/post.el")
(load "~/emacs-config/counter.el") ; M-x counter macro... very usefull
(add-hook 'post-mode-hook
          '(lambda () (setq fill-column 72)))
	  (require 'post)
	  (setq post-kill-quoted-sig nil)

;; call aspell instead of ispell
(setq-default ispell-program-name "aspell")
(setq ispell-dictionary "francais")

;; define a dos2unix function
(defun dos2unix (buffer)
  "Automate M-% C-q C-m RET C-q C-j RET"
  (interactive "b")
  (goto-char (point-min))
  (while (search-forward (string ?\C-m) nil t)
    (replace-match (string ?\C-j) nil t)))

;; fix color problems on some emacs configuration
(set-face-foreground 'modeline "White")

(setq initial-frame-alist nil)
(add-to-list 'default-frame-alist
      '(background-color . "Black"))
(add-to-list 'default-frame-alist
      '(foreground-color . "white"))
(add-to-list 'default-frame-alist
      '(cursor-color . "Steelblue"))

;; load the user preferences, or the defaults
(if (file-exists-p "~/.happyemacs")
    (load-file "~/.happyemacs")
  (load-file "~/emacs-config/.happyemacs")
  )
