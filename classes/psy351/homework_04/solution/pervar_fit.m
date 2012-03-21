function pervar = pervar_fit(obs, prd)
% obs and prd are nxm arrays (n stimuli, m responses)
% obs is frequency data
% prd is frequency

nstim = size(obs,1);
nresp = size(obs,2);

sse = 0;
pervar = 0;
for i=1:nstim
    N = sum(obs(i,:));
    for j=1:nresp
        sse = sse + (obs(i,j)-prd(i,j))^2;
        pervar = pervar + (obs(i,j)-N/nresp)^2;
    end
end

pervar = (pervar - sse) / pervar;

