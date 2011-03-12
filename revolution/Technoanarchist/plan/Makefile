IMAGES = ${patsubst %.svg,%.svgz,${wildcard *_paths.svg}} ${patsubst %.svg,%.jpg,${wildcard *_paths.svg}}
HEADER_SVGS = ${wildcard *_active.svg} ${wildcard *_inactive.svg} ${patsubst %_active.svg,%.svg,${wildcard *_active.svg}}
HEADERS = ${patsubst %.svg,%.png,$(HEADER_SVGS)}

all: index.html header.css $(HEADERS)

header.css: header_images.xslt navigation_header.svg
	xsltproc header_images.xslt navigation_header.svg > "$@"

%.svgz: %.svg
	cat "$<" | gzip -9 > "$@"

%.png: %.svg
	batik-rasterizer.sh -w 150 -h 140 -bg 0.255.255.255 -d "$@" "$<"

background.png: background.svg
	batik-rasterizer.sh -w 933 -h 280 -bg 0.255.255.255 -d "$@" "$<"

title_text.png: title_text.svg
	batik-rasterizer.sh -w 700 -h 51 -bg 0.255.255.255 -d "$@" "$<"

%.jpg: %.png
	convert "$<" "$@"

index.html: plan.docbook plan2site.xslt
	xsltproc plan2site.xslt plan.docbook > "$@"

clean:
	$(RM) -v *~ $(OUTPUTS) header.css $(HEADERS) $(HEADER_SVGS)
