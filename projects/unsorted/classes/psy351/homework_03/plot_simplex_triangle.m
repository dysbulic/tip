function plot_simplex_triangle(p1, p2, p3)
%

x = [p1(1) p2(1)];
y = [p1(2) p2(2)];
plot(x',y','b-');

x = [p2(1) p3(1)];
y = [p2(2) p3(2)];
plot(x',y','b-');

x = [p3(1) p1(1)];
y = [p3(2) p1(2)];
plot(x',y','b-');



