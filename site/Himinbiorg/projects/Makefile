IMAGES = ${patsubst %.svg,%.png,${wildcard *.svg}}
SIZE = 125

all: $(IMAGES)

%.png: %.svg
	~/bin/batik-rasterizer -h $(SIZE) -w $(SIZE) -d $@ $<

stripe.png: stripe.svg
	~/bin/batik-rasterizer -h 75 -w 75 -d $@ $<

clean:
	$(RM) $(IMAGES)
