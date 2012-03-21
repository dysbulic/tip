all: surveys.csv

surveys.csv:
	./formdump2csv.py *txt > $@

final.csv:
	./formdump2csv.py *final\ questionnaire* > $@

sart.1.csv:
	./formdump2csv.py *SART\ questionnaire\ -\ 1* > $@

sart.2.csv:
	./formdump2csv.py *SART\ questionnaire\ -\ 2* > $@

use.1.csv:
	./formdump2csv.py *use\ questionnaire\ -\ 1* > $@

use.2.csv:
	./formdump2csv.py *use\ questionnaire\ -\ 2* > $@
