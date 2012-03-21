OBJ = qt_svg_overpaint
PWD = $(shell pwd | sed -e 's|/cygdrive/c/||' -e 's|[^/]\+|..|g')

ifdef CYGWIN
	QTDOS = $(shell cygpath --dos --mixed "$$QTDIR")
endif

all: $(OBJ)

$(OBJ).pro:
	qmake -project -nopwd -o $@ $<
	echo "QT += opengl svg" >> $@

%_Makefile: %.pro
	qmake -nomoc -unix -o $@ $<
	sed -e 's|c:|$(PWD)|gi' $@ > $@.tmp
	mv "$@.tmp" "$@"
ifdef CYGWIN
	sed -e 's|c:.*moc.exe|$(QTDOS)/bin/moc.exe|gi' \
	    -e 's|c:|$(PWD)|gi' -e 's|/cygdrive/c|$(PWD)|gi' \
	    -e 's|$$(QTDIR)|"$$(QTDIR)"|g' $@.Release > $@.Release.tmp
	mv "$@.Release.tmp" "$@.Release"
	sed -e 's|c:.*moc.exe|$(QTDOS)/bin/moc.exe|gi' -e 's|c:|$(PWD)|gi' \
	    -e 's|/cygdrive/c|$(PWD)|gi' \
	    -e 's|$$(QTDIR)|"$$(QTDIR)"|g' $@.Debug > $@.Debug.tmp
	mv "$@.Debug.tmp" "$@.Debug"
endif

$(OBJ): $(OBJ)_Makefile $(OBJ).cpp main.cpp
	make --file=$<

clean:
	$(RM) $(OBJ) $(OBJ)_Makefile* *.o *.pro moc_glwidget.* *~
