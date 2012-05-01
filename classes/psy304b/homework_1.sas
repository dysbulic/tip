/**
 * Author: Will Holcomb <wholcomb@gmail.com>
 * Date: January 2008
 *
 * Simple SAS program to load a dataset and perform a one-way ANOVA on
 * it.
 */

/* LS -> 'line size' -> number of columns in the output
 * PS -> 'page size' -> number of lines per page in the output
 * FORMDLIM -> 'form delimiter' -> overrides the default output method
 *                                 of one result per page by separating
 *                                 forms with a character
 */
options LS=80 PS=55 formdlim = '-';

/* Load CVS data from a file */
proc import datafile="spider_data.csv" out=spider dbms=csv replace;
   getnames=yes;
run;

proc glm;           /* glm -> 'general linear model' -> library used to compute ANOVA */
/*class filmgp;*/       /* class defines header values as discrete rather than time-series */
model emg = filmgp; /* data relationship to model */
means filmgp;       /* also print mean and standard deviation information */
run;
