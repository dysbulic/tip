THERMS = red_thermometer.png fullred_thermometer.png blue_thermometer.png fullblue_thermometer.png

all: $(THERMS)

clean:; rm $(THERMS)

%.png: %.orig.trimmed.png
	mv $< $@

%.orig.trimmed.png: %.orig.png
	../us_map/trim_image.py $<

%.orig.png: %.svg
	batik-rasterizer.sh -d $@ -h 480 -onload $<

%thermometer.svg:
	ln -s ../moneymeter/thermometer.svg $@
