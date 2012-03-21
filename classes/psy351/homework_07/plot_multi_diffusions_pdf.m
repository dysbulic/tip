% Plot probability distribution

function [fig] = plot_multi_diffusions_pdf(diffusion_set, configs, var_name)

bar_width = 1;

% Assume equal size sets
num_sets = size(diffusion_set, 2);
num_diffusions = size(diffusion_set{1}, 2);

final_time = zeros(num_sets, num_diffusions);
final_evidence = zeros(num_sets, num_diffusions);
for i = 1:num_sets
  for j = 1:num_diffusions
    final_time(i, j) = diffusion_set{i}{j}.time(end);
    final_evidence(i, j) = diffusion_set{i}{j}.evidence(end);
  end
end

max_time = max(final_time(:));

min_tr = max_time;
for i = 1:num_sets
  for j = 1:num_diffusions
    min_tr = min(min_tr, diffusion_set{i}{j}.time(1));
  end
end

max_success = 0;
max_error = 0;

% Put 5% in each bin
num_bins = max(1, num_diffusions * .05)

for i = 1:num_sets
  success_times{i} = sort(final_time(i, final_evidence(i) > 0));
  error_times{i} = sort(final_time(i, final_evidence(i) <= 0));
  
  [success_n{i}, success_x{i}] = hist(success_times{i}, num_bins);
  [error_n{i}, error_x{i}] = hist(error_times{i}, num_bins);
  
  success_n{i} = success_n{i} / num_diffusions;
  error_n{i} = error_n{i} / num_diffusions;
  
  max_success = max(max_success, max(success_n{i}));
  max_error = max(max_error, max(error_n{i}));
end

fig = figure();
xlabel('Time');
ylabel('Evidence');
title('Diffusion Probabiltity Distribution');
axis([min_tr max_time 0 (max(max_error, max_success) * 1.1)]);

hold on;

for i = 1:num_sets
    color = hsv2rgb([i / num_sets, .9, .9]);
    plt = plot(success_x{i}, success_n{i}, '-');
    set(plt, 'Color', color);
    plt = plot(error_x{i}, error_n{i}, '.');
    set(plt, 'Color', color);
end
legend('Success', 'Error');
