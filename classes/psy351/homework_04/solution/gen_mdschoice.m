function p_prd = gen_mdschoice(bias, coord)
% one bias per n responses
% full nxn similarity matrix

nstim = length(bias);

for i=1:nstim
    for j=1:nstim;
        dist = sum(abs(coord(i,:) - coord(j,:)));
        sim(i,j) = exp(-dist);
    end
end

% each stimulus
for i=1:nstim
    % each response
    sumdenom = sum(bias .* sim(i,:));
    for j=1:nstim
        p_prd(i,j) = bias(j)*sim(i,j)/sumdenom;
    end
end

