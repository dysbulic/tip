APP = tip
SKINDIR = src/chrome/skin/classic
IMAGES = $(SKINDIR)/dreidel_logo.18x18.png

all: images ${APP}.xpi;

src/chrome/${APP}.jar:
	cd src/chrome && jar cf $(notdir $@) *

${APP}.xpi: src/chrome/${APP}.jar
	cd src && zip -r ../$@ . -x .svn/\* -x */.svn/\*

images: $(IMAGES);

%.png: ../dreidel_logo.svg
	batik-rasterizer.sh -h 18 -w 18 -d $@ $<

clean:
	$(RM) *~ src/chrome/${APP}.jar ${APP}.xpi $(IMAGES)
