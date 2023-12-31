OBJS = cards DeckOfCards Spheres ViewMatrix ProjectileLeading libprojectile.so libProjectileLeading.so targeting_test

all: $(OBJS)

clean:
	$(RM) $(OBJS) *~

targeting_test: targeting_test.c libprojectile.so
	gcc -o '$@' -lm -L. -lprojectile -lProjectileLeading '$<'

libprojectile.so: projectile_targeting.c
	gcc -o '$@' -shared -lm '$<'

libProjectileLeading.so: ProjectileLeading.c
	gcc -o '$@' -shared -lm '$<'

%: %.cpp
	g++ -o '$@' -lm '$<'

%: %.c
	gcc -o '$@' -lm '$<'
