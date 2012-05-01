all: godbbs.html

%.html: %.xml bbs-html.xslt
	xsltproc bbs-html.xslt $< > $@
