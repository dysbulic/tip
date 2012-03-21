#ifndef __ROBOT_AND_SDL__
#define __ROBOT_AND_SDL__

#ifdef __WIN32__
#include <stdlib.h>
#include <stdio.h>
#include <windows.h>
#include "robot.h"
#include <SDL/SDL.h>
#undef main
#undef SDLmain
#else
#include <SDL/SDL.h>
#endif

#endif
