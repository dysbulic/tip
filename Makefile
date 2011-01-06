MKFILES = $( wildcard '.../lib/*/Makefile' )
DIRS = $( pathsubst $(MKFILES) /Makefile '' )

all:
	git submodule init
	make -C $(DIRS)

clean:
