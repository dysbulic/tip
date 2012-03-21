% Homework 2
%
%

% Implement a function that calculates the probability of identifying object i with the label for
% object j as predicted by the Similarity Choice Model (SCM). Inputs to the function should be
% a vector of responses biases for all stimulus labels 1 through R, and an RxR matrix of
% similarities between object i and object j.
clear;
sim = [ [1.00 0.92 0.45 0.22 0.34 0.12],
        [0.92 1.00 0.22 0.43 0.12 0.54],
        [0.45 0.22 1.00 0.54 0.12 0.03], 
        [0.22 0.43 0.54 1.00 0.10 0.12],
        [0.34 0.12 0.12 0.10 1.00 0.33],
        [0.12 0.54 0.03 0.12 0.33 1.00]
        ];
bias = [1.00 0.50 1.00 0.75 1.00 1.00];
bias = bias/6;

Si = 3;
Rj = 3;
prob = scm(Si, Rj, sim, bias); 
    
% Implement a function that calculates the similarity between object i and object j in an mdimensional
% psychological space. Inputs to the function should be m-dimensional vector
% representations for objects i and j, an m-dimensional vector of attention weights for
% dimensions 1 through m, a sensitivity parameter c that scales the similarity-distance
% relationship, and the distance metric r. Test your function with object i equal to [2 4], object j
% equal to [4 3], attention weights equal to [.4 .6], and c equal to 1. Show the results.
clear;
stim1 = [2 4];
stim2 = [4 3];
w = [.4 .6];
c = 1;
r = 2;
similarity(c,w,stim1,stim2,r)

%
% illustrate effects of different attention weights
w = [.4 .6];
similarity(c,w,stim1,stim2,r)
w = [.8 .2];
similarity(c,w,stim1,stim2,r)
w = [.9 .1];
similarity(c,w,stim1,stim2,r)

% Explore the effects of different values of sensitivity c on the shape of the similarity x distance
% function by making creative use of the function you created above. Create a plot of similarity
% as a function of distance between objects i and j for different values of c. In particular, create
% a plot of similarity as a function of distance where distance ranges from 0 to 5 for c equal to
% 1, 2, and 5. Make sure you clearly label your plots.
clear;
w = 1;
r = 1;
stim1 = [0];
d = 0:.05:5;
c = 1;
for index=1:length(d);
    stim2 = [d(index)];
    sim1(index) = similarity(c,w,stim1,stim2,r);
end
c = 2;
for index=1:length(d);
    stim2 = [d(index)];
    sim2(index) = similarity(c,w,stim1,stim2,r);
end
c = 5;
for index=1:length(d);
    stim2 = [d(index)];
    sim3(index) = similarity(c,w,stim1,stim2,r);
end

figure(1);
plot(d, sim1, d, sim2, d, sim3);
legend('c=1', 'c=2', 'c=5');
xlabel('distance');
ylabel('similarity');

% Implement a function that calculates the probability of classifying object i as a member of
% category J – this is the guts of the GCM. Inputs to the function should be a vector of response
% biases for all categories 1 through R, the m-dimensional vector representation for object i, an
% array of m-dimensional vectors for the exemplars of all categories 1 through R, an mdimensional
% vector of attention weights for dimensions 1 through m, and the sensitivity
% parameter c. If creating a function for the general case of R response categories seems a bit
% daunting right now, you can create a function that assumes only two responses categories.

% SHJ Type I as an Examples
clear;
A = [1 1 1;
     1 1 2;
     1 2 1;
     1 2 2
    ];
B = [2 1 1;
     2 1 2;
     2 2 1;
     2 2 2
     ]
categories = {A B};
beta = [.5 .5];
w = [.90 .05 .05];
c = 2;
r = 1;

index = 1;
for i = 1:length(categories)
    for j = 1:size(categories{i},1)
        p = gcm(c,w,beta,categories{i}(j,:),categories,r);
        accuracy(index) = p(i);
        index = index + 1;
    end
end

sum(accuracy)/length(accuracy)

% We will explore predictions of the GCM for a very simple category structure. There are two
% categories, A and B, each category has three objects, and objects are represented in a twodimensional
% psychological space. The members of category A are [0 0], [2 0], and [2 2], and
% the members of category B are [3 3], [3 5], and [5 5]. You will use the function you just
% created to calculate the accuracy of categorizing each of the objects as member of their
% respective category (i.e., P(A|i) for category A objects and P(B|i) for category B objects). In
% addition, you will evaluate the P(A|i) for the category A prototype [1 1] and for a new
% category A item [0 2]. And you will evaluate the P(B|i) for the category B prototype [4 4] and
% for a new category B item [5 3]. Assume equal biases, equal attention weights, a Euclidean
% distance metric, and c=1.
clear;
A = [0 0;
     2 0;
     2 2
    ];
B = [3 3;
     3 5;
     5 5
     ]

ProtoA = [1 1];
ProtoB = [4 4];

TrueProtoA = [(0+2+2)/3 (0+0+2)/3];
TrueProtoB = [(3+3+5)/3 (3+5+5)/3];

NewA = [0 2];
NewB = [5 3];

categories = {A B};
beta = [.5 .5];
w = [.5 .5];
c = 1;
r = 2;

% old accuracy
index = 1;
for i = 1:length(categories)
    for j = 1:size(categories{i},1)
        p = gcm(c,w,beta,categories{i}(j,:),categories,r);
        accuracy(index) = p(i);
        index = index + 1;
    end
end
old = sum(accuracy)/length(accuracy)

% proto accuracy
clear accuracy;
p = gcm(c,w,beta,ProtoA,categories,r);
accuracy(1) = p(1);
p = gcm(c,w,beta,ProtoB,categories,r);
accuracy(2) = p(2);
proto = sum(accuracy)/length(accuracy);

% true proto accuracy
clear accuracy;
p = gcm(c,w,beta,TrueProtoA,categories,r);
accuracy(1) = p(1);
p = gcm(c,w,beta,TrueProtoB,categories,r);
accuracy(2) = p(2);
trueproto = sum(accuracy)/length(accuracy);

% new accuracy
clear accuracy;
p = gcm(c,w,beta,NewA,categories,r);
accuracy(1) = p(1);
p = gcm(c,w,beta,NewB,categories,r);
accuracy(2) = p(2);
new = sum(accuracy)/length(accuracy);

f2 = figure(2);
bar([proto old new]);
axis([.5 3.5 .5 1]);
xlabel('Stimulus Type');
set(gca,'xticklabel',{'Proto' 'Old' 'New'})
ylabel('Accuracy');

f3 = figure(3);
bar([trueproto old new]);
axis([.5 3.5 .5 1]);
xlabel('Stimulus Type');
set(gca,'xticklabel',{'Proto' 'Old' 'New'})
ylabel('Accuracy');
