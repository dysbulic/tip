function error = ModelError_2(paramVals)
%
% Takes parameter values as input and returns, as output,
% the error (i.e., discrepancy) between the resulting model
% predictions and human data.  Usage:
%
%    error = modelError( paramVals )
%
% where paramVals is a 2x1 vector.
%
% Checks that the parameter values are within the range [-4:+4], and
% returns a "huge" error if the parameters are outside that range.
%
% Programmed by John Kruschke, 1/28/03. Modified 1/26/2005.
%

rangelimit = 4.0;
hugeError = 999999.0;

if sum( abs(paramVals) > rangelimit ) > 0

   error = hugeError;

else

   error = 10 * -cos( 2* (paramVals(1)^2+paramVals(2)^2)^0.5 ) ...
      * exp( -0.5 * ((paramVals(1)+1)^2+(paramVals(2)-1)^2)^0.5 ) + 5.1 ;

end


%% end of program %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
