#!/usr/bin/env python

# Will Holcomb <wholcomb@gmail.com>
# Probability convergance for #6.b (Russell & Norvig #15.2.B)

from decimal import Decimal as D, getcontext
import Gnuplot, Gnuplot.funcutils
import xml.dom.minidom
from xml.dom.minidom import Node

outFile = "umbrella_forward_probabilities.svg"

getcontext().prec = 50

g = Gnuplot.Gnuplot(debug=1)
g('set term svg')
g('set out "%s"' % outFile)
g('set data style linespoints')
g.title('Converging Probability')
g.xlabel('t')
g.ylabel('p')

prob = [[D(".8834"), D(".1166")]]
for i in range(1, 75):
    prob.append([D(".7") * prob[-1][0] + D(".3") * prob[-1][1], D(".3") * prob[-1][0] + D(".7") * prob[-1][1]])
    print "%3d: <%s, %s>" % (i, prob[-1][0], prob[-1][1])

elms = [elm[0] for elm in prob]
g.plot(elms)

dvdDoc = xml.dom.minidom.parse(outFile)
dvdDoc.documentElement.setAttribute("height", "100%")
dvdDoc.documentElement.setAttribute("width", "100%")
dvdDoc.documentElement.setAttribute("xmlns", "http://www.w3.org/2000/svg")

outFilep = open(outFile + ".2", 'w')
print dvdDoc.writexml(outFilep)
outFilep.close()
print "Generated: %s" % outFile
