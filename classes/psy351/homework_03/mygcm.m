function error = mygcm(params)
%

% get the data
global Data;

% generate the predictions
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
for i=1:length(T)
    p = gcm(c,w,beta,T(i,:),categories,r);
    Pred(index) = p(1);
    index = index + 1;
end

% sum of squared error
sse = sum((Data - Pred) .^ 2);

error = sse;

