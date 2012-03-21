function [ X, Y, Z ] = GridEvaluation(modelHandle,parLow,parHigh,parInc)
%
% Returns the values of a two-parameter function over a grid of points.
%
% This is purely for instructional purposes.
%
% Takes as input the model handle (e.g., @myModel), 
% the lowest permissible parameter values (a two component vector),
% the highest permissible parameter values (a two component vector), and
% the parameter value increment a.k.a. step size (a two component vector).
%
% Returns as output the X Y and Z matrices used for "mesh" surface plotting.
%
% Programmed by John Kruschke, January 28 2003.

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

[ X Y ] = meshgrid( parLow(1):parInc(1):parHigh(1), parLow(2):parInc(2):parHigh(2) );

Z = [];

for i = 1:size(X,1),
    for j = 1:size(X,2),
        Z(i,j) = feval( modelHandle , [ X(i,j) Y(i,j) ] );
    end
end

%%%%%%%%%%%%%%%%%%%%%%%%%%%% end of program %%%%%%%%%%%%%%%%%%%%%%%%%%%%
