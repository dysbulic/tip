#!/opt/local/bin/python

def area(polygon):
    """Finds the volume of a non self-intersecting polygon"""
    # From: http://www.topcoder.com/tc?module=Static&d1=tutorials&d2=geometry1#polygon_area
    crossSum = 0
    for i in range(0, len(polygon) - 1):
        a = [polygon[i][0] - polygon[0][0], polygon[i][1] - polygon[0][1]]
        b = [polygon[i + 1][0] - polygon[0][0], polygon[i + 1][1] - polygon[0][1]]
        crossSum += a[0] * b[1] - a[1] * b[0] # cross products will cancel each other out
    return abs(crossSum / 2);

# Reorders a list of polygons so the elements with the lowest percentage of their
# area not intersected with another polygon are first
def sortByPercentageIntersection(polygons):
    coveredPercents = {}
    for abbr, hull in hulls.items():
        totalArea = polygon.area(hull)
        intersections = []
        for testAbbr, testHull in hulls.items():
            if testAbbr is not abbr:
                intersection = polygon.convexIntersection(hull, testHull)
            if intersection is not None:
                intersections.append(intersection)
            coveredArea = 0
        for intersection in intersections:
            coveredArea += polygon.area(intersection)
            for testIntersection in intersections:
                if testIntersection is not intersection:
                    coveredArea -= polygon.area(polygon.convexIntersection(intersection,
                                                                           testIntersection))
            coveredPercent = coveredArea / totalArea

def convexIntersection(a, b):
    """Finds the intersection of two convex polygons"""
    # From: http://www.iro.umontreal.ca/~plante/compGeom/algorithm.html
    polygons = [a, b]

    #
    # 1. Find the leftmost vertex of each polygon
    #
    leftmostVertexIndex = [0, 0]
    for i in range(0, len(polygons)):
        for j in range(1, len(polygons[i])):
            if polygons[i][j][0] < polygons[i][leftmostVertexIndex[i]][0]:
                leftmostVervexIndex[i] = j

    # To make the following code, just reorder the polygons
    for i in range(0, len(polygons)):
        newpolygon = polygons[i][leftmostVertexIndex[i] - 1:]
        newpolygon.extend(polygons[i][:leftmostVertexIndex[i] - 1])
        polygons[i] = newpolygon

    #
    # 2. Create a caliper for each polygon as a vertical line through the leftmost vertex
    #
    # The caliper in this algorithm is always between the active point and the previous point
    # to make an initially horizontal caliper, the previous point is initialized to one
    # directly above the leftmost point
    #
    previousPoint = [[polygons[0][0][0], polygons[0][0][1] + 1],
                     [polygons[1][0][0], polygons[1][0][1] + 1]]
    
    #
    # 3. Walk around the polygons, rotating the caliper that has the minimum right rotation to
    #     hit a vertex
    #
    activeIndex = [0, 0]
#     while activeIndex[0] + 1 < len(polygon[0]) or activeIndex[1] + 1 < len(polygon[1]):
#         theta = []
#         for i in range(0, len(polygons)):
#             theta[i] = math.atan2()
#         if(activeIndex[0] + 1 < len(polygon[0]) and
#            polygon[0][activeIndex[0][
    
    return None

def boundingRectangle(polygon):
    extents = polygon[0][:]
    extents.extend([0,0])
    for point in polygon:
        extents[0] = min(extents[0], point[0])
        extents[1] = min(extents[1], point[1])
        extents[2] = max(extents[2], point[0])
        extents[3] = max(extents[3], point[1])
    return extents

# Graham Scan
# Author: David Eppstein
# From: http://aspn.activestate.com/ASPN/Cookbook/Python/Recipe/117225
#
def orientation(p, q, r):
    '''Return positive if p-q-r are clockwise, neg if ccw, zero if colinear.'''
    return (q[1] - p[1]) * (r[0] - p[0]) - (q[0] - p[0]) * (r[1] - p[1])

def convexHulls(points):
    '''Graham scan to find upper and lower convex hulls of a set of 2d points.'''
    upper = []
    lower = []
    points.sort()
    for point in points:
        while len(upper) > 1 and orientation(upper[-2], upper[-1], point) <= 0: upper.pop()
        while len(lower) > 1 and orientation(lower[-2], lower[-1], point) >= 0: lower.pop()
        upper.append(point)
        lower.append(point)
    return upper, lower
