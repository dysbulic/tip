# Certain programs are finicky about their character sets.
# All of my work is in utf-8 and this produces versions
# appropriate for the NSIS compiler and WScript interpreter

utf16_scripts = create_arabic_student restrict_student_account personalize_student_account
iso88591_installers = postinstall gmc_programs rosetta_stone
files = $(foreach file, $(utf16_scripts), $(file).utf16.vbs) \
	$(foreach file, $(iso88591_installers), $(file).iso88591.nsi)

all: $(files)

%.utf16.vbs: %.vbs
	iconv -f utf-8 -t utf-16 $< > $@

%.iso88591.nsi: %.nsi
	iconv -f utf-8 -t iso-8859-1 "$<" > "$@"

clean:
	$(RM) -v $(files)
