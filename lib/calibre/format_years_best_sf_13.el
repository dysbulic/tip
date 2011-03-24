;; elisp file for reformatting ebook html files from The Year's Best SF 13
;;
;; M-x load-file format.el

;; Add doctype
(replace-string "<?xml version=\"1.0\"?>"
                (concat "<?xml version=\"1.0\"?>\n"
                        "<!DOCTYPE html PUBLIC\n"
                        " \"+//ISBN 0-9673008-1-9//DTD OEB 1.0.1 Document//EN\"\n"
                        " \"http://openebook.org/dtds/oeb-1.0.1/oebdoc101.dtd\">"))

;; change stylesheet
(replace-string "<link rel=\"stylesheet\" type=\"text/css\" href=\"../styles/pdlmsr.css\"/>"
                "<link rel=\"stylesheet\" href=\"style/book.css\" type=\"text/css\"/>")
(beginning-of-buffer)

;; unused namespaces
(replace-string " xmlns:svg=\"http://www.w3.org/2000/svg\"" "")
(replace-string " xmlns:xlink=\"http://www.w3.org/1999/xlink\"" "")

;; remove containers
(replace-regexp "<body>\n<div class=\"chapter\" id=\"\\(.*?\\)\">" "<body id=\"\\1\">")
(replace-string "<div class=\"chapterHead\">\n" "")
(replace-string "<div class=\"chapterBody\">\n" "")
(replace-string "</div>\n</div>\n</body>" "</body>")
(beginning-of-buffer)

(replace-regexp (concat "<h2 class=\"chapterTitle\"><span class=\"xrefInternal\">"
                        "<a href=\".*?\"><span class=\"bold\">\\(.*?\\)</span></a></span></h2>")
                "<h1>\\1</h1>")
(replace-regexp (concat "<h2 class=\"chapterAuthor\"><span class=\"xrefInternal\">"
                        "<a href=\".*?\"><span class=\"bold\"><span class=\"smallCaps\">"
                        "\\(.*?\\)</span></span></a></span></h2></div>")
                "<h2>by \\1</h2>\n")

(replace-regexp (concat "<p class=\"chapterOpenerText\"><span class=\"chapterOpenerFirstLetters\">"
                        "<span class=\"bold\">\\(.*?\\)</span></span>")
                "\n<hr/>\n\n<p class=\"first\"><span class=\"first-letter\">\\1</span>")
(beginning-of-buffer)

;; change classes
(replace-string "class=\"paraNoIndent\"" "class=\"noindent\"")
(beginning-of-buffer)
(replace-string " class=\"para\"" "")
(beginning-of-buffer)
(replace-string "class=\"paraCenter\"" "class=\"center\"")
(beginning-of-buffer)
(replace-string "class=\"smallCaps\"" "class=\"small-caps\"")
(beginning-of-buffer)

;; Use <i> tags
(replace-regexp "<span class=\"italic\">\\(.*?\\)</span>" "<i>\\1</i>")
(beginning-of-buffer)

;; space between paragraphs
(replace-regexp "\\(br/\\|/p\\)>\n?<\\(br\\|p\\)" "\\1>\n\n<\\2")
(beginning-of-buffer)

;; use breaks
(replace-string "<p class=\"spaceBreak\">&#160;</p>" "<br/>")
(beginning-of-buffer)

;; HTML-based smart quotes
(replace-regexp "\\(“\\|&#8220;\\)" "<q>")
(beginning-of-buffer)
(replace-string "<p>\"" "<p><q>")
(beginning-of-buffer)
(replace-regexp "\\(”\\|&#8221;\\)" "</q>")
(beginning-of-buffer)
(replace-string "\"</p>" "</q></p>")
(beginning-of-buffer)

;; Horizontal ellipsis
(replace-string "..." "&hellip;")
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

