MVN_SRC = http://mirror.cloudera.com/apache/maven/binaries/apache-maven-3.0.1-bin.tar.gz
SRC_FILE = $(notdir $(MVN_SRC))

dist: $(SRC_FILE)
	tar xvfz $<
	ln -s $(subst -bin.tar.gz,,$(SRC_FILE)) dist

$(SRC_FILE):
	wget $(MVN_SRC)