.PHONY: test all

compiled_files = libhello.so HelloWorld.class HelloWorld.h call_jvm

all: ${compiled_files}

libhello.so: hello_world_impl.c HelloWorld.h
	cc -shared -Wall -I /usr/lib/jvm/java-6-sun-1.6.0.07/include/ -I /usr/lib/jvm/java-6-sun-1.6.0.07/include/linux/ -o $@ $<

HelloWorld.h: HelloWorld.class
	${JAVADIR}javah HelloWorld

%.class: %.java
	${JAVADIR}javac $<

call_jvm: call_jvm.c
	cc -shared -Wall -I /usr/lib/jvm/java-6-sun-1.6.0.07/include/ -I /usr/lib/jvm/java-6-sun-1.6.0.07/include/linux/ -o $@ $<

# On windows
# cl -Ic:\java\include -Ic:\java\include\win32 -LD hello_world_impl.c -Fehello.dll

test: HelloWorld.class libhello.so
	${JAVADIR}java -Djava.library.path=. HelloWorld

clean:
	${RM} ${compiled_files} *~
