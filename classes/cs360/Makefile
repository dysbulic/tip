OBJS = cannibals.out assignment_1.dribble search_state.ecbae.out search_state.eebcaaee.out \
	${patsubst %.cpp,%,${wildcard *.cpp}} ${patsubst %.java,%.class,${wildcard *.java}}

all: $(OBJS)

cannibals.out: cannibals.py
	python $< > $@

search_state.ecbae.out: SymbolSearch.class SymbolSearchState.class AStarSearcher.class
	java SymbolSearch > $@

search_state.eebcaaee.out: SymbolErrorSearch.class SymbolSearchState.class AStarSearcher.class
	java SymbolErrorSearch > $@

%.class: %.java
	javac -Xlint:unchecked *.java

assignment_1.dribble: assignment_1.lisp
	clisp $<

clean:
	$(RM) $(OBJS) *.class *~
