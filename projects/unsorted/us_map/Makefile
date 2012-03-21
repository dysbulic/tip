MAPS = ${patsubst %.svg,%.html,${wildcard *.svg}}
STYLES = ${patsubst %.svg,%.css,${wildcard *.svg}}
IMAGES = ${patsubst %.svg,%_images,${wildcard *.svg}}

all: $(MAPS)

%.html: %.svg
	python process_map.py "$<"

clean:; $(RM) $(MAPS) $(STYLES) *.pyc *~

distclean: clean
	$(RM) -r $(IMAGES)
