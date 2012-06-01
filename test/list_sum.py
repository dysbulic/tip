#!/usr/bin/env python

import random

# Sum a list of numbers using list comprehensions

nums = [random.randrange(0,10) for i in range(random.randrange(10,20))]
assoc = {}
for i in range(0, len(nums)):
    assoc[chr(ord('a') + i)] = nums[i]
print assoc

# Nevermind, there's a built-in predicate

print sum(assoc.values())
