function F = lnfactorial(X)
%lnfactorial
%   log factorial of X (matrix)
%
% F = lnfactorial(X)
%

[n,d] = size(X);

if (d ~= 1)
    fprintf('Multidimensional Data Invalid!\n');
    F=0;
    return;
end

F = zeros(n,1);
for i=1:n
    if X(i) == 0
        F(i) = 0;
    else
        F(i) = sum(log(1:X(i)));
    end
end

