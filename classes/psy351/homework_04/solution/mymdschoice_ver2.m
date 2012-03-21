function error = mymdschoice_ver2(params)
%

% get the data
global Data;

Nstim = 16;
Nresp = 16;

Nvals = 4;

% get the parameters out of params
index = 0;
for i=1:Nresp
    index = index+1;
    bias(i) = abs(params(index));
end
sumbias = sum(bias);
bias = bias / sumbias;

for i=1:Nvals
    index = index+1;
    xvals(i) = params(index);
end
for i=1:Nvals
    index = index+1;
    yvals(i) = params(index);
end

Stim_Counter = 1;
for i=1:Nvals
    for j=1:Nvals
        coord(Stim_Counter, 1) = xvals(i);
        coord(Stim_Counter, 2) = yvals(j);
        Stim_Counter = Stim_Counter+1;
    end
end

% get predictions for the scm
p_prd = gen_mdschoice(bias, coord);

lnL = log_likelihood(Data, p_prd);

error = -lnL;
