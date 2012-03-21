function [time,which]=diffusion_simulation(mu,s2,TR,a,z)
% returns RT (time)
% and return which boundary hit
%       which = 0   0 (lower) boundary
%       which = 1   a (upper) boundary

time=TR;        % start the time at TR
tau=.00001;     % time per step of the diffusion
evidence=z;     % starting point

% loop until evidence is greater than or equal to a (upper boundary)
% or evidence is less than or equal to 0 (lower boundary)
while(evidence<a & evidence>0)
    
    % increment the time
    time = time + tau;

    % dx is the step size up or down.
    % s2 is the diffusion coefficient.
    % The larger s2, the larger the step up or down.
    % It needs to be scaled by the sqrt of tau for 
    % technical reasons when taking tau to zero in the limit.
    dx=sqrt(s2.*tau);

    % r is between 0 and 1
    r=rand;

    % This is the probability of moving up or down.
    % If mu is greater than 0, the diffusion tends to move up.
    % If my is less than 0, the diffusion tends to move down.
    p=0.5.*(1 + mu.*dx./s2);
    
    % if r < p then move up
    if r < p
        evidence = evidence + dx;
    % else move down
    else
        evidence = evidence - dx;
    end
end

if evidence < 0
    which = 0;
end

if evidence > a
    which = 1;
end


