all: $(foreach xhtml, $(wildcard homework_*.xhtml), $(xhtml:%.xhtml=%_solutions.pdf)) midterm_solutions.pdf

%_solutions.pdf: %.R
	R --no-save < "$<"

midterm-tukey.svg: midterm.R
	R --no-save --svg < "$<"
	xsltproc ../../inkscape_washout/set_100%_viewbox.xslt "$@" > "$@.100%"
	mv "$@.100%" "$@"
