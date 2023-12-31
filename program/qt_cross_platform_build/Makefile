OBJ=hello_qworld

all: $(OBJ)_Makefile
ifdef CYGWIN
	nmake /f $<
else
	make -f $<
endif

$(OBJ)_Makefile: $(OBJ).pro
	qmake -o $@ $<

$(OBJ).pro:
	qmake -set TARGET $(OBJ)
	qmake -project -o $@

clean:
	$(RM) -rfv *~ $(OBJ)_Makefile* $(OBJ).pro $(OBJ) $(patsubst %.cpp,%.o,$(wildcard *.cpp)) debug/ release/ vc*.pdb
