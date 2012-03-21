function [current_fit,current_pos,hook_path]=hook(myfunc,param,min_pos,max_pos,step,min_step)
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
% INPUTS:
% 1) func - Name of the function file name containing the model passed as string without the '.m' extension     
% 2) param - Vector containing the initial parameter values
% 3) min_pos - Vector containing the minimum boundary value for each parameter
% 4) max_pos - Vector containing the maximum boundary value for each parameter  
% 5) step -  Vector containing the step size for each parameter 
% 6) min_step - Vector containing the min step size for each parameter 
%     
% OUTPUTS:
% 1) current_fit - Best fit value
% 2) current_pos - Parameter vector corresponding to the best fit value.
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

global TRUE FALSE YES NO minimal_search current_pos test_pos next_pos
global current_fit test_fit next_fit improvement direction
global func param_min_pos param_max_pos param_step param_min_step

current_pos = [];
test_pos = [];
next_pos = [];
param_min_pos = [];
param_max_pos = [];
param_step = [];
param_min_step = [];

hook_path = [];
index = 1;

func = myfunc;

if (nargin < 6)
		disp ('Error, I expected 6 parameters')
		return
end
if (~(isstr(func)))
		error ('FUNC must be passed as a string')
end	


TRUE =1;
FALSE =0;
YES =1;
NO = 0;
minimal_search=NO;

current_pos = param;

param_min_pos=min_pos;
param_max_pos=max_pos;
param_step=step;
param_min_step=min_step;

while step_sizes_are_not_all_minimal
    test_current_position;
    if improvement
        while improvement
            modify_direction;
            
            change_position;
            hook_path(index,:) = [current_pos current_fit];
            index = index + 1;
            
            test_next_position;
        end
    else
        reduce_step_size;
    end
end

%%%%%%%%%%%%%%%%%%%%End function HOOK %%%%%%%%%%%%%%%%%%%%

function difference=step_sizes_are_not_all_minimal
%% Returns TRUE if step sizes are not all minimal.  
%% Also returns TRUE if step sizes are all minimal  
%% but have not yet been searched. 
global TRUE FALSE YES NO minimal_search current_pos test_pos next_pos
global current_fit test_fit next_fit improvement direction
global func param_min_pos param_max_pos param_step param_min_step


difference = FALSE;
for i=1:length(current_pos)
    if param_step(i) > param_min_step(i)
        difference = TRUE;
    end
end
if difference == FALSE && minimal_search == FALSE
    difference = TRUE;
    minimal_search = TRUE;
end

%%%%%%%%%%%%%%%%%%% End function step_sizes_are_not_all_minimal %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

function test_current_position

global TRUE FALSE YES NO minimal_search current_pos test_pos next_pos
global current_fit test_fit next_fit improvement direction
global func param_min_pos param_max_pos param_step param_min_step

improvement = FALSE;
test_pos= current_pos; %% Initialize test position at current position. 

%Evaluate model at current position
eval_str = ['current_fit=' func '(current_pos)'];
eval(eval_str);

for i=1:length(current_pos)%For each Parameter
    if param_step(i) ~= 0
        test_pos(i) = current_pos(i) + param_step(i);%% try incrementing it by a step 
        if test_pos(i) > param_max_pos(i)
            test_pos(i) = param_max_pos(i);
        end
        %Evaluate model at test position;
        eval_str = ['test_fit=' func '(test_pos);'];
        eval(eval_str);
        if test_fit >= current_fit %If increment makes for a worse fit, try decrementing param by a step.
            test_pos(i)=current_pos(i)-param_step(i);
            if test_pos(i) < param_min_pos(i)
                test_pos(i) = param_min_pos(i);
            end
            %Evaluate model at test position
            eval_str = ['test_fit=' func '(test_pos);'];
            eval(eval_str);
            if test_fit >= current_fit % If decrement also makes for a worse fit, set parameter to the unchanged (current) value.
                test_pos(i)=current_pos(i);
            else % but if decrement makes a better fit, change parameter to the decremented (test) value;
                current_fit = test_fit;
                improvement = TRUE;
            end
        else %If increment makes for a better fit, change parameter to the incremented (test) value;
            current_fit = test_fit;
            improvement = TRUE;
        end
    end
end
            
%%%%%%%%%%%% End function test_current_position %%%%%%%%%%%%

function modify_direction

global TRUE FALSE YES NO minimal_search current_pos test_pos next_pos
global current_fit test_fit next_fit improvement direction
global param_min_pos param_max_pos param_step param_min_step

change = FALSE;
direction = test_pos - current_pos;

%%%%%%% End function modify_direction %%%%%%%%%%%%%%%%%%%%%

function change_position

global TRUE FALSE YES NO minimal_search current_pos test_pos next_pos
global current_fit test_fit next_fit improvement direction
global param_min_pos param_max_pos param_step param_min_step

current_pos = test_pos;

%%%%%%%%%%%End function change_position%%%%%%%%%%%%%%

function test_next_position

global TRUE FALSE YES NO minimal_search current_pos test_pos next_pos
global current_fit test_fit next_fit improvement direction
global func param_min_pos param_max_pos param_step param_min_step

improvement = FALSE;
for i=1:length(current_pos)
    next_pos(i) = current_pos(i) + direction(i);
    if next_pos(i) > param_max_pos(i)
        next_pos(i) = param_max_pos(i);
    end
    if next_pos(i) < param_min_pos(i)
        next_pos(i) = param_min_pos(i);
    end
    test_pos(i) = next_pos(i);
end
%Evaluate model at next position
eval_str = ['next_fit=' func '(next_pos)'];
eval(eval_str);

for i=1:length(current_pos)
    if param_step(i) ~= 0
        test_pos(i) = next_pos(i) + param_step(i);%% try incrementing it by a step 
        if test_pos(i) > param_max_pos(i)
            test_pos(i) = param_max_pos(i);
        end
        %Evaluate model at test position
        eval_str = ['test_fit=' func '(test_pos);'];
        eval(eval_str);
        if test_fit >= next_fit %If increment makes for a worse fit, try decrementing param by a step.
            test_pos(i)=next_pos(i)-param_step(i);
            if test_pos(i) < param_min_pos(i)
                test_pos(i) = param_min_pos(i);
            end
            %Evaluate model at test position
            eval_str = ['test_fit=' func '(test_pos);'];
            eval(eval_str);
            if test_fit >= next_fit % If decrement also makes for a worse fit, set parameter to the unchanged (next) value.
                test_pos(i)=next_pos(i);
            else % but if decrement makes a better fit, change parameter to the decremented (test) value;
                next_fit = test_fit;
            end
        else %If increment makes for a better fit, hange parameter to the incremented (test) value;
            next_fit = test_fit;
        end
    end
end 

if current_fit > next_fit
    current_fit = next_fit;
    improvement = TRUE;
end

%%%%%%%%% End function test_next_position %%%%%%%%%%

function reduce_step_size

global TRUE FALSE YES NO minimal_search current_pos test_pos next_pos
global current_fit test_fit next_fit improvement direction
global param_min_pos param_max_pos param_step param_min_step

for i=1:length(current_pos)
    param_step(i)= param_step(i)./8;
    if param_step(i) < param_min_step(i)
        param_step(i) = param_min_step(i);
    end
end



