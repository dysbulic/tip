function error = mymodel_1(params)
%

% get the data
global Data;

% generate the predictions

A = [1 1;
     2 2
    ];
B = [1 2;
     2 1
    ];
categories = {A B};

% here are some made-up parameters
beta = [.5 .5];
w = params;
c = 1;
r = 1;

index = 1;
for i=1:length(A)
    p = gcm(c,w,beta,A(i,:),categories,r);
    Pred(index) = p(1);
    index = index + 1;
end
for i=1:length(B)
    p = gcm(c,w,beta,B(i,:),categories,r);
    Pred(index) = p(1);
    index = index + 1;
end

sse = sum((Data - Pred) .^ 2);

error = sse;

