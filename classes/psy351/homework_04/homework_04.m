% Author: Will Holcomb <will.holcomb@vanderbilt.edu>
% Date: September 2008
%
% PSY-351 Homework #4

% #1 Compute log limit of data

% Define observations
run ./data.m;

num_pres = size(obs, 1);

% count the number of presentations for each stimulus
presentations = sum(obs, 2);

% convert the raw counts into probabilties
for i = 1:num_pres
  for j = 1:size(obs, 2)
    prob_obs(i, j) = obs(i, j) / presentations(i);
  end
end

fprintf('\n\nSaturated ln likelihood: %f\n\n', ln_likelihood(obs, prob_obs));

fig = figure();
hold on
plot(obs, 'or');
plot(obs, '*g');
hold off
title('Saturated Model');
xlabel('Predicted');
ylabel('Observed');
plot2svg('homework_04.saturated.svg', fig);

% for the null model all guesses are the same
mean_prob = repmat(1 / size(obs, 1), size(obs));
fprintf('\n\nNull ln likelihood: %f\n\n', ln_likelihood(obs, mean_prob));

mean_pred = repmat(presentations', num_pres, 1) .* mean_prob;
mean_sse = sum(sum((obs - mean_pred) .^ 2));
fprintf('\n\nNull SSE: %f\n\n', mean_sse);

fig = figure();
hold on
plot(obs, 'or');
plot(mean_pred, '*g');
hold off
title('Null Model');
xlabel('Predicted');
ylabel('Observed');
plot2svg('homework_04.null.svg', fig);

% the similarity choice model has 16 free biases and 16^2/2 - 16
% free similarities

param_init = rand(1, num_pres + num_pres ^ 2 / 2 - num_pres);

% all values range between 0 and 1
param_min  = zeros(size(param_init));
param_max = ones(size(param_init));

% make the increment .025
param_inc  = repmat(.025, size(param_init));

fit_scm(param_init)

% use hook and jeeves to fit the data
[HOOK_lnL, HOOK_final, HOOK_path] = ...
    hook('fit_scm', param_init, param_min, param_max, param_inc, ...
         param_inc / 10);

fprintf('\n\nSCM Hook fit ln likelihood: %f\n\n', HOOK_lnL);

scm_prob = pack_hook_to_scm(HOOK_final);
scm_pred = repmat(presentations', num_pres, 1) .* scm_prob;

HOOK_sse = sum(sum((obs - scm_pred) .^ 2));
fprintf('\n\nSCM Hook fit SSE: %f\n\n', HOOK_sse);

fprintf('\n\nSCM Hook fit %% Variance: %f\n\n', ...
        (HOOK_sse - mean_sse) / mean_sse);

fig = figure();
hold on
plot(obs, 'or');
plot(scm_pred, '*g');
hold off
title('Hook Fit Similarity Choice Model');
xlabel('Predicted');
ylabel('Observed');
plot2svg('homework_04.scm.svg', fig);
