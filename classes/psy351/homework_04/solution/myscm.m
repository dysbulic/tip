function error = myscm(params)
%

% get the data
global Data;

Nstim = 16;
Nresp = 16;

% get the parameters out of params
index = 0;
for i=1:Nresp
    index = index+1;
    bias(i) = params(index);
end
sumbias = sum(bias);
bias = bias / sumbias;

for i=1:Nstim
    sim(i,i) = 1;
    for j=1:i-1
        index = index+1;
        sim(i,j) = params(index);
        sim(j,i) = sim(i,j);
    end
end

% get predictions for the scm
p_prd = gen_scm(bias, sim);

lnL = log_likelihood(Data, p_prd);

error = -lnL;
