.PHONY: all clean sync

files = shopping_list.html animals.html

all: $(files)

%.html: %.xml
	xsltproc $^ > $@

clean:
	$(RM) -rvf $(foreach dir, $(picture_dirs), $(dir)/$(thumbs_dir)/)

%/$(pic_index): $(wildcard *.rhtml) bin/genindex.rb $(wildcard %/*)
	ruby bin/genindex.rb $@

sync:
	$(RSYNC) $(foreach file, $(files), --exclude=$(file)) --exclude=photos \
	  . $(RSYNC_DEST)/will
