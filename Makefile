MKFILES = $( wildcard '.../lib/*/Makefile' )
DIRS = $( pathsubst $(MKFILES) /Makefile '' )

all:
	git submodules init
	make -C $(DIRS)

clean:
