PRODUCTS = book.embedded-style.svg book.external-style.svg book.linked-style.svg book.embedded-washout.png book.external-washout.png book.linked-washout.png
BOOK_CSS = book.group.css

all: $(PRODUCTS)

clean:
	$(RM) $(PRODUCTS) $(BOOK_CSS) *~

%-washout.png: %-style.svg
	java -jar ~/bin/batik-1.6/batik-rasterizer.jar -cssUser file://$$(pwd)/washout.css -d "$@" -w 210 -h 155 "$<"

%.embedded-style.svg: %.svg
	xsltproc group_inline_styles.xslt "$<" > "$@"

%.external-style.svg: %.svg
	xsltproc -stringparam "style_filename" "$(BOOK_CSS)" group_inline_styles.xslt "$<" > "$@"

%.linked-style.svg: %.svg
	xsltproc -stringparam washout true group_inline_styles.xslt "$<" > "$@"
