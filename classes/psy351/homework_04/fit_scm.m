% Author: Will Holcomb <will.holcomb@vanderbilt.edu>
% Date: September 2008
%
% Unpack the column vector from the hook function and use it to fit
% a ln likelihood of a set of observations from the similarity
% choice model
function lnL = fit_scm(params)
  run ./data.m;
  lnL = -ln_likelihood(obs, pack_hook_to_scm(params));
