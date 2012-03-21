% Plot cumulative distribution

function [fig] = plot_diffusions_cdf(diffusions)

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

fig = figure();
xlabel('Time');
ylabel('Evidence');
title('Diffusion Cumulative Distribution');
axis([min_tr max_time 0 1]);

success_times = sort(final_time(final_evidence > 0));
error_times = sort(final_time(final_evidence <= 0));

time = min_tr:(max_time - min_tr) / max(size(success_times, 2), size(error_times, 2)):max_time;

hold on;
plot(time(1:size(success_times, 2)), success_times / max(success_times), '-g');
plot(time(1:size(error_times, 2)), error_times / max(error_times), '-r');
legend('Success', 'Error');
