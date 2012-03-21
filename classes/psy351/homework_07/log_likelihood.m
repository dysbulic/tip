function lnL = log_likelihood(obs, prd)
% obs and prd are nxm arrays (n stimuli, m responses)
% obs is frequency data Freq(Rj|Si)
% prd is predicted probabilities P(Rj|Si)

nstim = size(obs,1);
nresp = size(obs,2);

% this is not the fastest way to code this
% but it's closest to the equations I gave you

lnL = 0;

% N! term
for i=1:nstim
    N = sum(obs(i,:));
    lnL = lnL + lnfactorial(N);
end

% each x! term
for i=1:nstim
    for j=1:nresp
        lnL = lnL - lnfactorial(obs(i,j));
    end
end

% epsilon to protect from rare cases of 0 prd's
epsilon = 0.0000000001;

% each x^p term
for i=1:nstim
    for j=1:nresp
        lnL = lnL + obs(i,j)*log(prd(i,j)+epsilon);
    end
end

