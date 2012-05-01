% Author: Will Holcomb <will.holcomb@vanderbilt.edu>
% Date: September 2008
%
% PSY-351 Homework #4

% Psychometric Function Example
%
% illustration of bootstrapping

global DATA;
global X;

X = [ 0.505; 0.800; 1.073; 1.382; 1.782; 1.982; 2.503; 3.491; ];
observations = [ 27 23; 21 29; 24 26; 30 20; 37 13; 36 14; 48 2; 49 1; ];

Data = observations;

% First, I want you to explore correct RTs and error RTs. In class
% today, I only looked at response time distributions when you hit the
% "a" boundary. These are correct responses. But I want you to compare
% RT distributions for correct ("a" boundary) and error ("0" boundary)
% responses.

config = simulation_config;
print_paths = 15;

config.iterations = 5000;

config.a   = 0.200;      % upper boundary (lower boundary is 0)
config.z   = 0.100;      % starting point
config.sz  = 0.0;        % variability in starting point from trial to trial
config.nu  = 0.10;       % mean drift rate
config.eta = 0.0;        % variability in drift rate from trial to trial
config.Ter = 0.200;      % non-decisional time
config.st  = 0.0;        % variabilit in TR from trial to trial
config.s2  = .01;        % diffusion coeffient is amount of within-trial noise

diffusions = generate_diffusions(config);

plot2svg('homework_07.problem_1_paths.svg', ...
         plot_diffusions_paths(diffusions, config, print_paths));
plot2svg('homework_07.problem_1_cdf.svg', ...
         plot_diffusions_cdf(diffusions));
plot2svg('homework_07.problem_1_pdf.svg', ...
         plot_diffusions_pdf(diffusions));

% % Next, try:

config.eta = 0.20;       % variability in drift rate from trial to trial

diffusions = generate_diffusions(config);

plot2svg('homework_07.problem_2_paths.svg', ...
         plot_diffusions_paths(diffusions, config, print_paths));
plot2svg('homework_07.problem_2_cdf.svg', ...
         plot_diffusions_cdf(diffusions));
plot2svg('homework_07.problem_2_pdf.svg', ...
         plot_diffusions_pdf(diffusions));

% % Finally, try:

config.eta = 0.0;        % variability in drift rate from trial to trial
config.sz  = 0.09;       % variability in starting point from trial to trial

diffusions = generate_diffusions(config);

plot2svg('homework_07.problem_3_paths.svg', ...
         plot_diffusions_paths(diffusions, config, print_paths));
plot2svg('homework_07.problem_3_cdf.svg', ...
         plot_diffusions_cdf(diffusions));
plot2svg('homework_07.problem_3_pdf.svg', ...
         plot_diffusions_pdf(diffusions));

% % Use these parameters:

config.a   = 0.400;      % upper boundary (lower boundary is 0)
config.z   = 0.200;      % starting point
config.sz  = 0.05;       % variability in starting point from trial to trial
config.eta = 0.200;      % variability in drift rate from trial to trial
config.Ter = 0.200;      % non-decisional time
config.st  = 0.05;       % variabilit in TR from trial to trial
config.s2  = .01;        % diffusion coeffient is amount of within-trial noise

configs{1} = config;
configs{1}.nu = .2;
diffusion_set{1} = generate_diffusions(configs{1});
configs{2} = config;
configs{2}.nu = .3;
diffusion_set{2} = generate_diffusions(configs{2});
configs{3} = config;
configs{3}.nu = .5;
diffusion_set{3} = generate_diffusions(configs{3});

plot2svg('homework_07.problem_4_pdf.svg', ...
         plot_multi_diffusions_pdf(diffusion_set, configs, 'nu'));


% % Find the pdf for nu=.2, nu=.3, and nu=.5. Plot all three pdfs on the same graph.
