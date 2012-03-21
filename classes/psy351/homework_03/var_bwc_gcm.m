function error = var_bwc_gcm(guess)

obseveredCatA = [0.97 0.98 0.96 0.96 0.85 0.81 0.73 0.76 0.33 0.43 0.30 0.25 0.04 0.06 0.03 0.03];

catA = [1 1; 2 2; 3 2; 4 1 ];
catB = [1 4; 2 3; 3 3; 4 4 ];

categories = {catA catB};

beta = [guess(1) 1 - guess(1)];
weight = [guess(2) 1 - guess(2)];
c = guess(3);
r = 1;

for i = 1:4
    for j = 1:4
        p = gcm(c, weight, beta, [i j], categories, r);
        predicted((i - 1) * 4 + j) = p(1);
    end
end

% sum of squared error
error = sum((obseveredCatA - predicted) .^ 2);