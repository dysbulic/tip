;; elisp file for reformatting ebook html files from The Year's Best SF 13
;;
;; M-x load-file format.el

;; Add doctype
(replace-regexp "<\\?xml.*?\\?>\n?"
                (concat "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
                        "<!DOCTYPE html PUBLIC\n"
                        " \"+//ISBN 0-9673008-1-9//DTD OEB 1.0.1 Document//EN\"\n"
                        " \"http://openebook.org/dtds/oeb-1.0.1/oebdoc101.dtd\">\n"))

;; separate lines
(replace-regexp "><\\(/?head\\|title\\|meta\\|/?body\\|link\\|/html\\)" ">\n<\\1")
(beginning-of-buffer)

;; remove unused links
(replace-regexp "<meta name=\"Adept.resource\".*?/>\n?" "")
(replace-regexp "<a id=\"[^\"]*\" shape=\"rect\"/>" "")
(beginning-of-buffer)

;; remove now empty divs
(replace-string "<div style=\"display:none;\"></div>" "")
(beginning-of-buffer)

;; change stylesheet
(replace-regexp "<link\\( +\\(rel=\"stylesheet\"\\|type=\"text/css\"\\|href=\".*\.css\"\\)\\)\\{3\\} */>"
                "<link rel=\"stylesheet\" href=\"style/book.css\" type=\"text/css\"/>")
(beginning-of-buffer)

;; unused namespaces
(replace-string " xmlns:svg=\"http://www.w3.org/2000/svg\"" "")
(replace-string " xmlns:xlink=\"http://www.w3.org/1999/xlink\"" "")
(replace-string " xmlns:ops=\"http://www.idpf.org/2007/ops\"" "")
(replace-string " xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"" "")

;; separate header
(replace-string "<body><" "<body>\n<")
(beginning-of-buffer)

;; Remove image rule
(replace-regexp "<p class=\"center\"><img alt=\"\" src=\"images/[0-9]*_line.png\"/></p>" "")
(replace-regexp "<p class=\"center\"><img alt=\"\" src=\"images/[0-9]*_line[0-9]+.png\"/></p>" "<hr/>")

;; remove unnecessary paragraph style
(replace-string "<p class=\"indent\">" "<p>")
(replace-string "<p class=\"nonindent\">" "<p class=\"noindent\">")
(beginning-of-buffer)

;; move subtitle
(search-forward-regexp "</h1><h1 class=\"chapter1\">\\(.*?\\)</h1>")
(setq author (capitalize (match-string 1)))
(beginning-of-buffer)
(replace-regexp "</h1><h1 class=\"chapter1\">\\(.*?\\)</h1>"
                (concat "</h1>\n<h2>by " author "</h2>"))

;; add author meta
(beginning-of-buffer)
(replace-string "<head>" (concat "<head>\n<meta name=\"author\" content=\"" author "\"/>"))

;; Replace title
(search-forward-regexp "<h1.*?>\\(.*?\\)</h1>")
(setq title (match-string 1))
(beginning-of-buffer)
(replace-regexp "<title>.*?</title>" (concat "<title>" title "</title>"))

;; set header
(re-search-forward "</h1>\n*<div class=\"block\"><p class=\"nonindent\">" nil t)
(replace-match "</h1>\n\n<section class=\"story introduction\">\n<h2>Introduction</h2>\n\n<p class=\"first\">")
(re-search-forward "</div><p class=\"nonindent\"><span class=\"big\">" nil t)
(replace-match "\n</section>\n\n<p class=\"first\"><span class=\"first-letter\">")
(beginning-of-buffer)

;; set sections
(replace-regexp "</div><h1 class=\"section\">\\(.*?\\)</h1><p class=\"nonindent\"><span class=\"big\">"
                "</section>\n\n<section>\n<h2>\\1</h2>\n\n<p class=\"first\"><span class=\"first-letter\">")
(replace-regexp "</p><h2 class=\"section\">\\(.*?\\)</h2><p class=\"nonindent\">"
                "</p>\n</section>\n\n<section>\n<h2>\\1</h2>\n\n\<p class=\"first\">")
(beginning-of-buffer)

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
(replace-regexp "\\(br/\\|/p\\)>\n?<\\(/section\\)" "\\1>\n<\\2")
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

;; Speed manual processing of continued quotes
(defun fix-citations () 
  "Translate <em>s to <cite>s."
  (interactive)
  (query-replace-regexp "<em>\\(.*?\\)</em>" "<cite>\\1</cite>"))
(global-set-key "\M-3" 'fix-citations)

(rng-first-error)

