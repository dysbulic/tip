volunteer_map.svg: map_source.svg make_dynamic.xslt target.svg
	xsltproc --nonet make_dynamic.xslt $< > $@

map_source.svg: ~/bureau_home/volunteers\ map/map_source.svg
	cp "$<" "$@"
