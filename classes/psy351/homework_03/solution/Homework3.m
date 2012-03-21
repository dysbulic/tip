% Homework 3
%
%

% REDO AS A SWITCH/CASE STATEMENT
%

clear all;
close all;

FigIdx = 1;
  
global RangeLimit;

for loop=1:9
    
    switch (loop)
        case 1
        % First, explore the effects of parameter increments using Error1.m. Set parInit = [1 -2]. 
        % Now run it using parInc = [0.1 0.1] versus parInc = [0.2 0.2].            
        model = 'Error1';
        RangeLimit = 4;
        parInit = [1 -2];
        parInc  = [0.1 0.1];
        
        case 2
        % versus parInc = [0.2 0.2].
        model = 'Error1';
        RangeLimit = 4;
        parInit = [1 -2];
        parInc  = [0.1 0.1];
 
        case 3
        % Try making the RangeLimit larger and see what happens to the searches.
        model = 'Error1';
        RangeLimit = 6;
        parInit = [1 -2];
        parInc  = [0.1 0.1];

        case 4
        % versus parInc = [0.2 0.2].
        model = 'Error1';
        RangeLimit = 6;
        parInit = [1 -2];
        parInc  = [0.2 0.2];
        
        case 5
        % Next, explore the effect of initial parameters. Set parInc = [0.1 0.1]. 
        % Run it using parInit = [ 2 0] versus parInit = [-2 0.5]. Do SimpleHillClimb 
        % and fminsearch and hook end up at the same minimum for both initial positions? 
        % Do they end up in the same minimum as each other? Do they find one of the 
        % three local minima of the function or do they get stuck? 
        model = 'Error1';
        RangeLimit = 4;
        parInit = [2 0];
        parInc  = [0.1 0.1];

        case 6
        % Next, explore the effect of initial parameters. Set parInc = [0.1 0.1]. 
        % Run it using parInit = [ 2 0] versus parInit = [-2 0.5]. Do SimpleHillClimb 
        % and fminsearch and hook end up at the same minimum for both initial positions? 
        % Do they end up in the same minimum as each other? Do they find one of the 
        % three local minima of the function or do they get stuck? 
        model = 'Error1';
        RangeLimit = 4;
        parInit = [-2 0.5];
        parInc  = [0.1 0.1];
 
        case 7
        % Now try Error2.m. Run this error function using three different initial values, 
        % all with parInc = [0.1 0.1]: parInit = [0 -1.5], parInit = [0 -1.6], and 
        % parInit = [1.6 -1.6]. Do SimpleHillClimb and fminsearch and hook end up at the 
        % same minimum for all initial positions? Do they end up in the same minimum as 
        % each other? Do they find one of the two local minima of the function or do they 
        % get stuck on a boundary of the allowed space? 
        model = 'Error2';
        RangeLimit = 4;
        parInit = [0 -1.5];
        parInc  = [0.1 0.1];

        case 8
        % Now try Error2.m. Run this error function using three different initial values, 
        % all with parInc = [0.1 0.1]: parInit = [0 -1.5], parInit = [0 -1.6], and 
        % parInit = [1.6 -1.6]. Do SimpleHillClimb and fminsearch and hook end up at the 
        % same minimum for all initial positions? Do they end up in the same minimum as 
        % each other? Do they find one of the two local minima of the function or do they 
        % get stuck on a boundary of the allowed space? 
        model = 'Error2';
        RangeLimit = 4;
        parInit = [0 -1.6];
        parInc  = [0.1 0.1];

        case 9
        % Now try Error2.m. Run this error function using three different initial values, 
        % all with parInc = [0.1 0.1]: parInit = [0 -1.5], parInit = [0 -1.6], and 
        % parInit = [1.6 -1.6]. Do SimpleHillClimb and fminsearch and hook end up at the 
        % same minimum for all initial positions? Do they end up in the same minimum as 
        % each other? Do they find one of the two local minima of the function or do they 
        % get stuck on a boundary of the allowed space? 
        model = 'Error2';
        RangeLimit = 4;
        parInit = [1.6 -1.6];
        parInc  = [0.1 0.1];
        
    end
    
    % set the initial conditions, boundaries, and step size for the search
    parLow  = [-RangeLimit -RangeLimit];
    parHigh = [ RangeLimit  RangeLimit];

    % simple hill-climbing algorithm
    traj = SimpleHillClimb(eval(['@' model]),parInit,parLow,parHigh,parInc);
    bestFit = traj(size(traj,1),:);

    % simplex algorithm
    options = optimset('display', 'iter', 'MaxIter', 500);
    options.Display = 'simplex';
    [bestx,fval,exitflag,output,simplex_path] = fminsearch_show(eval(['@' model]),parInit,options);
    bestFitFMINSEARCH = [bestx fval]; 

    % Hook-Jeeves
    [HOOK_fit,HOOK_pos,HOOK_path]=hook(model,parInit,parLow,parHigh,parInc,parInc/10);
    bestFitHOOK = [HOOK_pos HOOK_fit];

    % This generates the entire error surface. With most models, you
    % would never be able to do this. If you could, you would not need
    % to do a search - you'd have the complete error surface in hand.
    [X Y Z] = GridEvaluation(eval(['@' model]), parLow, parHigh, parInc);

    % first plot the 3D surface (left panel)
    figure(FigIdx);
    FigIdx = FigIdx + 1;
    subplot(1,2,1,'replace');
    surfc(X,Y,Z);
    hold on;
    plot3(traj(:,1),traj(:,2),traj(:,3),'-m','LineWidth',3);
    plot3(bestFit(1), bestFit(2), bestFit(3),'om', 'MarkerFaceColor', 'm', 'MarkerSize', 10);
    plot3(bestFitFMINSEARCH(1),bestFitFMINSEARCH(2),bestFitFMINSEARCH(3), 'sb', 'MarkerFaceColor','b','MarkerSize',10);
    hold off;
    axis([parLow(1) parHigh(1) parLow(2) parHigh(2) 0 16]) ;
    axis square;
    xlabel('Param_1') ;
    ylabel('Param_2') ;
    zlabel('Error') ;

    % then plot the contours (right panel)
    subplot(1,2,2,'replace') ;
    hold on;
    contour(X,Y,Z,20) ;
    axis([parLow(1) parHigh(1) parLow(2) parHigh(2)]) ;
    axis square;
    grid on;
    xlabel('Param_1') ;
    ylabel('Param_2') ;

    % plot the Simple Hill Climbing trajectory
    plot(traj(:,1),traj(:,2),'-m','LineWidth',3);
    plot(bestFit(1),bestFit(2),'om', 'MarkerFaceColor', 'm', 'MarkerSize', 10);

    % plot simplex path
    plot(bestFitFMINSEARCH(1),bestFitFMINSEARCH(2),'sb', 'MarkerFaceColor','b','MarkerSize',10);
    for i=1:size(simplex_path,1)
        points = simplex_path(i,:,:);
        plot_simplex_triangle(points(:,:,1), points(:,:,2), points(:,:,3));
    end

    % plot Hooke-Jeeves path
    % plot hook trajectory
    plot(HOOK_path(:,1), HOOK_path(:,2), 'g-');

    hold off;

end

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%5

% Try fitting the GCM to this data using hook. Free parameters should be the sensitivity c, 
% the dimensional attention weights w1 and w2=1-w1, and the category response biases bA 
% and bB = 1-bA. Note that this means that there are three free parameters. You will 
% need to modify the code I gave you to take these parameter dependencies into account. 
% It might be easier to use the hook.m search routine because it has min and max values 
% built into the function.

clear all;

FigIndex = 20;

data = [0.97;
	 	0.98;
	 	0.96;
	 	0.96;
	 	0.85;
	 	0.81;	
	 	0.73;
	 	0.76;
	 	0.33;
	 	0.43;
	 	0.30;
	 	0.25;
	  	0.04;
	  	0.06;
	  	0.03;
	  	0.03;
    ];

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

figure(FigIndex);
FigIndex = FigIndex+1;
hold on;
plot(A(:,1), A(:,2), 'ro');
plot(B(:,1), B(:,2), 'bx');
axis square;
xlabel('dim 1') ;
ylabel('dim 2') ;
axis([.1 4.9 .1 4.9]);
hold off;

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

% Now we are going to try to fit the gcm

global Data;

Data = data';

% reset the random number generator
rand('state', 0);

% run fits 10 times with different starting points
best_fit = 99999;
for i=1:10
    
    % starting point for parameter search
    % parInit(1) sensitivity (c)
    % parInit(2) attn weight (w1)
    % parInit(3) bias (bA)

    parInit = [2*rand() rand() rand()];

    % lowest values of parameters
    parLow  = [0 0 0];

    % highest values of parameters
    parHigh = [999 1 1];

    % parameter increment
    parInc  = [.05 .01 .01]

    % now try using Hook and Jeeves (a more sophisticated technique)
    [HOOK_fit,HOOK_pos,HOOK_path]=hook('mymodel',parInit,parLow,parHigh,parInc,parInc/10);
    bestFitHOOK = [HOOK_pos HOOK_fit];
    
    if HOOK_fit < best_fit
        best_fit = HOOK_fit;
        best_pos = HOOK_pos;
    end
end

c = best_pos(1);
w = [best_pos(2) 1-best_pos(2)];
beta = [best_pos(3) 1-best_pos(3)];
r = 1;

index = 1;
for i=1:length(test_stimuli)
    p = gcm(c,w,beta,test_stimuli(i,:),categories,r);
    Pred(index) = p(1);
    index = index + 1;
end

figure(FigIndex);
FigIndex = FigIndex+1;
hold on;
bar([Data' Pred']);
axis([.1 length(Data)+.9 0 1]);
colormap(cool);
xlabel('stimulus');
ylabel('probability');
legend('Data', 'Pred');
hold off;
