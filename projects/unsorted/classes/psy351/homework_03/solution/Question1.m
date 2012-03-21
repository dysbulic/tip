% Homework 3 : Question 1
%
%

clear all;
close all;

% set the initial conditions, boundaries, and step size for the search

global RangeLimit;
RangeLimit = 4;

parInit = [1 -2];
parInc  = [0.1 0.1];
parLow  = [-RangeLimit -RangeLimit];
parHigh = [ RangeLimit  RangeLimit];

% simple hill-climbing algorithm
traj = SimpleHillClimb(@Error1,parInit,parLow,parHigh,parInc);
bestFit = traj(size(traj,1),:);

% simplex algorithm
options = optimset('display', 'iter', 'MaxIter', 500);
options.Display = 'simplex';
[bestx,fval,exitflag,output,simplex_path] = fminsearch_show(@Error1,parInit,options);
bestFitFMINSEARCH = [bestx fval]; 

% Hook-Jeeves
[HOOK_fit,HOOK_pos,HOOK_path]=hook('Error1',parInit,parLow,parHigh,parInc,parInc/10);
bestFitHOOK = [HOOK_pos HOOK_fit];

% This generates the entire error surface. With most models, you
% would never be able to do this. If you could, you would not need
% to do a search - you'd have the complete error surface in hand.
[X Y Z] = GridEvaluation(@Error1, parLow, parHigh, parInc);

% first plot the 3D surface (left panel)
figure(1);
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
