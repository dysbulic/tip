ICONS = folder_open.png folder_closed.png file.png loading.png
THUMBNAILS = ${patsubst %.png,%.small.png,$(ICONS)}
DIMENSIONS = "15x15"

.PHONY: all clean

all: $(THUMBNAILS)

%.small.png: %.png
	convert -resize $(DIMENSIONS) $< $@

clean:
	$(RM) $(THUMBNAILS) *~
