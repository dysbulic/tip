% Author: Will Holcomb <wholcomb@gmail.com>
% Date: 09/2008
%

function dim_sim = dimensional_similarity(object_1, object_2, ...
                                          attentions, sensitivity, ...
                                          distance_metric)
  p = 2;
  mse = abs(object_1 - object_2) .^ distance_metric;
  distance = sum((attentions .* mse) .^ (1 / distance_metric));
  dim_sim = exp(-sensitivity * distance ^ p);
