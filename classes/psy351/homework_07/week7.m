%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
% week 7

clear all;
close all;

FigIndex = 1;

% Psychometric Function Example
%
% illustration of bootstrapping

global DATA;
global X;

Ntrials = 50;

X = [
    0.505;
    0.800;
    1.073;
    1.382;
    1.782;
    1.982;
    2.503;
    3.491;
];

Data = [
    27    23;
    21    29;
    24    26;
    30    20;
    37    13;
    36    14;
    48     2;
    49     1;
];

DATA = Data;

% initialize parameters
alpha  = 1;
beta   = 1;
lambda = .03;
gamma  = .5;

parInit = [alpha    beta lambda gamma];
parLow  = [    0 .000001      0     0];
parHigh = [  999     999      1     1];

options = optimset('Display', 'off', 'MaxIter', 500, 'LargeScale', 'off');
[parBest,parFit,exitflag,output,L,grad,hessian] = fmincon(@mymodel_psy,parInit,[],[],[],[],parLow,parHigh,[],options);
    
% get best-fitting parameters 
Bestalpha  = parBest(1);
Bestbeta   = parBest(2);
Bestlambda = parBest(3);
Bestgamma  = parBest(4);

% generate the psychometric function
x = 0:.1:4;
psy = Bestgamma + (1-Bestgamma-Bestlambda).*(1-exp(-(x./Bestbeta).^Bestalpha));

lnL = -parFit;

% generate a figure with observed and predicted
figure(FigIndex);
FigIndex = FigIndex + 1;
plot(x,psy,'-r',X,Data(:,1)/(Data(:,1)+Data(:,2)),'or');
xlabel('intensity');
ylabel('P(Correct)');
title('Psychometric Function');
axis([0 4 .45 1.05]);
text_string = sprintf('lnL = %g', lnL);
text(3, .5, text_string);

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

% Try calculating standard errors using NONPARAMETRIC
% bookstrapping procedures
% 

% We are going to try Nboot iterations. Here, I am just trying 50
% iterations, but in practice we would try 5000 or more. Bootstrapping
% is time-consuming.
Nboot = 20;

% You will create a histogram of lnL values in order to figure out
% the .025 (lower) and .975 (upper) percentiles in order to create
% the confidence intervals. This initializes the array that saves
% values (lnL's and the parameter values on each iteration).
HistArray = zeros(Nboot, length(parInit)+1);

% loop for the bootstrap
for i=1:Nboot

    % create a new dataset by bootstrapping
    BootData = Create_Bootstrap(Data);
    
    % make that the Data
    DATA = BootData;
    
    % fit the model to that "data"
    [parBest,parFit,exitflag,output,L,grad,hessian] = fmincon(@mymodel_psy,parInit,[],[],[],[],parLow,parHigh,[],options);

    HistArray(i,1) = parFit;
    HistArray(i,2:end) = parBest;
end

% need to sort HistArray in order to pull out the .025% and .975% 
% for each parameter

HistArray = sort(HistArray);
lower_CI = round(.025*Nboot);
upper_CI = round(.975*Nboot);
fprintf('alpha  (%g - %g - %g)\n', HistArray(lower_CI,2), Bestalpha,  HistArray(upper_CI,2));
fprintf('beta   (%g - %g - %g)\n', HistArray(lower_CI,3), Bestbeta,   HistArray(upper_CI,3));
fprintf('lambda (%g - %g - %g)\n', HistArray(lower_CI,4), Bestlambda, HistArray(upper_CI,4));
fprintf('gamma  (%g - %g - %g)\n', HistArray(lower_CI,5), Bestgamma,  HistArray(upper_CI,5));

% As you can see, when you only do 100 iterations, you do not
% get a very good estimate of the tails of the distribution.
% Here is the answer when you do 5000 iterations:
%
% 
% For comparison, here is the CI using the Hessian:
% alpha  (2.81432 - 4.16255 - 5.51078)
% beta   (1.41389 - 1.51401 - 1.61413)
% lambda (-0.0105429 - 0.0140742 - 0.0386913)
% gamma  (0.395604 - 0.490023 - 0.584443)
%

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
% Try calculating standard errors using PARAMETRIC
% bookstrapping procedures
%

% We are going to try Nboot iterations. Here, I am just trying 50
% iterations, but in practice we would try 5000 or more. Bootstrapping
% is time-consuming.
Nboot = 20;

% You will create a histogram of lnL values in order to figure out
% the .025 (lower) and .975 (upper) percentiles in order to create
% the confidence intervals. This initializes the array that saves
% values (lnL's and the parameter values on each iteration).
HistArray = zeros(Nboot, length(parInit)+1);

% loop for the bootstrap
for i=1:Nboot

    % create a new dataset by bootstrapping
    BootData = Create_Parametric_Bootstrap(X, Ntrials, Bestalpha, Bestbeta, Bestlambda, Bestgamma);
    
    % make that the Data
    DATA = BootData;
    
    % fit the model to that "data"
    [parBest,parFit,exitflag,output,L,grad,hessian] = fmincon(@mymodel_psy,parInit,[],[],[],[],parLow,parHigh,[],options);

    HistArray(i,1) = parFit;
    HistArray(i,2:end) = parBest;
end

% need to sort HistArray in order to pull out the .025% and .975% 
% for each parameter

HistArray = sort(HistArray);
lower_CI = round(.025*Nboot);
upper_CI = round(.975*Nboot);
fprintf('alpha  (%g - %g - %g)\n', HistArray(lower_CI,2), Bestalpha,  HistArray(upper_CI,2));
fprintf('beta   (%g - %g - %g)\n', HistArray(lower_CI,3), Bestbeta,   HistArray(upper_CI,3));
fprintf('lambda (%g - %g - %g)\n', HistArray(lower_CI,4), Bestlambda, HistArray(upper_CI,4));
fprintf('gamma  (%g - %g - %g)\n', HistArray(lower_CI,5), Bestgamma,  HistArray(upper_CI,5));

% As you can see, when you only do 100 iterations, you do not
% get a very good estimate of the tails of the distribution.
% Here is the answer when you do 5000 iterations:
%
% 
% For comparison, here is the CI using the Hessian:
% alpha  (2.81432 - 4.16255 - 5.51078)
% beta   (1.41389 - 1.51401 - 1.61413)
% lambda (-0.0105429 - 0.0140742 - 0.0386913)
% gamma  (0.395604 - 0.490023 - 0.584443)
%

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
%
% random walk / diffusion simulation
%
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

a  = 0.300;      % upper boundary (lower boundary is 0)
z  = 0.100;      % starting point
nu = 0.70;       % mean drift rate
TR = 0.200;      % non-decisional time
s2 = .01;        % diffusion coeffient is the amount of within-trial noise

% this simulates one trial (one decision)
[cum_evidence_path,RT_path,index_path]=diffusion_simulation_path(nu,s2,TR,a,z);

figure(FigIndex);
FigIndex = FigIndex + 1;

plot(RT_path(1:index_path), cum_evidence_path(1:index_path), 'r');
xlabel('Time');
ylabel('evidence');
max_time = 1.2;
axis([TR max_time -.05 a+.05]);
line([TR max_time], [0 0], 'LineWidth', 4);
line([TR max_time], [a a], 'LineWidth', 4);
hold on;

% do 10 more trials
for i=1:10
    % this simulates one trial (one decision)
    [cum_evidence_path,RT_path,index_path]=diffusion_simulation_path(nu,s2,TR,a,z);

    plot(RT_path(1:index_path), cum_evidence_path(1:index_path), 'r');
end

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

% now try some different parameters - in this case, making
% drift rate a lot smaller
a  = 0.300;      % upper boundary (lower boundary is 0)
z  = 0.100;      % starting point
nu = 0.10;       % mean drift rate
TR = 0.200;      % non-decisional time
s2 = .01;        % diffusion coeffient is the amount of within-trial noise

figure(FigIndex);
FigIndex = FigIndex + 1;
xlabel('Time');
ylabel('evidence');
max_time = 4;
axis([TR max_time -.05 a+.05]);
line([TR max_time], [0 0], 'LineWidth', 4);
line([TR max_time], [a a], 'LineWidth', 4);
hold on;

% do 10 trials
for i=1:10
    % this simulates one trial (one decision)
    [cum_evidence_path,RT_path,index_path]=diffusion_simulation_path(nu,s2,TR,a,z);

    plot(RT_path(1:index_path), cum_evidence_path(1:index_path), 'r');    
end

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

% parameter variability
% drift rate has trial by trial variability N(nu,eta)
% TR has a uniform distribution Ter +/- st/2
% z has a uniform distribution z +/- sz/2

iterations = 10;

a   = 0.500;      % upper boundary (lower boundary is 0)
z   = 0.200;      % starting point
sz  = 0.01;       % variability in starting point from trial to trial
nu  = 0.70;       % mean drift rate
eta = 0.500;      % variability in drift rate from trial to trial
Ter = 0.200;      % non-decisional time
st  = 0.05;       % variabilit in TR from trial to trial
s2  = .01;        % diffusion coeffient is the amount of within-trial noise

Ter_lo = Ter - st/2;
Ter_hi = Ter + st/2;

z_lo = z - sz/2;
z_hi = z + sz/2;

figure(FigIndex);
FigIndex = FigIndex + 1;
xlabel('Time');
ylabel('evidence');
max_time = 4;
axis([TR max_time -.05 a+.05]);
line([TR max_time], [0 0], 'LineWidth', 4);
line([TR max_time], [a a], 'LineWidth', 4);
hold on;

for i=1:iterations

    % Find TR for this trial 
    TR =  Ter_lo + rand .* (Ter_hi - Ter_lo);
    
    % Find Sample z for this trial
    ZZ= z_lo + rand .* (z_hi - z_lo);
    
    % Find sample mu for this trial
    mu = normrnd(nu,eta);
    
    [cum_evidence_path,RT_path,index_path]=diffusion_simulation_path(mu,s2,TR,a,ZZ);

    plot(RT_path(1:index_path), cum_evidence_path(1:index_path), 'r');    
end

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

%
% illustrate cdf (Weibull)
%

time = 0:.05:2.8;
TR = .2;
tstcdf = wblcdf(time, 1, 2);

figure(FigIndex);
FigIndex = FigIndex + 1;
plot(time+TR,tstcdf);
axis([0 3 0 1]);
title('Weibull cdf');
xlabel('Time');
ylabel('F(t)');

%
% illustrate pdf (Weibull)
%

time = 0:.05:2.8;
TR = .2;
tstpdf = wblpdf(time, 1, 2);

figure(FigIndex);
FigIndex = FigIndex + 1;
plot(time+TR,tstpdf);
axis([0 3 0 1]);
title('Weibull pdf');
xlabel('Time');
ylabel('f(t)');

%
% illustrate hazard function (Weibull)
%
% h(t) = pdf(t) / (1 - cdf(t))
%

h = tstpdf ./ (1 - tstcdf);

figure(FigIndex);
FigIndex = FigIndex + 1;
plot(time+TR,h);
axis([0 3 0 1.1*max(h)]);
title('Weibull hazard');
xlabel('Time');
ylabel('h(t)');

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

%
% illustrate cdf (Exponential)
%

time = 0:.05:2.8;
TR = .2;
tstcdf = expcdf(time, 1);

figure(FigIndex);
FigIndex = FigIndex + 1;
plot(time+TR,tstcdf);
axis([0 3 0 1]);
title('Exponential cdf');
xlabel('Time');
ylabel('F(t)');

%
% illustrate pdf (Exponential)
%

time = 0:.05:2.8;
TR = .2;
tstpdf = exppdf(time, 1);

figure(FigIndex);
FigIndex = FigIndex + 1;
plot(time+TR,tstpdf);
axis([0 3 0 1]);
title('Exponential pdf');
xlabel('Time');
ylabel('f(t)');

%
% illustrate hazard function (Exponential)
%
% h(t) = pdf(t) / (1 - cdf(t))
%

h = tstpdf ./ (1 - tstcdf);

figure(FigIndex);
FigIndex = FigIndex + 1;
plot(time+TR,h);
axis([0 3 0 1.1*max(h)]);
title('Exponential hazard');
xlabel('Time');
ylabel('h(t)');

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

% generate 500 RTs from the diffusion model

% parameter variability
% drift rate has trial by trial variability N(nu,eta)
% TR has a uniform distribution Ter +/- st/2
% z has a uniform distribution z +/- sz/2

iterations = 500;

a   = 0.500;      % upper boundary (lower boundary is 0)
z   = 0.200;      % starting point
sz  = 0.01;       % variability in starting point from trial to trial
nu  = 0.70;       % mean drift rate
eta = 0.500;      % variability in drift rate from trial to trial
Ter = 0.200;      % non-decisional time
st  = 0.05;       % variabilit in TR from trial to trial
s2  = .01;        % diffusion coeffient is the amount of within-trial noise

Ter_lo = Ter - st/2;
Ter_hi = Ter + st/2;

z_lo = z - sz/2;
z_hi = z + sz/2;

tic;
for i=1:iterations

    % Find TR for this trial 
    TR(i) =  Ter_lo + rand .* (Ter_hi - Ter_lo);
    
    % Find Sample z for this trial
    ZZ(i)= z_lo + rand .* (z_hi - z_lo);
    
    % Find sample mu for this trial
    mu(i) = normrnd(nu,eta);
    
    % calculate RT and which boundary is hit
    [RT(i),which(i)]=diffusion_simulation(mu(i),s2,TR(i),a,ZZ(i));
end
toc;

Correct_RT = RT(find(which==1));
Error_RT = RT(find(which==0));
Accuracy = mean(which);

%
% Histogram estimation of RT PDF
%

Bins = 0:.05:2.5;
[histogram,t] = hist(Correct_RT,Bins);
figure(FigIndex);
FigIndex = FigIndex + 1;
plot(t(1:end-1),histogram(1:end-1));
title('histogram pdf estimate');
xlabel('RT');
ylabel('count');
axis([0 2.5 0 1.1*max(histogram)]);

%
% Kernal estimation of RT PDF
%

[pdf,t] = ksdensity(Correct_RT);
figure(FigIndex);
FigIndex = FigIndex + 1;
plot(t,pdf);
title('kernal pdf estimate');
xlabel('RT');
ylabel('f(t)');
axis([0 2.5 0 1.1*max(pdf)]);

%
% full CDF
%

t = sort(Correct_RT);
N = length(Correct_RT);
cdf = (1:N)/N;
figure(FigIndex);
FigIndex = FigIndex + 1;
plot(t,cdf);
title('cdf estimate');
xlabel('RT');
ylabel('F(t)');
axis([0 2.5 0 1]);

%
% CDF in percentiles
%

cdf = [.05:.05:.95];
t = sort(Correct_RT);
N = length(Correct_RT);
cdf_t = t(round(cdf*N));
figure(FigIndex);
FigIndex = FigIndex + 1;
plot(cdf_t,cdf,'r-',cdf_t,cdf,'ro');
title('cdf percentiles');
xlabel('RT');
ylabel('F(t)');
axis([0 2.5 0 1]);

%
% CDF in smaller percentiles
%

cdf = [.1 .3 .5 .7 .9];
t = sort(Correct_RT);
N = length(Correct_RT);
cdf_t = t(round(cdf*N));
figure(FigIndex);
FigIndex = FigIndex + 1;
plot(cdf_t,cdf,'r-',cdf_t,cdf,'ro');
title('cdf percentiles');
xlabel('RT');
ylabel('F(t)');
axis([0 2.5 0 1]);

% 
% RT hazard function
%
t = sort(Correct_RT);
N = length(Correct_RT);
cdf = (1:N)/N;
[pdf,t] = ksdensity(Correct_RT,t);
h = pdf ./ (1-cdf);
figure(FigIndex);
FigIndex = FigIndex + 1;
plot(t,h);
title('hazard function');
xlabel('RT');
ylabel('h(t)');
axis([0 2.5 0 1.1*max(h)]);

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

% example of vincentizing

iterations = 500;

% SUBJECT 1
a   = 0.500;      % upper boundary (lower boundary is 0)
z   = 0.200;      % starting point
sz  = 0.01;       % variability in starting point from trial to trial
nu  = 0.70;       % mean drift rate
eta = 0.200;      % variability in drift rate from trial to trial
Ter = 0.200;      % non-decisional time
st  = 0.05;       % variabilit in TR from trial to trial
s2  = .01;        % diffusion coeffient is the amount of within-trial noise
Ter_lo = Ter - st/2;    Ter_hi = Ter + st/2;
z_lo = z - sz/2;        z_hi = z + sz/2;
for i=1:iterations
    TR(i) =  Ter_lo + rand .* (Ter_hi - Ter_lo);
    ZZ(i)= z_lo + rand .* (z_hi - z_lo);
    mu(i) = normrnd(nu,eta);
    [RT(i),which(i)]=diffusion_simulation(mu(i),s2,TR(i),a,ZZ(i));
end
Correct_RT1 = RT(find(which==1));

% SUBJECT 2
a   = 0.600;      % upper boundary (lower boundary is 0)
z   = 0.250;      % starting point
sz  = 0.02;       % variability in starting point from trial to trial
nu  = 0.70;       % mean drift rate
eta = 0.250;      % variability in drift rate from trial to trial
Ter = 0.400;      % non-decisional time
st  = 0.05;       % variabilit in TR from trial to trial
s2  = .01;        % diffusion coeffient is the amount of within-trial noise
Ter_lo = Ter - st/2;    Ter_hi = Ter + st/2;
z_lo = z - sz/2;        z_hi = z + sz/2;
for i=1:iterations
    TR(i) =  Ter_lo + rand .* (Ter_hi - Ter_lo);
    ZZ(i)= z_lo + rand .* (z_hi - z_lo);
    mu(i) = normrnd(nu,eta);
    [RT(i),which(i)]=diffusion_simulation(mu(i),s2,TR(i),a,ZZ(i));
end
Correct_RT2 = RT(find(which==1));

% SUBJECT 3
a   = 0.600;      % upper boundary (lower boundary is 0)
z   = 0.200;      % starting point
sz  = 0.05;       % variability in starting point from trial to trial
nu  = 0.65;       % mean drift rate
eta = 0.300;      % variability in drift rate from trial to trial
Ter = 0.650;      % non-decisional time
st  = 0.10;       % variabilit in TR from trial to trial
s2  = .01;        % diffusion coeffient is the amount of within-trial noise
Ter_lo = Ter - st/2;    Ter_hi = Ter + st/2;
z_lo = z - sz/2;        z_hi = z + sz/2;
for i=1:iterations
    TR(i) =  Ter_lo + rand .* (Ter_hi - Ter_lo);
    ZZ(i)= z_lo + rand .* (z_hi - z_lo);
    mu(i) = normrnd(nu,eta);
    [RT(i),which(i)]=diffusion_simulation(mu(i),s2,TR(i),a,ZZ(i));
end
Correct_RT3 = RT(find(which==1));

%
% PDFs
%

[pdf1,t1] = ksdensity(Correct_RT1);
[pdf2,t2] = ksdensity(Correct_RT2);
[pdf3,t3] = ksdensity(Correct_RT3);

figure(FigIndex);
FigIndex = FigIndex + 1;
plot(t1,pdf1,'r-',t2,pdf2,'r-',t3,pdf3,'r-');
title('kernal pdf estimate');
xlabel('RT');
ylabel('f(t)');
axis([0 2.5 0 1.1*max([pdf1 pdf2 pdf3])]);
hold on;

[pdf,t] = ksdensity([Correct_RT1 Correct_RT2 Correct_RT3]);
plot(t,pdf,'b-');

%
% CDFs
%

cdf = .05:.05:.95;
t = sort(Correct_RT1);
N = length(Correct_RT1);
cdf_t1 = t(round(cdf*N));
t = sort(Correct_RT2);
N = length(Correct_RT2);
cdf_t2 = t(round(cdf*N));
t = sort(Correct_RT3);
N = length(Correct_RT3);
cdf_t3 = t(round(cdf*N));
figure(FigIndex);
FigIndex = FigIndex + 1;
plot(cdf_t1,cdf,'r-o',cdf_t2,cdf,'r-o',cdf_t3,cdf,'r-o');
title('cdfs');
xlabel('RT');
ylabel('F(t)');
axis([0 2.5 0 1]);
hold on;

% combine RTs
cdf = .05:.05:.95;
t = sort([Correct_RT1 Correct_RT2 Correct_RT3]);
N = length([Correct_RT1 Correct_RT2 Correct_RT3]);
cdf_t = t(round(cdf*N));
plot(cdf_t,cdf,'b-o');

% Vincentizing
cdf = .05:.05:.95;
cdf_t = mean([cdf_t1' cdf_t2' cdf_t3'], 2);
plot(cdf_t,cdf,'g-o');
hold off;

