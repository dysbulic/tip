% Author: Will Holcomb <wholcomb@gmail.com>
% Date: 09/2008
%
% PSY-351 - Computational Methods
% Homework #2

% #1. Compute the probability of applying a particular label to a
% particular item using the similarity choice model.
%
% See scm.m

biases = [ 1 2 3 4 5 ]
similarities = [ 1 2 3 4 5;
                 2 1 2 3 4;
                 3 2 1 2 3;
                 4 3 2 1 2;
                 5 4 3 2 1 ]

scm(2, 4, biases, similarities)

% #2. Implement a function that calculates the similarity between
% object i and object j in an m-dimensional psychological space.
%
% See distance_similarity.m

one = [2 4]
two = [4 3]
attentions = [.4 .6]
sensitivity = 1
distance = 2

dimensional_similarity(one, two, attentions, sensitivity, distance)

% #3. Create a plot of similarity as a function of distance between
% objects i and j for different values of c.

for p = 1 : 2
  d = 0:.1:5
  sensitivities = [1 2 5]
  colors = 'rgb'
  fig = figure();
  title(sprintf('Distance x Similarity when p = %d', p));
  xlabel('Similarity (s)');
  ylabel('Distance (d)');
  hold on
  for i = 1 : length(sensitivities)
    sensitivity = sensitivities(i)
    points = exp(-sensitivity .* d .^ p)
    % To use the original function, one can use values to control
    % the value of d
    %    points = dimensional_similarity(d, zeros(size(d)), ones(size(d)), ...
    %                                sensitivity, 1);
    plot(points, d, sprintf('%s.-', colors(i)))

    % doesn't work for multiple entries
    % legend(sprintf('c = %d', sensitivity))
  end
  legend('c = 1', 'c = 2', 'c = 5')
  plot2svg(sprintf('homework_02.3-%d.svg', p), fig)
end

% #4. Implement a function that calculates the probability of
% classifying object i as a member of category j.
% 
% see gcm.m

% #5.

A = [0 0; 2 0; 2 2]
B = [3 3; 3 5; 5 5]
proto_A = [1 1]
cat_A = [0 2]
proto_B = [4 4]
cat_B = [5 3]
