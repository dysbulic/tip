function error = mymdschoice(params)
%

% get the data
global Data;

Nstim = 16;
Nresp = 16;

Ndim = 2;

% get the parameters out of params
index = 0;
for i=1:Nresp
    index = index+1;
    bias(i) = abs(params(index));
end
sumbias = sum(bias);
bias = bias / sumbias;

for i=1:Nstim
    for j=1:Ndim
        index = index+1;
        coord(i,j) = params(index);
    end
end

% get predictions for the scm
p_prd = gen_mdschoice(bias, coord);

lnL = log_likelihood(Data, p_prd);

error = -lnL;
