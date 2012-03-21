global RangeLimit;
RangeLimit = 4;

parInit = [1 -2];
parInc  = [0.1 0.1];
view = [12 38];

set(0, 'DefaultFigureVisible', 'off');

plot_error('Error1', parInit, parInc, view);
plot_error('Error1', parInit, [0.2 0.2], view);

parInit = [-2 0];

plot_error('Error1', parInit, parInc, view);
plot_error('Error1', [-2 0.5], parInc, view);

RangeLimit = 10;
plot_error('Error1', parInit, parInc, view);

RangeLimit = 4;

view = [-42 32];

plot_error('Error2', [0 -1.5], parInc, view);
plot_error('Error2', [0 -1.6], parInc, view);
plot_error('Error2', [1.6 -1.6], parInc, view);

% Question #2

parInit = [0 0 0];
parLow  = [0 0 0];
parHigh = [1 1 100];
parInc  = [.01 .01 .1];

[HOOK_fit, HOOK_pos, hook_path] = hook('var_bwc_gcm', parInit, parLow, parHigh, parInc, parInc / 10);

fprintf('Best Fit: b = %.4f; w = %.4f; c = %.4f\n', HOOK_pos(1), HOOK_pos(2), HOOK_pos(3));