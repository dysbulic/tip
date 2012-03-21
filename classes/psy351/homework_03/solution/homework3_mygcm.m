function error = homework3_mygcm(params)
%

% get the data
global Data;

% generate the predictions
A = [1 1;
     2 2;
     3 2;
     4 1;
    ];
B = [1 4;
     2 3;
     3 3;
     4 4
     ];
All = [1 1;
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
       4 4;
     ];
categories = {A B};

beta = [params(1) 1-params(1)];
w = [params(2) 1-params(2)];
c = params(3);
r = 1;

for i=1:length(All)
    p = gcm(c,w,beta,All(i,:),categories,r);
    Pred(i) = p(1);
end

% sum of squared error
sse = sum((Data - Pred) .^ 2);

error = sse;

