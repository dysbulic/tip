OUTDIR = $(shell pwd | sed -e 's|.*\.\.\./lib/mimis/inhabit/win||')

all:
	find -L -name ... -prune -or \( -name js -or -name rhino \) -print0 \
	 | xargs -0 cat \
	 > "${OUTDIR}/js"
