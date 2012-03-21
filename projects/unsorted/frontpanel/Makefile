CFLAGS += -Wall -D__KERNEL__ -DMODULE -I/usr/src/linux/include -I /usr/src/kernels/$$(uname --kernel-release)-i686/include -O2
NAME = ${patsubst %.c, %, ${wildcard *.c}}
OBJS = ${patsubst %.c, %.o, ${wildcard *.c}}
DEV = /dev/frontpanel

CFLAGS += -DDEBUG

obj-m   := frontpanel.o
obj-m   := usb-skeleton.2.6.15.o
obj-m   := usb-skeleton.test.o
KERNELDIR ?= /lib/modules/$(shell uname --kernel-release)/build
PWD       := $(shell pwd)

SDCC_HC908JB8 = --code-loc 0xDC00 --data-loc 0x0000 --xram-loc 0x0040 --stack-loc 0x013f -mhc08

HEADERS = ${patsubst %.h.xhtml,%.h,${wildcard *.h.xhtml}}

INTERMEDIATES = ${patsubst %.s19,%.rel,${wildcard *.s19}} \
	        ${patsubst %.s19,%.asm,${wildcard *.s19}} \
	        ${patsubst %.s19,%.map,${wildcard *.s19}} \
		${patsubst %.s19,%.lnk,${wildcard *.s19}} \
		${patsubst %.s19,%.lst,${wildcard *.s19}} \
		${patsubst %.s19,%.rst,${wildcard *.s19}} \
		${patsubst %.s19,%.mem,${wildcard *.s19}} \
		${patsubst %.s19,%.sym,${wildcard *.s19}}

.PHONY: all clean modreload docs

# all:; $(MAKE) -C $(KERNELDIR) M=$(PWD)

all: prommer.cleaned.svg $(HEADERS) hello_world.hc908jb8.s19

%.cleaned.svg: %.svg
	echo '<?xml version="1.0" standalone="no"?>' > "$@"
	./compress_pathes.py "$<" >> "$@"

doxygen.conf:; doxygen -g $@

docs:
	doxygen doxygen.conf

echo:; @echo $(INTERMEDIATES)

%.h: %.h.xhtml
	@echo "Stripping $@ from $<"
	@echo "<stylesheet version=\"1.0\" \
	        xmlns=\"http://www.w3.org/1999/XSL/Transform\" \
	        xmlns:html=\"http://www.w3.org/1999/xhtml\"> \
	         <output method=\"text\"/> \
	         <template match=\"/\"> \
	           <apply-templates select=\"//html:div[contains(@class, 'code')]\"/> \
	         </template> \
	       </stylesheet>" | xsltproc --xinclude - $< > $@

%.s19: %.c
	sdcc -L ~/sdcc/device/lib/build/hc08/ $(SDCC_HC908JB8) -o $@ $<

hello_world.hc908jb8.c: usb.h types.h mc68hc908jb8.h

modreload: $(OBJS)
	@$(RM) -v $(DEV)
	/sbin/lsmod | grep -qe "^$(trim $(NAME))" && rmmod -v $(NAME) || true
	insmod $(OBJS)
	mknod $(DEV) c $$(grep $(NAME) /proc/devices | cut "-d " -f1) 0

clean:; $(RM) -rfv *.o *~ core .depend .*.cmd *.ko *.mod.c .tmp_versions Modules.symvers *.s19 $(INTERMEDIATES) $(HEADERS) prommer.cleaned.svg
