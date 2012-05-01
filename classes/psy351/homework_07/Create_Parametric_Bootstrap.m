function BootData = Create_Parametric_Bootstrap(X, Ntrials, alpha, beta, lambda, gamma)
%

BootData = zeros(length(X),2);

for i=1:length(X)
    for j=1:Ntrials
        
        psy = gamma + (1-gamma-lambda).*(1-exp(-(X(i)./beta).^alpha));
        
        rnd = rand();
        
        if rnd < psy
            BootData(i,1) = BootData(i,1) + 1;
        else
            BootData(i,2) = BootData(i,2) + 1;
        end
        
    end 
end
