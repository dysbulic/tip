#!/usr/bin/env python

from copy import copy

class Map:
    order = None
    steps = []
    goal = None
    count = 0
    START = "S"
    GOAL = "G"
    UP = "U"
    DOWN = "D"
    LEFT = "L"
    RIGHT = "R"
    
    def __init__(self, width = 8, height = 8, border_cells = [],
                 start_cell = {'x':0,'y':0}, goal = {'x':7,'y':7}):
        self.order = [[None] * width for i in range(height)]
        for cell in border_cells:
            self.order[cell['y']][cell['x']] = False
        self.order[start_cell['y']][start_cell['x']] = self.START
        self.order[goal['y']][goal['x']] = self.GOAL
        self.goal = goal
        self.steps.append(start_cell)

    def at_goal(self):
        return (self.current_position['x'] == self.goal['x'] and
                self.current_position['y'] == self.goal['y'])
    
    def get_current_position(self):
        return self.steps[-1]

    def set_current_position(self, position):
        self.count += 1
        print "(%s,%s) = %s" % (position['x'], position['y'], self.count)
        self.order[position['y']][position['x']] = self.count
        self.steps.append(position)

    current_position = property(get_current_position, set_current_position)

    def can_move(self, direction = UP, do_move = False):
        position = copy(self.current_position)
        if direction is self.UP:
            position['y'] -= 1
        elif direction is self.DOWN:
            position['y'] += 1
        elif direction is self.LEFT:
            position['x'] -= 1
        elif direction is self.RIGHT:
            position['x'] += 1
        if(position['x'] >= 0 and position['x'] < len(self.order) and
           position['y'] >= 0 and position['y'] < len(self.order[0]) and
           (self.order[position['y']][position['x']] is None or
            self.order[position['y']][position['x']] is self.GOAL)):
            if do_move: self.set_current_position(position)
            return True
        else:
            return False
    
    def move(self, direction = UP):
        return self.can_move(direction, do_move = True)

    def can_backtrack(self):
        return len(self.steps) > 0

    def backtrack(self):
        self.steps.pop()
        
    def __str__(self):
        separator = "+--" * len(self.order[0]) + "+\n"
        
        out = separator
        for row in self.order:
            out += "|"
            for cell in row:
                if cell is None: out += "  "
                elif cell is False: out += "XX"
                elif cell is self.START: out += "SS"
                elif cell is self.GOAL: out += "GG"
                else: out += "%02d" % cell
                out += "|"
            out += "\n"
        return out + separator

filled_cells = []
for i in range(2, 7):
    filled_cells.append({'x':i,'y':3})
filled_cells.append({'x':1,'y':4})
filled_cells.append({'x':5,'y':4})
filled_cells.append({'x':2,'y':5})
filled_cells.append({'x':6,'y':5})

space = Map(border_cells = filled_cells, start_cell = {'x':3,'y':5}, goal = {'x':2,'y':2})
while not space.at_goal() and space.can_backtrack():
    if space.move(space.UP): pass
    elif space.move(space.LEFT): pass
    elif space.move(space.RIGHT): pass
    elif space.move(space.DOWN): pass
    else: space.backtrack()

print space
