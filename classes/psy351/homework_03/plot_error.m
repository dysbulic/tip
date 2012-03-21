% Homework 3 : Question 1
%
%

function plot_error(errFuncName, parInit, parInc, viewPos)

% Function describing the error surface
errFunc = eval(['@' errFuncName]);

global RangeLimit;
parLow  = [-RangeLimit -RangeLimit];
parHigh = [ RangeLimit  RangeLimit];

% simple hill-climbing algorithm
traj = SimpleHillClimb(errFunc, parInit, parLow, parHigh, parInc);
bestFit = traj(size(traj,1),:);

% simplex algorithm
options = optimset('display', 'iter', 'MaxIter', 500);
options.Display = 'simplex';
[bestx, fval, exitflag, output, simplex_path] = ...
    fminsearch_show(errFunc, parInit, options);
bestFitFMINSEARCH = [bestx fval]; 

% Hook-Jeeves
[HOOK_fit, HOOK_pos, HOOK_path] = ...
    hook(errFuncName, parInit, parLow, parHigh, parInc, parInc / 10);
bestFitHOOK = [HOOK_pos HOOK_fit];

% This generates the entire error surface. With most models, you
% would never be able to do this. If you could, you would not need
% to do a search - you'd have the complete error surface in hand.
[X Y Z] = GridEvaluation(errFunc, parLow, parHigh, parInc);

figTitle = sprintf('%s - parInc [%.1f,%.1f] / parInit [%.1f,%.1f]', errFuncName, parInc(1), parInc(2), parInit(1), parInit(2));
filename = sprintf('%s_%.1f,%.1f-%.1f,%.1f-r%.1f.3d.svg', errFuncName, parInit(1), parInit(2), parInc(1), parInc(2), RangeLimit);
if ~exist(filename, 'file')
    % first plot the 3D surface (left panel)
    fig = figure();
    surfc(X,Y,Z);
    hold on;
    plot3(traj(:,1),traj(:,2),traj(:,3),'-m','LineWidth',3);
    plot3(bestFit(1), bestFit(2), bestFit(3),'om', 'MarkerFaceColor', 'm', 'MarkerSize', 10);
    %plot3(bestFitFMINSEARCH(1),bestFitFMINSEARCH(2),bestFitFMINSEARCH(3), 'sb', 'MarkerFaceColor','b','MarkerSize',10);
    hold off;
    axis([parLow(1) parHigh(1) parLow(2) parHigh(2) 0 16]) ;
    axis square;
    view(viewPos(1), viewPos(2));
    xlabel('Param_1') ;
    ylabel('Param_2') ;
    zlabel('Error') ;
    title(figTitle);

    plot2svg(filename, fig);
end

filename = sprintf('%s_%.1f,%.1f-%.1f,%.1f-r%.1f.2d.svg', errFuncName, parInit(1), parInit(2), parInc(1), parInc(2), RangeLimit);
if ~exist(filename, 'file')
    % then plot the contours (right panel)
    fig = figure();
    hold on;
    contour(X,Y,Z,20) ;
    axis([parLow(1) parHigh(1) parLow(2) parHigh(2)]) ;
    axis square;
    grid on;
    xlabel('Param_1') ;
    ylabel('Param_2') ;
    title(figTitle);

    % plot the Simple Hill Climbing trajectory
    plot(traj(:,1),traj(:,2),'-m','LineWidth',3);
    plot(bestFit(1),bestFit(2),'om', 'MarkerFaceColor', 'm', 'MarkerSize', 10);

    % plot simplex path
    plot(bestFitFMINSEARCH(1),bestFitFMINSEARCH(2),'sb', 'MarkerFaceColor','b','MarkerSize',10);
    for i = 1:size(simplex_path, 1)
        points = simplex_path(i,:,:);
        plot_simplex_triangle(points(:,:,1), points(:,:,2), points(:,:,3));
    end

    % plot Hooke-Jeeves path
    plot(HOOK_path(:,1), HOOK_path(:,2), 'g-');

    ann = {sprintf('Hill Climb: (%.2f,%.2f)', bestFit(1), bestFit(2))
           sprintf('Hook: (%.2f,%.2f)', bestFitHOOK(1), bestFitHOOK(2))
           sprintf('Simplex: (%.2f,%.2f)', bestFitFMINSEARCH(1), bestFitFMINSEARCH(2))};
    text('Position', [parLow(1) * .95 parHigh(2) * .95], ...
         'String', ann, ...
         'VerticalAlignment', 'top');

    plot2svg(filename, fig);
end
hold off;
