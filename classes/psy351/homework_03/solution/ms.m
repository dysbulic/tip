
clear all;
close all;

% observed P(A|i) from Nosofsky, Palmeri, & McKinley (1994)
% a replication of the Medin and Schaffer (1978) experiment
obs = [.77; % A1
       .78; % A2
       .83; % A3
       .64; % A4
       .61; % A5 
       .39; % B1
       .41; % B2
       .21; % B3
       .15; % B4
       .56; % T1
       .41; % T2
       .82; % T3
       .40; % T4
       .32; % T5
       .53; % T6
       .20  % T7
       ];
obs = obs';

% set Data to fit
% this is passed to mygcm
global Data;
Data = obs;

% assuming equal bias, c=1, and r=1
% best fitting should be w = [1.321 0.399 1.277 0.671];

parInit = [1 1 1 1];
parLow  = [0 0 0 0];
parHigh = [999 999 999 999];
parInc  = [.05 .05 .05 .05];

[HOOK_fit,HOOK_pos,hook_path]=hook('mygcm',parInit,parLow,parHigh,parInc,parInc/10);

% now get model predictions using the HOOK_pos returned from hook.m
A = [1 1 1 2;
     1 2 1 2;
     1 2 1 1;
     1 1 2 1;
     2 1 1 1
    ];
B = [1 1 2 2;
     2 1 1 2;
     2 2 2 1;
     2 2 2 2
     ];
T = [1 2 2 1;
     1 2 2 2;
     1 1 1 1;
     2 2 1 2;
     2 1 2 1;
     2 2 1 1;
     2 1 2 2
     ];
categories = {A B};

% only allowing the weights to vary in these fits
beta = [.5 .5];
w = HOOK_pos;
c = 1;
r = 1;

% generate the predictions for each stimulus
index = 1;
for i=1:length(A)
    p = gcm(c,w,beta,A(i,:),categories,r);
    prd(index) = p(1);
    index = index + 1;
end
for i=1:length(B)
    p = gcm(c,w,beta,B(i,:),categories,r);
    prd(index) = p(1);
    index = index + 1;
end
for i=1:length(T)
    p = gcm(c,w,beta,T(i,:),categories,r);
    prd(index) = p(1);
    index = index + 1;
end

% create obs versus prd in bar chart
figure(1);
bar([obs' prd']);
colormap(cool);
xlabel('Stimulus');
set(gca,'xtick',1:16)
set(gca,'xticklabel',{'A1' 'A2' 'A3' 'A4' 'A5' 'B1' 'B2' 'B3' 'B4' 'T1' 'T2' 'T3' 'T4' 'T5' 'T6' 'T7'})
ylabel('P(A)');
legend('obs', 'prd');
