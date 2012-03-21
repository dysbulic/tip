function y=similarity(c,w,stim1,stim2,r)
% similarity
% c     sensitivity
% w     attention weight parameter
% stim1 stimulus 1
% stim2 stimulus 2
% r     distance metric
%
y=exp(-c.*((sum(w.*abs(stim1-stim2).^r)).^(1/r)));


