% Author: Will Holcomb <will.holcomb@vanderbilt.edu>
% Date: September 2008
%
% Log likelihood:
%  - observations: 2d matrix of "seen i and reported j"
%  - predictions: 2d matrix of "probability of seeing i and reporting j"
function ln_likelihood = ln_likelihood(observations, predictions)
  count_sum = lnfacsum(sum(observations, 2));
  frequence_sum = lnfacsum(observations);
  weighted_obs = observations .* log(predictions);
  weighted_obs(isnan(weighted_obs)) = 0;
  prob_sum = sum(weighted_obs(:));
  ln_likelihood = count_sum - frequence_sum + prob_sum;
