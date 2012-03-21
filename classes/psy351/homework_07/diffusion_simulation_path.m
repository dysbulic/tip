function [return_value] = ...
    diffusion_simulation_path(mu, s2, TR, a, ZZ)

max_points = 100000;
time = zeros(1, max_points);
evidence = zeros(1, max_points);

% initial conditions
index = 1;
time(index) = TR;
tau = .0001;
evidence(index) = ZZ;

while((evidence(index) < a) & (evidence(index) > 0))
    index = index + 1;
    time(index) = time(index - 1) + tau;
    dx = sqrt(s2 * tau);
    p = 0.5 * (1 + mu / s2 * dx);
    
    if rand < p
        evidence(index) = evidence(index - 1) + dx;
    else
        evidence(index) = evidence(index - 1) - dx;
    end
end

return_value = diffusion_result;
% truncate arrays
return_value.evidence = evidence(1:index);
return_value.time = time(1:index);
