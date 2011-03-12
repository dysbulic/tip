;; elisp file for reformatting ebook html files from Dangerous Visions
;;
;; M-x load-file format_dv.el

;; Add doctype
(insert "<!DOCTYPE html PUBLIC
 \"+//ISBN 0-9673008-1-9//DTD OEB 1.0.1 Document//EN\"
 \"http://openebook.org/dtds/oeb-1.0.1/oebdoc101.dtd\">\n")

;; separate lines
(replace-regexp "><\\(/?head\\|title\\|meta\\|body\\|link\\|/html\\)" ">\n<\\1")
(beginning-of-buffer)

(replace-regexp "<body><br/><p>\\(.*?\\)</p><center><b>\\(.*?\\)</b></center><p>\\(.*?\\)</p>"
                "<body>\n\\1\n<h1>\\2</h1>\n<h2>\\3</h2>\n\n")
(beginning-of-buffer)

(replace-regexp "\\(br/\\|/p\\)><\\(br\\|p\\)" "\\1>\n\n<\\2")
(beginning-of-buffer)
(goto-line 1)

;; HTML-based smart quotes
(replace-string "“" "<q>")
(beginning-of-buffer)
(replace-string "<p>\"" "<p><q>")
(beginning-of-buffer)
(replace-string "”" "</q>")
(beginning-of-buffer)
(replace-string "\"</p>" "</q></p>")
(beginning-of-buffer)

;; Horizontal ellipsis
(replace-string "..." "&hellip;")
(beginning-of-buffer)

;; Replace title
(search-forward-regexp "<h1>\\(.*?\\)</h1>")
(setq title (capitalize (match-string 1)))
(beginning-of-buffer)
(replace-regexp "<title>.*?</title>" (concat "<title>" title "</title>"))
(replace-regexp "<h1>.*?</h1>" (concat "<h1>" title "</h1>"))

;; Add author meta tag
(search-forward-regexp "<h2>by \\(.*?\\)</h2>")
(setq author (capitalize (match-string 1)))
(beginning-of-buffer)
(replace-string "<head>" (concat "<head>\n<meta name=\"author\" content=\"" author "\"/>"))

;; Add stylesheet
(replace-regexp "<link .*?rel=\"stylesheet\"[^>]*>\n" "")
(insert "<link rel=\"stylesheet\" href=\"style/book.css\" type=\"text/css\"/>\n")

(replace-string "<p><a href=\"Ellison-DVisions_split_1.html#toc\">[Back to Table of Contents]</a></p>\n\n" "")
(beginning-of-buffer)
(replace-regexp "<p align=\"CENTER\" class=\"pba\" id=\"calibre_pb_[0-9]+\"/>" "")
(beginning-of-buffer)

(replace-string "<p>Afterword:</p>" "<hr/>\n\n<p>Afterword:</p>")
(beginning-of-buffer)

;; Speed manual processing of continued quotes
(defun complete-quote () 
  "Complete a continued quote tag."
  (interactive)
  (nxml-finish-element)
  (search-backward "<q")
  (forward-char 2)
  (insert " class=\"continued\"")
  (rng-next-error))
(global-set-key "\M-2" 'complete-quote)

(rng-first-error)

