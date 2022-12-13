OUTPUT = Mentor_Handbook_2007-2008.pdf

all: $(OUTPUT)

$(OUTPUT): $(subst .pdf,.tex,$(OUTPUT)) $(wildcard *tex)
	pdflatex "$<"
# Only generating once will not generate the table of contents
	pdflatex "$<"

clean:
	$(RM) -v *~ *aux *log *log *out *idx *toc $(OUTPUT)
