function [fig] = plot_diffusions_paths(diffusions, config, print_paths)

hue = 0; % red
iterations = min(print_paths, size(diffusions, 2));

max_time = 0;
for i = 1:iterations
  max_time = max(max_time, diffusions{i}.time(end));
end

min_tr = max_time;
for i = 1:size(diffusions, 2)
  min_tr = min(min_tr, diffusions{i}.time(1));
end

fig = figure();
xlabel('Time');
ylabel('Evidence');
title(sprintf('%d of %d Diffusion Simulations', ...
              iterations, size(diffusions, 2)));
axis([min_tr max_time -.05 config.a + .05]);

hold on;

line([min_tr max_time], [0 0], 'LineWidth', 2);
line([min_tr max_time], [config.a config.a], 'LineWidth', 2);
for i = 1:iterations
  plt = plot(diffusions{i}.time, diffusions{i}.evidence);
  set(plt, 'Color', hsv2rgb([hue, diffusions{i}.time(end) / max_time, 1]));
end
