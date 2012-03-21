% Author: Will Holcomb <will.holcomb@vanderbilt.edu>
% Date: September 2008
%
% Sum of a list of natural logarithm factorials
function lnfacsum = lnfacsum(list)
  % flatten the list
  list = list(:);
  for i = 1:size(list)
    list(i) = sum(log(1:max(1, list(i))));
  end
  %list(list == -Inf) = 0;
  lnfacsum = sum(list);
