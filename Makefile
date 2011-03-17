MKFILES = $( wildcard '.../lib/*/Makefile' )
DIRS = $( pathsubst $(MKFILES) /Makefile '' )

all:
	git submodule init
	make -C $(DIRS)

clean:
	find -name *~ -print0 | xargs -0 rm -v
