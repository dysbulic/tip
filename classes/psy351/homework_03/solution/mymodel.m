function error = mymodel(params)
%

% get the data
global Data;

% generate the predictions

% A simple category structure
A = [1 1;
     2 2;
     3 2;
     4 1
    ];
B = [1 4;
     2 3;
     3 3;
     4 4
    ];
categories = {A B};

% test stimuli
test_stimuli = [ 1 1;
                 2 1;
                 3 1;
                 4 1;
                 1 2;
                 2 2;
                 3 2;
                 4 2;
                 1 3;
                 2 3;
                 3 3;
                 4 3;
                 1 4;
                 2 4;
                 3 4;
                 4 4
                ];

% here are some made-up parameters
c = params(1);
w = [params(2) 1-params(2)];
beta = [params(3) 1-params(3)];
r = 1;

index = 1;
for i=1:length(test_stimuli)
    p = gcm(c,w,beta,test_stimuli(i,:),categories,r);
    Pred(index) = p(1);
    index = index + 1;
end

sse = sum((Data - Pred) .^ 2);

error = sse;

