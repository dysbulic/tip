married('George','Mum').
married('Elizabeth','Philip').
married('Spencer','Kydd').
married('Andrew','Sarah').
married('Anne','Mark').
married('Diana','Charles').

married(A,B) :- married(B,A).

parent('George','Margaret').
parent('George','Elizabeth').
parent('Philip','Edward').
parent('Philip','Andrew').
parent('Philip','Anne').
parent('Philip','Charles').
parent('Spencer','Diana').
parent('Charles','William').
parent('Charles','Harry').
parent('Mark','Peter').
parent('Mark','Zara').
parent('Andrew','Beatrice').
parent('Andrew','Eugene').

parent('Mum','Margaret').
parent('Mum','Elizabeth').
parent('Elizabeth','Edward').
parent('Elizabeth','Andrew').
parent('Elizabeth','Anne').
parent('Elizabeth','Charles').
parent('Kydd','Diana').
parent('Diana','William').
parent('Diana','Harry').
parent('Anne','Peter').
parent('Anne','Zara').
parent('Sarah','Beatrice').
parent('Sarah','Eugene').

% only the father is specified. a predicate is added to include the
% spouses since all parents are married in this situation

%parent(A,B) :- married(A,X), parent(X,B).

gender('George','male').
gender('Mum','female').
gender('Philip','male').
gender('Elizabeth','female').
gender('Spencer','male').
gender('Kydd','female').
gender('Andrew','male').
gender('Sarah','female').
gender('Mark','male').
gender('Anne','female').
gender('Charles','male').
gender('Diana','female').
gender('Margaret','female').
gender('Edward','male').
gender('Andrew','male').
gender('Anne','female').
gender('Charles','male').
gender('Spencer','male').
gender('William','male').
gender('Harry','male').
gender('Peter','male').
gender('Zara','female').
gender('Beatrice','female').
gender('Eugene','male').

grandchild(A,B) :- parent(B,X), parent(X,A).
greatGrandparent(A,B) :- parent(A,X), grandchild(B,X).
sibling(A,B) :- parent(X,A), parent(X,B), A \= B.
brother(A,B) :- sibling(A,B), gender(A,'male').
sister(A,B) :- sibling(A,B), gender(A,'female').
son(A,B) :- parent(B,A), gender(A,'male').
daughter(A,B) :- parent(B,A), gender(A,'female').
uncle(A,B) :- parent(X,B), sibling(X,A), gender(A,'male').
aunt(A,B) :- parent(X,B), sibling(X,A), gender(A,'female').
brotherInLaw(A,B) :- gender(A,'male'), married(B,X), sibling(A,X).
sisterInLaw(A,B) :- gender(A,'female'), married(B,X), sibling(A,X).
firstCousin(A,B) :- parent(X,A), parent(Y,B), sibling(X,Y).
