% Author: Will Holcomb <will.holcomb@vanderbilt.edu>
% Date: September 2008
%
% PSY-351 Homework #4

% #1.a. Graph data using existant Wiebull Function

global Data;
global X;

num_trials = 500;

X = [ 0.800; 1.073; 1.382; 1.782; 1.982; 3.491; ];
observations = [ 250 250; 310 190; 380 120; 430 70; 500 0; 490 10; ];
Data = observations;

% Range and granularity over which to display the result function
display_range = floor(min(X)):.1:ceil(max(X));

%
% Fit Wiebull function
%

% initialize parameters
alpha  = 1;
beta   = 1;
lambda = 0;
gamma  = .5;

parInit = [alpha    beta lambda gamma];
parLow  = [    0 .000001      0     0];
parHigh = [  999     999      1     1];
parInc  = [  .05     .05    .01   .01];

[HOOK_wiebull_lnl, HOOK_fit_wiebull, HOOK_path] = ...
    hook('mymodel_psy', parInit, parLow, parHigh, parInc, parInc / 10);

% get best-fitting parameters
fit_alpha  = HOOK_fit_wiebull(1);
fit_beta   = HOOK_fit_wiebull(2);
fit_lambda = HOOK_fit_wiebull(3);
fit_gamma  = HOOK_fit_wiebull(4);

% generate the psychometric function
psy_fit_wiebull = (fit_gamma + (1 - fit_gamma - fit_lambda) .* ...
                   (1 - exp(-(display_range ./ fit_beta) .^ fit_alpha)));

% generate a figure with observed and predicted
fig = figure();
plot(display_range, psy_fit_wiebull, '-r', ...
     X, Data(:, 1) ./ (Data(:, 1) + Data(:, 2)), 'ob');
xlabel('Intensity');
ylabel('P(Correct)');
title('Wiebull Fit Psychometric Function');
axis([min(display_range) max(display_range) 0 1]);
text(max(display_range) * .75, .5, sprintf('lnL = %g', -HOOK_wiebull_lnl));

plot2svg('homework_06.full_psycho.svg', fig);

%
% Repeat, but constrain lambda to 0
%

parInit = [alpha    beta lambda gamma];
parLow  = [    0 .000001      0     0];
parHigh = [  999     999      0     1];
parInc  = [  .05     .05    .01   .01];

[HOOK_fit, HOOK_pos, HOOK_path] = ...
    hook('mymodel_psy', parInit, parLow, parHigh, parInc, parInc / 10);

fit_alpha  = HOOK_pos(1);
fit_beta   = HOOK_pos(2);
fit_lambda = HOOK_pos(3);
fit_gamma  = HOOK_pos(4);

psy = (fit_gamma + (1 - fit_gamma - fit_lambda) .* ...
       (1 - exp(-(display_range ./ fit_beta) .^ fit_alpha)));

fig = figure();
plot(display_range, psy, '-r', ...
     X, Data(:, 1) ./ (Data(:, 1) + Data(:, 2)), 'ob');
xlabel('Intensity');
ylabel('P(Correct)');
title('Wiebull Fit w/ 0 Upper Padding');
axis([min(display_range) max(display_range) 0 1]);
text(max(display_range) * .75, .5, sprintf('lnL = %g', -HOOK_fit));

plot2svg('homework_06.zero_lambda.svg', fig);

%
% Repeat, but constrain gamma to 0
%

parInit = [alpha    beta lambda gamma];
parLow  = [    0 .000001      0     0];
parHigh = [  999     999      1     0];
parInc  = [  .05     .05    .01   .01];

[HOOK_fit, HOOK_pos, HOOK_path] = ...
    hook('mymodel_psy', parInit, parLow, parHigh, parInc, parInc / 10);

fit_alpha  = HOOK_pos(1);
fit_beta   = HOOK_pos(2);
fit_lambda = HOOK_pos(3);
fit_gamma  = HOOK_pos(4);

psy = (fit_gamma + (1 - fit_gamma - fit_lambda) .* ...
       (1 - exp(-(display_range ./ fit_beta) .^ fit_alpha)));

fig = figure();
plot(display_range, psy, '-r', ...
     X, Data(:, 1) ./ (Data(:, 1) + Data(:, 2)), 'ob');
xlabel('Intensity');
ylabel('P(Correct)');
title('Wiebull Fit w/ 0 Lower Bound');
axis([min(display_range) max(display_range) 0 1]);
text(max(display_range) * .75, .5, sprintf('lnL = %g', -HOOK_fit));

plot2svg('homework_06.zero_gamma.svg', fig);


%
% #2. Fit logistic function
%

parInit = [alpha    beta lambda gamma];
parLow  = [    0 .000001      0     0];
parHigh = [  999     999      1     1];
parInc  = [  .05     .05    .01   .01];

[HOOK_fit, HOOK_pos, HOOK_path] = ...
    hook('mymodel_logistic', parInit, parLow, parHigh, parInc, parInc / 10);

fit_alpha  = HOOK_pos(1);
fit_beta   = HOOK_pos(2);
fit_lambda = HOOK_pos(3);
fit_gamma  = HOOK_pos(4);

psy = (fit_gamma + (1 - fit_gamma - fit_lambda) .* ...
                  (1 ./ (1 + exp(-(display_range - fit_alpha) ./ fit_beta))));

fig = figure();
plot(display_range, psy, '-r', ...
     X, Data(:, 1) ./ (Data(:, 1) + Data(:, 2)), 'ob');
xlabel('Intensity');
ylabel('P(Correct)');
title('Logistic Psychometric Function');
axis([min(display_range) max(display_range) 0 1]);
text(max(display_range) * .75, .5, sprintf('lnL = %g', -HOOK_fit));

plot2svg('homework_06.logistic.svg', fig);

%
% #3. Perform simple bootstrap
%

% Parameter values saved from unconstrained Wiebull fit
fit_alpha  = HOOK_fit_wiebull(1);
fit_beta   = HOOK_fit_wiebull(2);
fit_lambda = HOOK_fit_wiebull(3);
fit_gamma  = HOOK_fit_wiebull(4);

% Currently don't know what the different models are to test
num_simulations = 1
for i = 1:num_simulations
  for j = 1:size(X)
    probability = (fit_gamma + (1 - fit_gamma - fit_lambda) .* ...
                   (1 - exp(-(X(j) ./ fit_beta) .^ fit_alpha)));
    rands = rand(num_trials, 1);
    outcomes(j, 1) = size(rands(rands < probability), 1);
    outcomes(j, 2) = num_trials - outcomes(j, 1);
  end
  Data = outcomes;
  [HOOK_fit_lnl, HOOK_pos, HOOK_path] = ...
      hook('mymodel_psy', parInit, parLow, parHigh, parInc, parInc / 10);
  g_square(i) = 2 * (HOOK_wiebull_lnl - HOOK_fit_lnl);
end

fit_alpha  = HOOK_pos(1);
fit_beta   = HOOK_pos(2);
fit_lambda = HOOK_pos(3);
fit_gamma  = HOOK_pos(4);

psy = (fit_gamma + (1 - fit_gamma - fit_lambda) .* ...
       (1 - exp(-(display_range ./ fit_beta) .^ fit_alpha)));

fig = figure();
plot(display_range, psy_fit_wiebull, '-r', ...
     X, observations(:, 1) ./ (observations(:, 1) + observations(:, 2)), 'ob', ...
     display_range, psy, '-g', ...
     X, outcomes(:, 1) ./ (outcomes(:, 1) + outcomes(:, 2)), '*g');
xlabel('Intensity');
ylabel('P(Correct)');
title('Generated and Fit Data from Wiebull');
axis([min(display_range) max(display_range) 0 1]);
text(max(display_range) * .75, .5, sprintf('lnL = %g', -HOOK_fit));

plot2svg('homework_06.fit_predicted.svg', fig);

fig = figure();
hist(g_square);
xlabel('G^2');
ylabel('Count');
title('Distribution of Reduced versus Saturated Model');

plot2svg('homework_06.g2_histogram.svg', fig);
