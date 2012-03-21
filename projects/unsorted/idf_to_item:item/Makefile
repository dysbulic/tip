SVGS = listeners_per_artist.svg log_listeners_per_artist.svg \
	listens_per_artist.svg log_listens_per_artist.svg \
	listens_per_listener.svg low_pop_listens_per_listener.svg \
	unique_artists_per_listener.svg \
	vector_length_per_artist_raw.svg

svgs: $(SVGS)

lastfm_graphs.pdf: lastfm_data_graphs.R
	R --no-save < "$<"

clean:
	$(RM) $(SVGS) *~

%.svg: %_raw.svg
	xsltproc ../inkscape_washout/set_100%_viewbox.xslt "$<" > "$@.tmp"
	./compress_pathes.py "$@.tmp" > "$@"
	$(RM) "$@.tmp"

%_raw.svg: lastfm_data_graphs.R
	R --no-save --svg < "$<"
