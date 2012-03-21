function trajectory = SimpleHillClimb(modelHandle,parInit,parLow,parHigh,parInc)
%
% Does a simple hill-climbing (actually hill descent) optimization for a 
% two-parameter function.
%
% This is purely for instructional purposes and is an utterly 
% unrealistic algorithm for real model fitting.
%
% Takes as input the model handle (e.g., @myModel), 
% the initial parameters (a two component vector),
% the lowest permissible parameter values (a two component vector),
% the highest permissible parameter values (a two component vector), and
% the parameter value increment a.k.a. step size (a two component vector).
%
% Returns as output the trajectory taken during the hill descent, 
% specified as a three column matrix. Each row is a point along the
% descent, specifying the parameter values (two of them) and the 
% value of the error returned from evaluating the model handle.
%
% Programmed by John Kruschke, January 28 2003.

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

% Initialize current parameter values.
parCurrent = parInit ;
% Initialize trajectory.
trajectory = [] ;
% Initialize "found it" flag.
minNotYetFound = 1 ;

% Begin hill-climbing loop
while minNotYetFound ,
    
    % Initialize improvement flag
    improvement = 0 ;
    
    % Evaluate error at current position.
    errorCurrent = feval( modelHandle , parCurrent ) ;
    trajectory = [ trajectory ; parCurrent errorCurrent ] ;
        
    % Try an increment on parameter 1.
    parNext = parCurrent + [ parInc(1) 0 ] ;
    % Replace exceeded limits with highest permissible values.
    exceedIndices = find( parNext > parHigh ) ;
    parNext(exceedIndices) = parHigh(exceedIndices);
    % Evaluate error at new parameter values.
    errorNext = feval( modelHandle, parNext ) ;
    
    % If error has decreased, keep the increment.
    if errorNext < errorCurrent
        parCurrent = parNext ;
        errorCurrent = errorNext ;
        trajectory = [ trajectory ; parCurrent errorCurrent ] ;
        improvement = 1 ;
        
    else % otherwise try a decrement on parameter 1.
        parNext = parCurrent - [ parInc(1) 0 ] ;
        % Replace exceeded limits with lowest permissible values.
        exceedIndices = find( parNext < parLow ) ;
        parNext(exceedIndices) = parLow(exceedIndices);
        % Evaluate error at new parameter values.
        errorNext = feval( modelHandle, parNext ) ;
        
        % If error has decreased, keep the decrement.
        if errorNext < errorCurrent
            parCurrent = parNext ;
            errorCurrent = errorNext ;
            trajectory = [ trajectory ; parCurrent errorCurrent ] ;
            improvement = 1 ;
        end
        
    end
        
    % Try an increment on parameter 2.
    parNext = parCurrent + [ 0 parInc(2) ] ;
    % Replace exceeded limits with highest permissible values.
    exceedIndices = find( parNext > parHigh ) ;
    parNext(exceedIndices) = parHigh(exceedIndices);
    % Evaluate error at new parameter values.
    errorNext = feval( modelHandle, parNext ) ;
    
    % If error has decreased, keep the increment.
    if errorNext < errorCurrent
        parCurrent = parNext ;
        errorCurrent = errorNext ;
        trajectory = [ trajectory ; parCurrent errorCurrent ] ;
        improvement = 1 ;
        
    else % otherwise try a decrement on parameter 2.
        parNext = parCurrent - [ 0 parInc(2) ] ;
        % Replace exceeded limits with lowest permissible values.
        exceedIndices = find( parNext < parLow ) ;
        parNext(exceedIndices) = parLow(exceedIndices);
        % Evaluate error at new parameter values.
        errorNext = feval( modelHandle, parNext ) ;
        
        % If error has decreased, keep the decrement.
        if errorNext < errorCurrent
            parCurrent = parNext ;
            errorCurrent = errorNext ;
            trajectory = [ trajectory ; parCurrent errorCurrent ] ;
            improvement = 1 ;
        end
        
    end
    
    if improvement == 0
        minNotYetFound = 0 ; % i.e., min has been found
    end
        
end % end of while loop.

%%%%%%%%%%%%%%%%%%%%%%%%%%%% end of program %%%%%%%%%%%%%%%%%%%%%%%%%%%%
