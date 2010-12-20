OBJS = ${patsubst %.c,%,${wildcard *.c}} ${patsubst %.cpp,%,${wildcard *.cpp}} ${patsubst %.java,%.class,${wildcard *.java}} out.gif
LIBS = jquery.dump.js animator.js

all: $(OBJS) $(LIBS)

%: %.c
	cc -lm -Wall -o "$@" -O3 "$<"

%.class: %.java
	javac "$<"

out.gif: web_bug.py
	python $< $@

jquery:
	git clone git://github.com/wholcomb/jquery.git
	make -C jquery/

clean:
	$(RM) *~ $(OBJS) *.class

%.pro: %.cpp
	qmake -project -nopwd -o $@ $<

%_Makefile: %.pro
	qmake -o $@ $<

qt_%: qt_%_Makefile
	make --file=$<

%.png: %.svg
	batik-rasterizer.sh -h 100 -w 100 -onload -d "$@" "$<"

jquery.%.js:
	curl --output $@ http://plugins.jquery.com/files/$@.txt

animator.js:
	curl --output $@ http://berniecode.com/include/animator.js
