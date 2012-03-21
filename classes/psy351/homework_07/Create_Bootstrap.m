function BootData = Create_Bootstrap(obs)
%
% Creates a bootstrapped dataset. Assumes that the obs 
% is organized in conditions by row and responses by columns

[Ncond Nresp] = size(obs);

% There are ways of writing this routine to be way more efficient.
% But I'm writing it this way to make the bootstrapping most obvious.

% First, we turn the frequencies of events into discrete events
% that can be sampled (i.e., if there are 50 trials in a condition,
% and if each trial can have events 1 or 2, then the array will be
% a series of 1's and 2's representing the discrete events that
% can occur.

% this is hard coded assuming only two possible responses
for i=1:Ncond
    Sample_Data(i,:) = [ones(1,obs(i,1))*1 ones(1,obs(i,2))*2];
end

% now create bootstrap by sampling from Sample_Data with replacement
BootData = zeros(Ncond,Nresp);
for i=1:Ncond
    ntrials = obs(i,1)+obs(i,2);
    for k=1:ntrials
        % rand returns a random number between 0 and 1
        which_trial = ceil(rand * ntrials);
        
        % what was the response on that trial
        which_resp = Sample_Data(i,which_trial);
        
        % increment Bootstrap with one more of that kind
        BootData(i,which_resp) = BootData(i,which_resp) + 1;
    end
end


