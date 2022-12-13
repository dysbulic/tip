# java org.apache.xalan.xslt.Process -IN filelist.xml -XSL list-html.xsl -HTML -OUT index.html

# Because ant dies on an error, this includes all the jars so as to restart it

objs = index.html Axes.jnlp Star.jar Spinners.jar Translation.jar Axes.jar Behavior.jar Detach.jar LabelRotation.jar ViewPlatformTest.jar Overlays.jar SceneViewer.jar

all: $(objs);

# The xalan redirect is handled even though it isn't listed as an extension
index.html: filelist.xml list-html.xslt
	xsltproc list-html.xslt filelist.xml > $@

%.jar:
	ant $@

%.jnlp: filelist.xml list-jnlp.xslt
	xsltproc list-jnlp.xslt filelist.xml

clean:
	$(RM) $(objs);
