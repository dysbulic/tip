# Firefox doesn't handle real <use> elements, so they have to be faked
# with xIncludes

PRODUCTS = ${patsubst %.template.svg,%.svg,${wildcard *.template.svg}}

all: $(PRODUCTS)

clean:
	$(RM) $(PRODUCTS) *~

%.svg: %.template.svg
	xmllint --xinclude "$<" > "$@"
