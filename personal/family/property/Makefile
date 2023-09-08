PNGS = tl_corner.png br_corner.png bottom_shadow.png top_shadow.png riddlecreek.png

all: $(PNGS)

tl_corner.png: tl_corner.svg
	batik-rasterizer.sh -w 624 -h 365 -d "$@" "$<"

br_corner.png: tl_corner.png
	convert -rotate 180 $< $@

%_shadow.png: %_shadow.svg
	batik-rasterizer.sh -w 10 -h 45 -d "$@" "$<"

riddlecreek.png: riddlecreek.svg
	batik-rasterizer.sh -w 500 -h 245 -d "$@" "$<"

clean:
	$(RM) *~ $(PNGS)
