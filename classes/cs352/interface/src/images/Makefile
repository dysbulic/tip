IMAGEBASES = bomb chem
NUMBASES = $(patsubst %,1-%,$(IMAGEBASES)) $(patsubst %,2-%,$(IMAGEBASES)) $(patsubst %,3-%,$(IMAGEBASES)) \
           map chemical_task dirt_task waypoint robot robot-s 1 2 3 4
OFILES = $(patsubst %,%-o.png,$(NUMBASES))

OPACITY = .2

all: $(OFILES)

%-o.png: %.png
	./set_opacity.py $< $(OPACITY) $@