/**
 * Author: Will Holcomb <wholcomb@gmail.com>
 * Date: February 2008
 */

Title 'Stress versus Cortisol Secretion';
Options ls=80 ps=55 formdlim = '-';

data stress;
input stress $ cortisol @@;
datalines;
H 77 H 63 H 80 H 45 H 86 H 72
M 34 M 72 M 60 M 44 M 39 M 48
L 51 L 44 L 29 L 48 L 33 L 32
;

proc glm;
class stress;
model cortisol = stress;
run;

Title 'GLMPOWER calculation for Homework #2';

Data Dep;
Input cue $ mean even_weight uneven_weight;
datalines;
A 17.5	1 1
B 19	1 1
C 25	1 5
D 20.5	1 1
;

proc glmpower;
class cue;
model mean = cue;
weight even_weight;
power
	stddev = 9
	alpha = 0.05
	ntotal= 80
	power = .;
run;

proc glmpower;
class cue;
model mean = cue;
weight uneven_weight;
power
	stddev = 9
	alpha = 0.05
	ntotal= 80
	power = .;
run;
