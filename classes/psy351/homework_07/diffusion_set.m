classdef diffusion_set
  properties
    iterations = 100;
    print_paths = 15;
    hue = 0;          % red lines
    a   = 0.200;      % upper boundary (lower boundary is 0)
    z   = 0.100;      % starting point
    sz  = 0.0;        % variability in starting point from trial to trial
    nu  = 0.10;       % mean drift rate
    eta = 0.0;        % variability in drift rate from trial to trial
    Ter = 0.200;      % non-decisional time
    st  = 0.0;        % variability in TR from trial to trial
    s2  = .01;        % diffusion coeffient is amount of within-trial noise

    diffusions = [];
    min_tr = NaN;
    max_time = NaN;
  end
  methods

   function max_time = get.max_time(obj)
     if isnan(obj.max_time)
       diffusions = obj.diffusions;
       obj.max_time = 0;
       for i = 1:size(diffusions, 2)
         obj.max_time = max(obj.max_time, diffusions{i}.time(end));
       end
     end
     max_time = obj.max_time;
   end
    
   function min_tr = get.min_tr(obj)
     if isnan(obj.min_tr)
       fprintf('\nCalculating min tr: %d / %d\n\n', obj.min_tr, isnan(obj.min_tr));
       diffusions = obj.diffusions;
       obj.min_tr = obj.max_time;
       for i = 1:size(diffusions, 2)
         obj.min_tr = min(obj.min_tr, diffusions{i}.time(1));
       end
       fprintf('\nCalculated min tr: %d / %d\n\n', obj.min_tr, isnan(obj.min_tr));
     end
     min_tr = obj.min_tr;
   end

   % don't know how to call a getter from within another getter
   function diffusions = get.diffusions(obj)
     if isempty(obj.diffusions)
       fprintf('\nCalculating diffusions: %d\n\n', isempty(obj.diffusions));
       Ter_lo = obj.Ter - obj.st / 2;
       Ter_hi = obj.Ter + obj.st / 2;

       z_lo = obj.z - obj.sz / 2;
       z_hi = obj.z + obj.sz / 2;

       means = obj.nu + obj.eta * randn(obj.iterations, 1);

       obj.diffusions = {};
       for i = 1:obj.iterations
         TR =  Ter_lo + rand * (Ter_hi - Ter_lo);
         ZZ = z_lo + rand * (z_hi - z_lo);
         obj.diffusions{i} = ...
             diffusion_simulation_path(means(i), obj.s2, TR, obj.a, ZZ);
       end
       fprintf('\nCalculated diffusions: %d\n\n', isempty(obj.diffusions));
       obj.min_tr = NaN;
       obj.max_time = NaN;
     end
     diffusions = obj.diffusions;
   end

   function ref = subsref(obj, index)
     fprintf('\nGetting Index: %d\n\n', size(index.subs));
     ref = obj.diffusions{index.subs};
   end

%     function obj = set.iterations(obj, param)
%       if obj.iterations ~= param
%         obj.iterations = param;
%         obj.diffusions = [];
%       end
%     end

%     function obj = set.a(obj, param)
%       if obj.a ~= param
%         obj.a = param;
%         obj.diffusions = [];
%       end
%     end

%     function obj = set.z(obj, param)
%       if obj.z ~= param
%         obj.z = param;
%         obj.diffusions = [];
%       end
%     end

%     function obj = set.sz(obj, param)
%       if obj.sz ~= param
%         obj.sz = param;
%         obj.diffusions = [];
%       end
%     end

%     function obj = set.nu(obj, param)
%       if obj.nu ~= param
%         obj.nu = param;
%         obj.diffusions = [];
%       end
%     end

%     function obj = set.eta(obj, param)
%       if obj.eta ~= param
%         obj.eta = param;
%         obj.diffusions = [];
%       end
%     end

%     function obj = set.Ter(obj, param)
%       if obj.Ter ~= param
%         obj.Ter = param;
%         obj.diffusions = [];
%       end
%     end

%     function obj = set.st(obj, param)
%       if obj.st ~= param
%         obj.st = param;
%         obj.diffusions = [];
%       end
%     end

%     function obj = set.s2(obj, param)
%       if obj.s2 ~= param
%         obj.s2 = param;
%         obj.diffusions = [];
%       end
%     end
  end
end
