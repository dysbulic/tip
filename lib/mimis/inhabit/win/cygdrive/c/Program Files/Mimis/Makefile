OUTDIR = $(shell pwd | sed -e 's|.*\.\.\./lib/mimis/inhabit/win||')

all:
	mkdir -p "${OUTDIR}"
	find -L -maxdepth 2 -name ... -prune \
		-or \( -name js -or -name rhino \) \
		-print0 \
	 2> /dev/null \
	 | xargs -0 cat \
	 > "${OUTDIR}/js"
