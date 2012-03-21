#ifndef __BOMB_INFO__
#define __BOMB_INFO__

const long MAX_BOMBS = 4;
const long MAX_MODES = 3;

const long BOMB_NOT_FOUND = 0;
const long BOMB_FOUND = 1;
const long BOMB_DEFUSED = 2;

const long BOMB_COUNTS[MAX_MODES] = {1, 2, 2};

const float BOMB_INFO_X[MAX_MODES][MAX_BOMBS] = {
    {-7,  0, 0, 0},
    {7.5,  21.8, 0, 0},
    {14.3, -5, 20.66,  0}
};

const float BOMB_INFO_Y[MAX_MODES][MAX_BOMBS] = {
    {9,  0, 0, 0},
    {10.3,  -6.2, 0, 0},
    {-8.6, -9, -4.6, 0}
};

const float BOMB_INFO_Z[MAX_MODES][MAX_BOMBS] = {
    {18.24,  0, 0, 0},
    {18.24,  18.24, 0, 0},
    {18.24,  18.24, 0, 0}
};

const float BOMB_INFO_R1[MAX_MODES][MAX_BOMBS] = {
    {0, 0, 0, 0},
    {0, 0, 0, 0},
    {0, 0, 0, 0}
};

const float BOMB_INFO_R2[MAX_MODES][MAX_BOMBS] = {
    {0, 0, 0, 0},
    {0, 0, 0, 0},
    {0, 0, 0, 0}
};

const float BOMB_INFO_R3[MAX_MODES][MAX_BOMBS] = {
    {0, 0, 0, 0},
    {0, 0, 0, 0},
    {0, 0, 0, 0}
};

#endif
