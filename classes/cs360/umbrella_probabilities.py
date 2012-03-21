#!/usr/bin/env python

# Will Holcomb <wholcomb@gmail.com>
# Probability convergance for #6.a (Russell & Norvig #15.2.A)

from decimal import Decimal as D, getcontext

getcontext().prec = 50

back = []
prob = [[D(".25"), D(".75")]]
for i in range(1, 70):
    back.append([D(".7") * prob[-1][0] + D(".3") * prob[-1][1], D(".3") * prob[-1][0] + D(".7") * prob[-1][1]])
    prob.append([D(".9") * back[-1][0], D(".2") * back[-1][1]])
    prob[-1] = [prob[-1][0] / (prob[-1][0] + prob[-1][1]), prob[-1][1] / (prob[-1][0] + prob[-1][1])]
    # print "%3d: <%s, %s>" % (i, back[-1][0], back[-1][1])
    print "%3d: <%s, %s>" % (i, prob[-1][0], prob[-1][1])
