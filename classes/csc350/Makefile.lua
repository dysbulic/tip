PROG = lua
VPATH = src
CFLAGS += -Wall -Iinclude
LDFLAGS += -lm
SKIP += test/globals.lua # requires user interaction
TESTS := $(subst $(SKIP),,$(wildcard test/*.lua))
OBJS := $(patsubst %.c,%.o,$(wildcard src/*.c)) 

.PHONY: all depend clean test $(TESTS)
$(PROG): $(OBJS)
all: $(PROG)
depend: $(OBJS)
clean:;	@$(RM) -v $(PROG) $$(find -name "*~") $(OBJS)
test: $(PROG) $(TESTS)
$(TESTS):
	@echo "Running test $@:"
	@time ./$(PROG) $@
	@echo 

