function y=scm(Si,Rj,sim,bias)
% scm
% Si    Stimulus i
% Rj    Response j
% sim   similarity matrix
% bias  bias
%
denom = 0.0;
for j=1:length(bias)
    denom = denom + bias(j) * sim(Si, j);
end
y = bias(Rj) * sim(Si, Rj) / denom;


