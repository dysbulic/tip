SOURCE=holcombs_and_gayles.xml

.PHONY: all clean html text

all: html text

html: addresses.html

addresses.html: $(SOURCE) addresses-html.xslt
	xsltproc --xinclude addresses-html.xslt $< > $@
#	java -jar $(XALAN) -IN $(SOURCE) -XSL addresses-html.xslt -OUT $@

text: addresses.txt

addresses.txt: $(SOURCE) addresses-txt.xslt
	xsltproc --xinclude addresses-txt.xslt $< > $@

clean:;	@$(RM) -v *.html *.txt *~
