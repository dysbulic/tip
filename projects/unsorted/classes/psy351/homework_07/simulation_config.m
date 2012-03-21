classdef simulation_config
properties
  iterations = 100;
  hue = 0;          % red lines
  a   = 0.200;      % upper boundary (lower boundary is 0)
  z   = 0.100;      % starting point
  sz  = 0.0;        % variability in starting point from trial to trial
  nu  = 0.10;       % mean drift rate
  eta = 0.0;        % variability in drift rate from trial to trial
  Ter = 0.200;      % non-decisional time
  st  = 0.0;        % variability in TR from trial to trial
  s2  = .01;        % diffusion coeffient is amount of within-trial noise
end
end
