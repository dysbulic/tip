% Week 4 Homework 
%

clear variables;
close all;
format short;

FigIndex = 1;

% 
% Identification-Confusion Data
%
% each row is a stimulus (S1-S16)
% each column is a response (R1-R16)
%
% data are the frequencies with which each stimulus
% was identified with each response

Nstim = 16;
Nresp = 16;

obs = [94  34   0   0  66  15   0   0   4   1   0   0   0   0   0   0;
       13  85  34   1  12  35  20   3   1   1   0   0   0   0   0   0;
        0  15  79  30   2   9  36  17   0   1   2   0   0   0   0   0;
        0   3  28 117   0   0  16  36   0   0   2   2   0   0   0   0;
       11   8   0   0 121  22   2   0  46   6   0   0   3   0   0   0;
        0  14   9   0  16  47  38   0   8  21  12   2   0   0   0   0;
        0   0  15  10   0  11  68  39   0   2  17   9   0   0   0   0;
        0   0   5  24   0   2  33  95   0   0  14  22   0   0   0   0;
        0   0   0   0  18   4   0   0 135  24   3   0  31   4   0   0;
        0   0   0   0   6  19  11   3  29  79  32   5   6  15  12   0;
        0   0   0   0   0   2  17  21   0  22  83  55   0   2  23   3;
        0   0   0   0   0   0   4  39   0   5  28 102   0   0   6  15;
        0   0   0   0   1   0   1   0  35   7   0   0 105  32   1   0;
        0   0   0   0   0   0   0   0  10  31  16   0  10  88  30   3;
        0   0   0   0   0   0   0   0   0   7  23  27   0  19  97  32;
        0   0   0   0   0   0   1   0   0   1  12  57   0   0  29  95
       ];

% Give the fit for the saturated perfect-fitting model (lnL, SSE, and
% %Var). This gives the upper bound on how well any model could fit 
% the observed data. How many free parameters are there in the 
% perfect-fitting models? Create a appropriately labeled plot of 
% observed versus predicted identification frequencies.

% saturated model assumes assumes that the prd = obs
% there is a faster way to do this, but this is simplest to understand

Npres = sum(obs,2);   % total presentations per stimulus
for i=1:Nstim
    for j=1:Nresp
        p_prd(i,j) = obs(i,j)/Npres(i);
        f_prd(i,j) = obs(i,j);
    end
end
lnL = log_likelihood(obs, p_prd);
fprintf('saturated lnL = %g\n', lnL);

sse = sse_fit(obs, f_prd);
fprintf('saturated sse = %g\n', sse); 

pervar = pervar_fit(obs, f_prd);
fprintf('saturated %%var = %g\n', pervar*100); 

% number of free parameters is Nstim x (Nresp-1)
% it's Nresp-1 because either the probabities must sum to 1
% or the frequencies must add up to the total number of
% presentations of stimulus i
fprintf('saturated # free parameters = %g\n', Nstim*(Nresp-1)); 

figure(FigIndex);
FigIndex = FigIndex + 1;
plot(f_prd, obs, 'or');
title('Saturated');
xlabel('prd');
ylabel('obs');
axis([0 max([max(f_prd) max(obs)]) 0 max([max(f_prd) max(obs)])]);
axis square;

% Give the fit for the null model (lnL, SSE, and %Var). This 
% does not really give the lower bound on how poorly any model 
% could fit the data, but no plausible model would ever fit 
% worse than this. How many free parameters are there in the 
% null model? Create a appropriately labeled plot of observed 
% versus predicted identification frequencies.

Npres = sum(obs,2);   % total presentations per stimulus
for i=1:Nstim
    for j=1:Nresp
        p_prd(i,j) = 1/Nresp;
        f_prd(i,j) = Npres(i)/Nresp;
    end
end
lnL = log_likelihood(obs, p_prd);
fprintf('null lnL = %g\n', lnL);

sse = sse_fit(obs, f_prd);
fprintf('null sse = %g\n', sse); 

pervar = pervar_fit(obs, f_prd);
fprintf('null %%var = %g\n', pervar*100); 

fprintf('null # free parameters = %g\n', 0); 

figure(FigIndex);
FigIndex = FigIndex + 1;
plot(f_prd, obs, 'or');
title('Null');
xlabel('prd');
ylabel('obs');
axis([0 max([max(f_prd) max(obs)]) 0 max([max(f_prd) max(obs)])]);
axis square;

% Now, fit the basic similarity-choice model to the observed data. 
% Recall that for this model, there is a bias for every response 
% bj. And there is a similarity for each pair of stimuli sij, where 
% sij= sji and sii=1. 
%
% Report the maximum likelihood parameters found by using the 
% Hooke and Jeeves hill-climbing algorithm. Report the fit values 
% (lnL, SSE, and %Var). How many free parameters are there? Does 
% the similarity-choice model fit significantly worse than the 
% saturated model? Create a appropriately labeled plot of 
% observed versus predicted identification frequencies.

% initialize the bias parameters to random numbers
% we normalize them to sum to 1 in the model
bias = rand(1,Nresp);

% initialize the sim parameters to random number (0..1)
% we'll worry about setting sii=1 and sji=sij later
sim = rand(Nstim, Nresp);

% fill in ParInit with the parameter values
index = 0;
for i=1:Nresp
    index = index+1;
    parInit(index) = bias(i);
end
for i=1:Nstim
    for j=1:i-1
        index = index+1;
        parInit(index) = sim(i,j);
    end
end

global Data;
Data = obs;

% note that index now has the total number of parameters
parLow  = zeros(1,index);
parHigh = ones(1,index);
parInc  = ones(1,index) * .05;

[HOOK_fit,HOOK_pos,HOOK_path]=hook('myscm',parInit,parLow,parHigh,parInc,parInc/10);

index = 0;
for i=1:Nresp
    index = index+1;
    bias(i) = HOOK_pos(index);
end
sumbias = sum(bias);
bias = bias / sumbias;

for i=1:Nstim
    sim(i,i) = 1;
    for j=1:i-1
        index = index+1;
        sim(i,j) = HOOK_pos(index);
        sim(j,i) = sim(i,j);
    end
end

% get predictions for the scm
p_prd = gen_scm(bias, sim);

% create f_prd (predicted frequency) by multiplying by number of presentations
Npres = sum(obs,2);
for i=1:Nstim
    for j=1:Nresp
        f_prd(i,j) = p_prd(i,j)*Npres(i);
    end
end

lnL = log_likelihood(obs, p_prd);
fprintf('scm lnL = %g\n', lnL);

sse = sse_fit(obs, f_prd);
fprintf('scm sse = %g\n', sse); 

pervar = pervar_fit(obs, f_prd);
fprintf('scm %%var = %g\n', pervar*100); 

fprintf('scm # free parameters = %g\n', (Nresp-1)+(Nstim*(Nstim-1)/2)); 

figure(FigIndex);
FigIndex = FigIndex + 1;
plot(f_prd, obs, 'or');
title('SCM');
xlabel('prd');
ylabel('obs');
axis([0 max([max(f_prd) max(obs)]) 0 max([max(f_prd) max(obs)])]);
axis square;

% Now, fit the MDS-choice model to the observed data. Recall 
% that in this model, rather than having free similarity 
% parameters, similarity is defined as an exponentially-
% decreasing function of distances between points. The point 
% represent the locations of each object in psychological space. 
% For this example, assume an unconstrained two-dimensional space. 
% That is, each stimulus is represented by an (x,y) position in that space.
% where im is the value of object i on dimension m and jm is the value 
% of object j on dimension m. You can set c equal to 1 without loss 
% of generality. Assume r=1. Report the maximum likelihood parameters 
% found by using the Hooke and Jeeves hill-climbing algorithm. Report 
% the fit values (lnL, SSE, and %Var). How many free parameters are 
% there? Does the MDS-choice model fit significantly worse than the 
% similarity-choice model? Create a appropriately labeled plot of 
% observed versus predicted identification frequencies.

% initialize the bias parameters to random numbers
% we normalize them to sum to 1 in the model
bias = rand(1,Nresp);

Ndim = 2;

% initialize the stimulus coordinates to random numbers
coord = rand(Nstim, Ndim);

clear parInit parLow parHigh parInc;

index = 0;
for i=1:Nresp
    index = index+1;
    parInit(index) = bias(i);
end
for i=1:Nstim
    for j=1:Ndim
        index = index+1;
        parInit(index) = coord(i,j);
    end
end

% note that index now has the total number of parameters
parLow  = -999*ones(1,index);
parHigh =  999*ones(1,index);
parInc  = ones(1,index) * .05;

[HOOK_fit,HOOK_pos,HOOK_path]=hook('mymdschoice',parInit,parLow,parHigh,parInc,parInc/10);

index = 0;
for i=1:Nresp
    index = index+1;
    bias(i) = HOOK_pos(index);
end
sumbias = sum(bias);
bias = bias / sumbias;

for i=1:Nstim
    for j=1:Ndim
        index = index+1;
        coord(i,j) = HOOK_pos(index);
    end
end

% get predictions for the scm
p_prd = gen_mdschoice(bias, coord);

% create f_prd (predicted frequency) by multiplying by number of presentations
Npres = sum(obs,2);
for i=1:Nstim
    for j=1:Nresp
        f_prd(i,j) = p_prd(i,j)*Npres(i);
    end
end

lnL = log_likelihood(obs, p_prd);
fprintf('mds-choice lnL = %g\n', lnL);

sse = sse_fit(obs, f_prd);
fprintf('mds-choice sse = %g\n', sse); 

pervar = pervar_fit(obs, f_prd);
fprintf('mds-choice %%var = %g\n', pervar*100); 

fprintf('mds-choice # free parameters = %g\n', (Nresp-1)+((Nstim-1)*Ndim)); 

figure(FigIndex);
FigIndex = FigIndex + 1;
plot(f_prd, obs, 'or');
title('MDS-Choice');
xlabel('prd');
ylabel('obs');
axis([0 max([max(f_prd) max(obs)]) 0 max([max(f_prd) max(obs)])]);
axis square;

%
% Now, the lnL could be a local minimum, so I'm going to try to loop
% a few times and save the best-fitting parameters

Ndim = 2;

clear parInit parLow parHigh parInc;

Iterations = 10;

% remember that fit returned by Hook is -lnL
Best_fit = 999999999;

for i=0:Iterations
    bias = rand(1,Nresp);
    coord = rand(Nstim, Ndim);

    index = 0;
    for i=1:Nresp
        index = index+1;
        parInit(index) = bias(i);
    end
    for i=1:Nstim
        for j=1:Ndim
            index = index+1;
            parInit(index) = coord(i,j);
        end
    end

    parLow  = -999*ones(1,index);
    parHigh =  999*ones(1,index);
    parInc  = ones(1,index) * .05;

    [HOOK_fit,HOOK_pos,HOOK_path]=hook('mymdschoice',parInit,parLow,parHigh,parInc,parInc/10);

    if (HOOK_fit < Best_fit)
        Best_fit = HOOK_fit;
        Best_pos = HOOK_pos;
    end
end

index = 0;
for i=1:Nresp
    index = index+1;
    bias(i) = Best_pos(index);
end
sumbias = sum(bias);
bias = bias / sumbias;

for i=1:Nstim
    for j=1:Ndim
        index = index+1;
        coord(i,j) = Best_pos(index);
    end
end

% get predictions for the scm
p_prd = gen_mdschoice(bias, coord);

% create f_prd (predicted frequency) by multiplying by number of presentations
Npres = sum(obs,2);
for i=1:Nstim
    for j=1:Nresp
        f_prd(i,j) = p_prd(i,j)*Npres(i);
    end
end

lnL = log_likelihood(obs, p_prd);
fprintf('mds-choice (Multiple Starting Points) lnL = %g\n', lnL);

sse = sse_fit(obs, f_prd);
fprintf('mds-choice (Multiple Starting Points) sse = %g\n', sse); 

pervar = pervar_fit(obs, f_prd);
fprintf('mds-choice (Multiple Starting Points) %%var = %g\n', pervar*100); 

fprintf('mds-choice (Multiple Starting Points) # free parameters = %g\n', (Nresp-1)+((Nstim-1)*Ndim)); 

figure(FigIndex);
FigIndex = FigIndex + 1;
plot(f_prd, obs, 'or');
title('MDS-Choice (Multiple Starting Points)');
xlabel('prd');
ylabel('obs');
axis([0 max([max(f_prd) max(obs)]) 0 max([max(f_prd) max(obs)])]);
axis square;

% Now, I would like you to consider some kind of special case of the
% MDS-choice model that can significantly reduce the number of free 
% parameters. Describe the model and justify your selection. Report 
% the maximum likelihood parameters found by using the Hooke and 
% Jeeves hill-climbing algorithm. Report the fit values (lnL, SSE, 
% and %Var). How many free parameters are there? Does this special 
% case fit significantly worse than the similarity-choice model? 
% Create a appropriately labeled plot of observed versus predicted 
% identification frequencies.
%
% In this special case, I will assume a regular grid of locations for
% objects. There are four increments along dimension 1 (nvals = 4), and
% four increments along dimension 2 (nvals = 2). So, a object 1 would
% be represented by (x1, y1) and object 5 would be represented by (x1,y2)
% without loss of generality, x1 can be set to 0 and y1 can be set to 0,
% but I'll let them vary in these fits

Nvals = 4;

clear parInit parLow parHigh parInc;

Iterations = 10;

% remember that fit returned by Hook is -lnL
Best_fit = 999999999;

for i=0:Iterations
    bias = rand(1,Nresp);
    xvals = rand(1,Nvals);
    yvals = rand(1,Nvals);

    index = 0;
    for i=1:Nresp
        index = index+1;
        parInit(index) = bias(i);
    end
    for i=1:Nvals
        index = index+1;
        parInit(index) = xvals(i);
    end
    for i=1:Nvals
        index = index+1;
        parInit(index) = yvals(i);
    end

    parLow  = -999*ones(1,index);
    parHigh =  999*ones(1,index);
    parInc  = ones(1,index) * .05;

    [HOOK_fit,HOOK_pos,HOOK_path]=hook('mymdschoice_ver2',parInit,parLow,parHigh,parInc,parInc/10);

    if (HOOK_fit < Best_fit)
        Best_fit = HOOK_fit;
        Best_pos = HOOK_pos;
    end
end

index = 0;
for i=1:Nresp
    index = index+1;
    bias(i) = Best_pos(index);
end
sumbias = sum(bias);
bias = bias / sumbias;

for i=1:Nvals
    index = index+1;
    xvals(i) = Best_pos(index);
end
for i=1:Nvals
    index = index+1;
    yvals(i) = Best_pos(index);
end

Stim_Counter = 1;
for i=1:Nvals
    for j=1:Nvals
        coord(Stim_Counter, 1) = xvals(i);
        coord(Stim_Counter, 2) = yvals(j);
        Stim_Counter = Stim_Counter+1;
    end
end

% get predictions for the scm
p_prd = gen_mdschoice(bias, coord);

% create f_prd (predicted frequency) by multiplying by number of presentations
Npres = sum(obs,2);
for i=1:Nstim
    for j=1:Nresp
        f_prd(i,j) = p_prd(i,j)*Npres(i);
    end
end

lnL = log_likelihood(obs, p_prd);
fprintf('mds-choice ver 2 lnL = %g\n', lnL);

sse = sse_fit(obs, f_prd);
fprintf('mds-choice ver 2 sse = %g\n', sse); 

pervar = pervar_fit(obs, f_prd);
fprintf('mds-choice ver 2 %%var = %g\n', pervar*100); 

fprintf('mds-choice ver 2 # free parameters = %g\n', (Nresp-1)+((Nstim-1)*Ndim)); 

figure(FigIndex);
FigIndex = FigIndex + 1;
plot(f_prd, obs, 'or');
title('MDS-Choice ver 2');
xlabel('prd');
ylabel('obs');
axis([0 max([max(f_prd) max(obs)]) 0 max([max(f_prd) max(obs)])]);
axis square;
