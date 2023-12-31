.PHONY: all clean

empty:=
space:=$(empty) $(empty)
classpath := $(subst $(space),:,$(wildcard ../quarterly_report/fop/*.jar))
FOP = java -classpath $(classpath) org.apache.fop.apps.Fop
FOP = fop

all: allpdf allhtml

allpdf: $(foreach pdf, $(wildcard recipes/*.rml), $(pdf:recipes/%.rml=pdf/%.pdf)) pdf/cookbook.pdf

allhtml: $(foreach html, $(wildcard recipes/*.rml), $(html:recipes/%.rml=html/%.html))

pdf/cookbook.pdf: cookbook.docbook cookbook.xslt rml-fo.xslt fo pdf
	xsltproc --xinclude cookbook.docbook > fo/cookbook.fo
	fop fo/cookbook.fo pdf/cookbook.pdf

pdf/%.pdf: recipes/%.rml rml-fo.xslt fo pdf
	xsltproc --xinclude rml-fo.xslt $< > fo/$*.fo
	$(FOP) fo/$*.fo $@

html/%.html: recipes/%.rml rml-xhtml.xslt html
	xsltproc --xinclude rml-xhtml.xslt $< > html/$*.html

fo:
	mkdir -v fo

pdf:
	mkdir -v pdf

html:
	mkdir -v html

clean:
	@find -name "*~" -or -name ".*~" | xargs $(RM) -v
	$(RM) -v fo/*.fo pdf/*.pdf html/*.html
	rmdir fo/ pdf/ html/
