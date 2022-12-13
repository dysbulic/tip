IMAGES = honir_header.png honir_header.jpg ${patsubst %.svg,%.svgz,${wildcard *.svg}}
BATIKOPTS = -h 200 -w 765 -onload

all: $(IMAGES)

clean:; $(RM) $(IMAGES)

%.png: %.svg
	batik-rasterizer.sh $(BATIKOPTS) -d "$@" "$<"

%.jpg: %.svg
	batik-rasterizer.sh $(BATIKOPTS) -d "$@" -m image/jpeg -q .85 "$<"

%svgz: %svg
	gzip -c9 "$<" > "$@"
