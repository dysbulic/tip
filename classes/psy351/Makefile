MATLAB = sh /gpfs0/local/x86_64/matlab/2007b/opteron/sysgcc/nonet/bin/matlab_program

all: homework_02/homework_02.log homework_03/homework_03.log homework_04/homework_04.log \
	homework_06/homework_06.log homework_07/homework_07.log

%.log: %.m
	cd $(dir $<) &&	$(MATLAB) < $(notdir $<) > $(notdir $@) 2>&1
