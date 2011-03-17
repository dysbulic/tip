# ToDo: Pull from manifest
ICON_SIZES = 16 48 128
icons = $(foreach size, $(ICON_SIZES), icon/$(size)/png)

all: crx

icon/%/png: logo/svg
	mkdir -p "$(@D)"
	# When given dir/png as an output location, batik creates dir/png/svg.png
	rasterize -m image/png -w "$*" -h "$*" -d "$(@D)/tmp.png" "$<"
	mv "$(@D)/tmp.png" "$@"

crx: $(icons) background.html manifest.json */jquery */js */html
	mkcrx . key/signing/pem
