#!/usr/bin/python

# Place 8 queens on a standard chessboard, such that each row, column,
# and diagonal contains no more than one queen.

class QueensSpace:
    columnCount = 8
    currentColumn = -1
    states = []

    def __init__(self):
        for i in range(0, self.columnCount):
            self.states.append(None)
    
    def getNextLocation(self, column = None):
        if column is None: self.column = self.currentColumn + 1
        row = self.states[column]
        if row is None: row = -1
        isClear = False
        while not isClear:
            row += 1
            for c in range(0, column): # iterate up to the current column
                # there are potentially three places of conflict
                highDiag = row - (column - c)
                lowDiag = row + (column - c)
                if ((highDiag >= 0 and self.states[c] == highDiag) or
                    (lowDiag < self.columnCount and self.states[c] == lowDiag) or
                    (self.states[c] == row)):
                    isClear = False
                    break
                isClear = True
        if isClear: return row
        else: return None

    def isGoal(self):
        return self.currentColumn == self.columnCount - 1

    def __str__(self):
        out = ""
        for state in self.states:
            out += ",%s" % state
        return "%s:<%s>" % (self.currentColumn, out[1:])


space = QueensSpace()
while not space.isGoal():
    print space
    nextLoc = space.getNextLocation(space.currentColumn + 1)
    if space.states[space.currentColumn + 1] is None:
        if nextLoc is not None:
            space.currentColumn += 1
            space.states[space.currentColumn] = nextLoc
        else:
            space.states[space.currentColumn] = None
            space.currentColumn -= 1
    else:
        if nextLoc is not None:
            space.states[space.currentColumn + 1] = nextLoc
        else:
            space.states[space.currentColumn + 1] = None
            space.currentColumn -= 1

print "Goal: %s" % space
