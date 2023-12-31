PNGS = 007.png 009.png

all: $(PNGS)

%.png: %.svg
	batik-rasterizer.sh -w 91 -h 22 -d "$@" "$<"

clean:
	$(RM) *~ $(PNGS)
