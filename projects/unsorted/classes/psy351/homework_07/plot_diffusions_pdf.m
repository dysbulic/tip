% Plot probability distribution

function [fig] = plot_diffusions_pdf(diffusions)

num_bins = 20;
bar_width = 1;
iterations = size(diffusions, 2);

final_time = zeros(size(diffusions));
final_evidence = zeros(size(diffusions));
for i = 1:iterations
  final_time(i) = diffusions{i}.time(end);
  final_evidence(i) = diffusions{i}.evidence(end);
end

max_time = max(final_time);

min_tr = max_time;
for i = 1:iterations
  min_tr = min(min_tr, diffusions{i}.time(1));
end

success_times = sort(final_time(final_evidence > 0));
error_times = sort(final_time(final_evidence <= 0));

[success_n, success_x] = hist(success_times, num_bins);
[error_n, error_x] = hist(error_times, num_bins);

success_n = success_n / iterations;
error_n = error_n / iterations;

fig = figure();
xlabel('Time');
ylabel('Evidence');
title('Diffusion Probabiltity Distribution');
axis([min_tr max_time 0 (max(max(success_n), max(error_n)) * 1.1)]);

hold on;
br = bar(success_x, success_n, bar_width);
set(br, 'FaceColor', [0, .9, .4], 'EdgeColor', 'k')
br = bar(error_x, error_n, bar_width);
set(br, 'FaceColor', [.9, 0, .4], 'EdgeColor', 'k')
legend('Success', 'Error');
