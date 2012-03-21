% Homework 6

clear all;
close all;

FigIndex = 1;

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
% Psychometric Function Example
%

global Data;
global X;

Ntrials = 500;

X = [
    0.800;
    1.073;
    1.382;
    1.782;
    1.982;
    3.491;
];

Data = [
    250 250;
    310 190;
    380 120;
    430  70;
    500   0;
    490  10;
];

% get ready to call hook

% initialize parameters
alpha  = 1;
beta   = 1;
lambda = 0;
gamma  = .5;

parInit = [alpha    beta lambda gamma];
parLow  = [    0 .000001      0     0];
parHigh = [  999     999      1     1];
parInc  = [  .05     .05    .01   .01];

[HOOK_fit,HOOK_pos,HOOK_path]=hook('mymodel_psy',parInit,parLow,parHigh,parInc,parInc/10);

% get best-fitting parameters 
Bestalpha  = HOOK_pos(1);
Bestbeta   = HOOK_pos(2);
Bestlambda = HOOK_pos(3);
Bestgamma  = HOOK_pos(4);

% generate the psychometric function
x = 0:.1:4;
psy = Bestgamma + (1-Bestgamma-Bestlambda).*(1-exp(-(x./Bestbeta).^Bestalpha));

% generate a figure with observed and predicted
figure(FigIndex);
FigIndex = FigIndex + 1;
plot(x,psy,'-r',X,Data(:,1)/(Data(:,1)+Data(:,2)),'ob');
xlabel('intensity');
ylabel('P(Correct)');
title('Full Psychometric Function');
axis([0 4 0 1]);
text_string = sprintf('lnL = %g', -HOOK_fit);
text(3, .5, text_string);

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
% Psychometric Function Example
%

global Data;
global X;

Ntrials = 500;

X = [
    0.800;
    1.073;
    1.382;
    1.782;
    1.982;
    3.491;
];

Data = [
    250 250;
    310 190;
    380 120;
    430  70;
    500   0;
    490  10;
];

% get ready to call hook

% initialize parameters
alpha  = 1;
beta   = 1;
lambda = 0;
gamma  = .5;

parInit = [alpha    beta lambda gamma];
parLow  = [    0 .000001      0     0];
parHigh = [  999     999      1     1];
parInc  = [  .05     .05      0   .01];

[HOOK_fit,HOOK_pos,HOOK_path]=hook('mymodel_psy',parInit,parLow,parHigh,parInc,parInc/10);

% get best-fitting parameters 
Bestalpha  = HOOK_pos(1);
Bestbeta   = HOOK_pos(2);
Bestlambda = HOOK_pos(3);
Bestgamma  = HOOK_pos(4);

% generate the psychometric function
x = 0:.1:4;
psy = Bestgamma + (1-Bestgamma-Bestlambda).*(1-exp(-(x./Bestbeta).^Bestalpha));

% generate a figure with observed and predicted
figure(FigIndex);
FigIndex = FigIndex + 1;
plot(x,psy,'-r',X,Data(:,1)./(Data(:,1)+Data(:,2)),'ob');
xlabel('intensity');
ylabel('P(Correct)');
title('Psychometric Function - Constrained');
axis([0 4 0 1]);
text_string = sprintf('lnL = %g', -HOOK_fit);
text(3, .5, text_string);

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
% Psychometric Function Example
%
% Logistic
%

global Data;
global X;

Ntrials = 500;

X = [
    0.800;
    1.073;
    1.382;
    1.782;
    1.982;
    3.491;
];

Data = [
    250 250;
    310 190;
    380 120;
    430  70;
    500   0;
    490  10;
];

% get ready to call hook

% initialize parameters
alpha  = 1;
beta   = 1;
lambda = 0;
gamma  = .5;

parInit = [alpha    beta lambda gamma];
parLow  = [    0 .000001      0     0];
parHigh = [  999     999      1     1];
parInc  = [  .05     .05    .01   .01];

[HOOK_fit,HOOK_pos,HOOK_path]=hook('mymodel_logistic',parInit,parLow,parHigh,parInc,parInc/10);

% get best-fitting parameters 
Bestalpha  = HOOK_pos(1);
Bestbeta   = HOOK_pos(2);
Bestlambda = HOOK_pos(3);
Bestgamma  = HOOK_pos(4);

% generate the psychometric function
x = 0:.1:4;
psy = Bestgamma + (1-Bestgamma-Bestlambda).*(1./(1+exp(-(x-Bestalpha)./Bestbeta)));

% generate a figure with observed and predicted
figure(FigIndex);
FigIndex = FigIndex + 1;
plot(x,psy,'-r',X,Data(:,1)./(Data(:,1)+Data(:,2)),'ob');
xlabel('intensity');
ylabel('P(Correct)');
title('Psychometric Function - Logistic');
axis([0 4 0 1]);
text_string = sprintf('lnL = %g', -HOOK_fit);
text(3, .5, text_string);

