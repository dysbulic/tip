function r = pearson(x,y)
% pearson correlation between x and y
%
meanx = mean(x);	%Compute mean value of X
meany = mean(y);	%Compute mean value of Y

%Compute the arguments that go into the mathematical formula of R
sx2   = sum((x-meanx).^2);  
sy2   = sum((y-meany).^2);  
sxy   = sum((x-meanx).*(y-meany));

% Mathematical definition of Pearson's product moment correlation coefficient
r = sxy./sqrt(sx2*sy2);  
