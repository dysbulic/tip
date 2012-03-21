function error = mymodel_logistic(params)
%

% get the data variable that you created in the main routine
% before calling HOOK
global Data;
global X;

alpha  = params(1);
beta   = params(2);
lambda = params(3);
gamma  = params(4);

for i=1:length(X)
    p_prd(i,1) = (gamma + (1 - gamma - lambda) .* ...
                  (1 ./ (1 + exp(-(X(i) - alpha) ./ beta))));
    p_prd(i,2) = 1 - p_prd(i,1);
end
    
lnL = log_likelihood(Data, p_prd);

% return -lnL because minimizing -lnL is the same as
% maximizing lnL
error = -lnL;

