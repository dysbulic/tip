classes = $(subst bin,src,$(foreach file, $(wildcard src/*.java), $(file:%.java=%.class)))

all:
	javac -d bin src/*.java

jar: EditDistance.jar

EditDistance.jar:
	jar -c -f $@ -C bin . -m manifest src
