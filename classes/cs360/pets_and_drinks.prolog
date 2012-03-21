%% tests is a house is next to another
%%  largely from: http://en.literateprograms.org/Zebra_Puzzle_(Prolog)

% Run in gprolog
% ?- ['pets_and_drinks.prolog'].
% ?- who_has_pet(zebra).

is_right(Left, Right, [Left, Right | _]).
is_right(Left, Right, [_ | Rest]) :- is_right(Left, Right, Rest).
nextto(X, Y, List) :- is_right(X, Y, List).
nextto(X, Y, List) :- is_right(Y, X, List).

who_has_pet(Pet) :-

%% house(Nationality, Pet, Color, Cigarette, Drink)

%% There are five houses on the street.
Street = [_, _, _, _, _],

% Someone has the requested pet
member(house(_, Pet, _, _, _), Street),

%% The Norwegan lives in the first house on the left.
Street = [house(norwegan, _, _, _, _), _, _, _, _],

%% The Englishman lives in the red house.
member(house(englishman, _, red, _, _), Street),

%% The Spaniard owns the dog.
member(house(spaniard, dog, _, _, _), Street),

%% Kools are smoked in the yellow house.
member(house(_, _, yellow, kools, _), Street),

%% The man who smokes Chesterfields lives next to the man with the fox.
nextto(house(_, _, _, chesterfields, _),
       house(_, fox, _, _, _),
       Street),

%% The Norwegan lives next to the blue house.
nextto(house(norwegan, _, _, _, _),
       house(_, _, blue, _, _),
       Street),

%% The Winston smoker owns snails.
member(house(_, snails, _, winston, _), Street),

%% The Lucky Strike smoker drinks orange juice.
member(house(_, _, _, lucky_strike, oj), Street),

%% The Ukranian drinks tea.
member(house(ukrainian, _, _, _, tea), Street),

%% The Japanese smokes Parlaiments.
member(house(japanese, _, _, parliaments, _), Street),

%% Kools are smoked in the house next to the house where the horse is kept.
nextto(house(_, _, _, kools, _),
       house(_, horse, _, _, _),
       Street),

%% Coffee is drunk in the green house.
member(house(_, _, green, _, coffee), Street),

%% The green house is immediately to the right of the ivory blue house.
is_right(house(_, _, green, _, _),
         house(_, _, ivory_blue, _, _),
         Street),

%% Milk is drunk in the middle house.
[_, _, house(_, _, _, _, milk), _, _] = Street,

write(Street).
