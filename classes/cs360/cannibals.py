#!/usr/bin/python

# Cannibals and missionaries -- Three missionaries and three cannibals
# come to a river. There is a boat on their side of the river that can
# take either one or two persons across the river at one time. How
# should they use the boat to cross the river in such a way that the
# cannibals never outnumber missionaries on either side of the river?

import copy

class Shore:
    missionaries = 0
    cannibals = 0

    def __init__(self, m = 0, c = 0):
        self.missionaries = m
        self.cannibals = c
    
    def __add__(self, newshore):
        self.missionaries += newshore.missionaries
        self.cannibals += newshore.cannibals
        return self
    def __sub__(self, newshore):
        self.missionaries -= newshore.missionaries
        self.cannibals -= newshore.cannibals
        return self

    def isLegal(self):
        return (self.missionaries >= 0 and self.cannibals >= 0 and
                (self.missionaries == 0 or (self.missionaries >= self.cannibals)))

    def __str__(self):
        return "%dm%dc" % (self.missionaries, self.cannibals)

class State:
    near = None
    far = None
    onNearSide = True
    lastCross = None

    def __init__(self, n = Shore(), f = Shore()):
        self.near = n
        self.far = f

    def cross(self, people):
        self.lastCross = people
        if self.onNearSide:
            self.near -= people
            self.far += people
        else:
            self.near += people
            self.far -= people
        self.onNearSide = not self.onNearSide

    def isLegal(self):
        return self.near.isLegal() and self.far.isLegal()

    def isGoal(self):
        return not self.onNearSide and (self.near.missionaries == 0 and
                                        self.near.cannibals == 0)
        
    def __str__(self):
        nearness = lambda isNear: (isNear and ["n"] or ["f"])[0]
        legality = lambda isLegal: (isLegal and [""] or ["[i]"])[0]
        return "%s:%s%s:%s%s" % (nearness(self.onNearSide), self.near, legality(self.near.isLegal()),
                                 self.far, legality(self.far.isLegal()))

# Start off at n:3m3c:0m0c
queue = [State(Shore(3, 3))]

# don't repeat states
searched = {}

# while there are states to check
while len(queue) > 0:
    current = queue[0]
    del queue[0]
    if current.isGoal(): break
    searched[str(current)] = True
    print "Examining %s" % current
    for miss in range(0, 3):
        for cani in range(max(0, 1 - miss), 3 - miss):
            newState = copy.deepcopy(current)
            newState.parent = current
            newState.cross(Shore(miss, cani))
            transition = "  %s + %sm%sc -> %s (%d)" % (current, miss, cani, newState, len(queue))
            if not newState.isLegal() or searched.has_key(str(newState)):
                pass # print "%s [skipped]" % transition
            else:
                queue.append(newState)
                print transition

path = ""
while current is not None:
    path = " + %s\n   %s%s" % (current.lastCross, current, path)
    try:
        current = current.parent
    except AttributeError:
        current = None
path = path[8:]

print "Breadth-First Solution:"
print path

print "\n\n"

def iterative_deepening_search(goal, maxdepth = 50):
    def depth_limited_search(current, limit = 1):
        if current.isGoal(): return [current]
        if limit > 0:
            searched[str(current)] = True
            print "  Examining %s" % current
            for miss in range(0, 3):
                for cani in range(max(0, 1 - miss), 3 - miss):
                    newState = copy.deepcopy(current)
                    newState.cross(Shore(miss, cani))
                    if newState.isLegal() and not searched.has_key(str(newState)):
                        print "    %s + %sm%sc -> %s (%d)" % (current, miss, cani, newState, limit)
                        path = depth_limited_search(newState, limit - 1)
                        if path is not None:
                            path.insert(0, current)
                            return path

    for limit in range(2, maxdepth):
        searched = {}
        print "Searching for %s with limit %d" % (goal, limit)
        path = depth_limited_search(goal, limit)
        if path is not None: return path

pathlist = iterative_deepening_search(State(Shore(3, 3)))
path = ""
for current in pathlist:
    path = "%s + %s\n    %s" % (path, current.lastCross, current)
path = path[8:]

print "Iterative-Deepening Solution:"
print path
