% Author: Will Holcomb <wholcomb@gmail.com>
% Date: 09/2008
%
% Computes the probability of applying identifying a particular
% object with a particular label using the similarity choice model.

% object_index - index of the object being presented
% label_index - index of the label to generate a probability for
% biases - vector of response biases
% similarities - two dimensional array of object:object similarities

function scm = scm(object_index, label_index, biases, similarities)
  scm = ((biases(label_index) * similarities(object_index, label_index)) ...
         / sum(biases .* similarities(object_index,:)));
