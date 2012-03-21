function p_prd = gen_scm(bias, sim)
% one bias per n responses
% full nxn similarity matrix

nstim = length(bias);

% each stimulus
for i=1:nstim
    % each response
    sumdenom = sum(bias .* sim(i,:));
    for j=1:nstim
        p_prd(i,j) = bias(j)*sim(i,j)/sumdenom;
    end
end

