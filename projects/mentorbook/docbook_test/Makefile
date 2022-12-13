mentor_hadbook.pdf: mentor_handbook.fo
	fop "$<" "$@"

%.fo: %.docbook $(wildcard recipes/*.docbook)
	xsltproc --xinclude docbook-xsl-1.73.2/fo/docbook.xsl "$<" > "$@"
