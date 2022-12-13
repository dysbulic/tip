objs = $(foreach source, $(wildcard *.c), $(source:%.c=%))
all: $(objs)

clean:
	$(RM) -v $(objs) *~

gnome_%: gnome_%.c
	gcc $< -o $@ -g -Wall `gnome-config --cflags gnome gnomeui` `gnome-config --libs gnome gnomeui`

gtk_%: gtk_%.c
	gcc $< -Wall -o $@ `pkg-config --cflags --libs gtk+-2.0`

%: %.c
	gcc $< -Wall -o $@

