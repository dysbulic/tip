OBJS=${patsubst %.c,%,${wildcard *.c}} ${patsubst %.cpp,%,${wildcard *.cpp}} ${patsubst %.java,%.class,${wildcard *.java}} out.gif

all: $(OBJS)

%: %.c
	cc -lm -Wall -o "$@" -O3 "$<"

%.class: %.java
	javac "$<"

out.gif: web_bug.py
	python $< $@

clean:
	$(RM) *~ $(OBJS)

%.pro: %.cpp
	qmake -project -nopwd -o $@ $<

%_Makefile: %.pro
	qmake -o $@ $<

qt_%: qt_%_Makefile
	make --file=$<

%.png: %.svg
	batik-rasterizer.sh -h 100 -w 100 -onload -d "$@" "$<"
