.PHONY: all clean

all: Player.swf PlayerDebug.swf $(wildcard *.swf)

%.swf: %.as 
	$(wildcard ~/mtasc/mtasc) -cp $(wildcard ~/mtasc/std/) -header 600:400:3 -swf $@ -main $^

%Debug.swf: %.as
	$(wildcard ~/mtasc/mtasc) -cp $(wildcard ~/mtasc/std/) -header 600:400:3 -trace $*.customTrace -swf $@ -main $^
