#!/usr/bin/env python

# Geneate a svg graph of Powers of a Base
#
# For: http://hoenir.himinbi.org/2009/06/22/spot-space/
#
# Author: dysbulic <dys@technoanarchy.org>
# Status:
#   22/06/2009 - Dies on an apparent inheritance issue in python

# Numeric base to chart powers
base = 2

# Numbers of powers of 2 to map
numIterations = 10

#
# Holder Classes
#
class Size(object):
    def __init__(self, width, height):
        self.width = width
        self.height = height

class tbDirection(object):
    def __init__(self, top, bottom):
        self.top = top
        self.bottom = bottom

class lrDirection(object):
    def __init__(self, left, right = None):
        self.left = left
        self.right = right

# ToDo: Add properties to default to pass through x&y if the values match i.e. box.bottom.y
class Box(tbDirection):
    def __init__(self, top, bottom, left, right):
        super(tbDirection).__init__(lrDirection(Point(top, left), Point(top, right)),
                                          lrDirection(Point(bottom, left), Point(bottom, right)))

class Pad(tbDirection, lrDirection):
    def __init__(self, top, bottom, left, right):
        super(tbDirection, self).__init__(top, bottom)
        super(lrDirection, self).__init__(left, right)

class Point(object):
    def __init__(self, x, y):
        self.x = x
        self.y = y

#
# Computations
#

# Canvas dimensions
canvasHeight = base^numIterations
canvasSize = Size(canvasHeight, canvasHeight / 5)

# Padding around the graph
percentage = .1
left = right = canvasSize.width * percentage
top = bottom = canvasSize.height * percentage
pad = Pad(top, bottom, left, right)

# Graph dimensions
graph = Size(canvasSize.width + pad.left + pad.right, canvasSize.height + pad.top + pad.bottom)

canvas = Box(pad.top, pad.top + canvasSize.height, pad.left, pad.left + canvas.width)

print '<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" version="1.0"'
print '     viewBox="0 0 %.2f %.2f">' % (graph.width, graph.height)

graphPosition = []

for i in xrange(0, numIterations + 1):
    xProgress = float(i) / numIterations
    yProgress = base^i / canvasHeight

    point = Point(canvas.bottom.left.x + xProgress * size.width,
                  canvas.bottom.left.y - yProgress * size.height)
    graphPosition.append(point)

    if len(graphPosition > 1):
        # Connect the points
        pattern = '"%06.2f" '.join(['  <line x1', 'y1', 'x2', 'y2', '/>'])
        print pattern % (graphPosition[-2].x, graphPosition[-2].y, point.x, point.y)
        
    # Print gridlines
    pattern = '%06.2f'.join(['  <path class="gridline" d="L', ',', ' ', ',', ' '])
    print pattern % (point.x, canvas.bottom.left.y, point.x, point.y, canvas.bottom.left.x, point.y)

print '</svg>'
