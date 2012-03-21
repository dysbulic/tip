% Author: Will Holcomb <will.holcomb@vanderbilt.edu>
% Date: September 2008
function scm_prob = pack_hook_to_scm(params)
  num_params = sqrt(size(params, 2) * 2);

  biases = params(1:num_params);
  biases = biases / sum(biases);

  similarities = ones(num_params);
  for i = 1:num_params
    for j = 1:i - 1
      index = num_params + i - 1 + j;
      similarities(i, j) = params(index);
      similarities(j, i) = params(index);
    end
  end

  scm_prob = zeros(num_params);
  for i = 1:num_params
    for j = 1:num_params
      scm_prob(i, j) = scm(i, j, biases, similarities);
    end
  end
  
