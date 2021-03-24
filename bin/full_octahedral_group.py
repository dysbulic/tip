import numpy as np
from math import log, ceil
 
from misc.bin2svg import bin2svg       # https://pastebin.com/y8rY5Vj4
 
from octahedral_permutations import *  # https://pastebin.com/JBx0TSsC
from store_svg import *                # https://pastebin.com/FaBF6k7E
 
 
def number_to_reverse_binary_list(n, length):
    bin_str = "{0:b}".format(n).zfill(length)[::-1]
    return [int(digit) for digit in bin_str]
 
 
def perm_to_bin_mat(perm):
    long = len(perm)
    high = ceil(log(max(perm), 2))
    mat = np.zeros([high, long], dtype=bool)
    for i, e in enumerate(perm):
        mat[:, i] = number_to_reverse_binary_list(e, high)
    return mat
 
 
def perm_to_svg_path(perm):
    mat = perm_to_bin_mat(perm)
    return bin2svg(mat)
 
 
conjugacy_class_to_color = {
    'A': 'white',  'B': 'white',  'C': 'white',  'D': 'green', 'E': 'orange',
    'a': 'yellow', 'b': 'yellow', 'c': 'yellow', 'd': 'blue',  'e': 'red'
}
 
 
gray_cube_coordinates = [(0, 3), (0, 1), (1, 2), (1, 0), (2, 3), (2, 1), (3, 2), (3, 0)]
arrow_cube_coordinates = [(0, 3), (0, 1), (.6, 2.2), (.6, .2), (2, 3), (2, 1), (2.6, 2.2), (2.6, .2)]
 
for filenum in range(6):
 
    footer_perm_formulas = ''
    footer_perm_matrix = ''
    footer_invperm_formulas = ''
    footer_invperm_matrix = ''
 
    filename = 'test %s.svg' % filenum
 
    rectangles_svg = ''
    gray_matrix_text_svg = ''
 
    for rectnum in range(8):
 
        permdict = octahedral_permutations[(rectnum, filenum)]
        invpermdict = octahedral_permutations[permdict['inverse']]
        perm, invperm = permdict['perm'], invpermdict['perm']
 
        # background and cube colors
        rectbeige = 'f6e6d2' if permdict['parity'] else 'fcf9f0'  # light if parity 0
        (bottomgray, topgray) = ('ddd', '888') if rectnum in [0, 3, 5, 6] else ('888', 'ddd')
        if rectnum == 0:
            (bottombeige, topbeige) = ('f6e6d2', 'fcf9f0') if permdict['parity'] else ('fcf9f0', 'f6e6d2')
 
        # conjugacy class colors
        conjug_letter = permdict['conjug']
        conjug_color_name = conjugacy_class_to_color[conjug_letter]
        conjug_color = {
            'white':  'ffffff', 'green': '33d42a', 'orange': 'ffa200',
            'yellow': 'ffff7f', 'blue':  '3375ff', 'red':    'ef2500'
        }[conjug_color_name]
 
        # permutation IDs (0..23, 0'..23')
        int24 = permdict['int'] % 24
        apostrophe = permdict['int'] >= 24
        bold = int24 in [0, 7, 16, 23]
        perm_id = str(int24) + apostrophe*"'"
 
        perm_id_circle_svg = '<circle cx="0" cy="0" r="18" stroke="black" stroke-width="%s" fill="#%s" />' \
                             '<g style="text-anchor: middle;" font-family="sans-serif" font-size="16px" font-weight="%s">' \
                             '<text x="0" y="6">%s</text></g>' \
                             % ('3' if bold else '1.5', conjug_color, 'bold' if bold else 'normal', perm_id)
 
        # arrows
        arrows_svg = ''
        for i, p in enumerate(perm):
            if i != p:
                c1 = arrow_cube_coordinates[i]
                c2 = arrow_cube_coordinates[p]
                diff = np.array(c2) - np.array(c1)
                diff_length = np.sqrt(diff[0] ** 2 + diff[1] ** 2)
                diff_short = diff * .32 / diff_length  # .32 is the approximate length of the arrow end before scaling by 30
                if perm[p] == i:  # if this is a transposition, make one double arrow
                    if i < p:
                        c1_short = c1 + diff_short
                        c2_short = c2 - diff_short
                        coordinates = (
                            round(c1_short[0], 3), round(c1_short[1], 3), round(c2_short[0], 3), round(c2_short[1], 3)
                        )
                        arrows_svg += '<g opacity=".7">' \
                                      '<line x1="%s" y1="%s" x2="%s" y2="%s" ' \
                                      'marker-start="url(#backward)" marker-end="url(#foreward)" />' \
                                      '</g>' % coordinates
                else:  # if no transposition
                    c2_short = c2 - diff_short
                    coordinates = (c1[0], c1[1], round(c2_short[0], 3), round(c2_short[1], 3))
                    arrows_svg += '<g opacity=".7">' \
                                  '<line x1="%s" y1="%s" x2="%s" y2="%s" marker-end="url(#foreward)" />' \
                                  '</g>' % coordinates
 
        # permuted numbers
        perm_vector = '<text x="0" y="0">%s</text><text x="15" y="0">%s</text>' \
                      '<text x="30" y="0">%s</text><text x="45" y="0">%s</text>' \
                      '<text x="67" y="0">%s</text><text x="82" y="0">%s</text>' \
                      '<text x="97" y="0">%s</text><text x="112" y="0">%s</text>' % perm
 
        invperm_vector = '<text x="0" y="0">%s</text><text x="24" y="0">%s</text>' \
                         '<text x="48" y="0">%s</text><text x="72" y="0">%s</text>' \
                         '<text x="96" y="0">%s</text><text x="120" y="0">%s</text>' \
                         '<text x="144" y="0">%s</text><text x="168" y="0">%s</text>' % invperm
 
        invperm_cube = '<text x="0" y="90">%s</text><text x="0" y="30">%s</text>' \
                       '<text x="30" y="60">%s</text><text x="30" y="0">%s</text>' \
                       '<text x="60" y="90">%s</text><text x="60" y="30">%s</text>' \
                       '<text x="90" y="60">%s</text><text x="90" y="0">%s</text>' % invperm
 
        # letters
        perm_letters = '<use xlink:href="#%s" transform="translate(0)"/>' \
                       '<use xlink:href="#%s" transform="translate(28)"/>' \
                       '<use xlink:href="#%s" transform="translate(56)"/>' % permdict['letters']
 
        invperm_letters = '<use xlink:href="#%s" transform="translate(0)"/>' \
                          '<use xlink:href="#%s" transform="translate(28)"/>' \
                          '<use xlink:href="#%s" transform="translate(56)"/>' % invpermdict['letters']
 
        # negators
        perm_negators = ''
        for i, d in enumerate(permdict['negators']):
            if d:
                perm_negators += '<rect x="%s" y="31" width="16" height="2.5"/>' % (101 + i * 28)
 
        invperm_negators = ''
        for i, d in enumerate(invpermdict['negators']):
            if d:
                invperm_negators += '<rect x="%s" y="31" width="16" height="2.5"/>' % (101 + i * 28)
 
        # formulas (letters with negators)
        perm_formula = formula_svg_store.format(letters=perm_letters, negators=perm_negators)
        invperm_formula = formula_svg_store.format(letters=invperm_letters, negators=invperm_negators)
 
        # SVG matrices
        perm_mat = perm_to_svg_path(perm)
        invperm_mat = perm_to_svg_path(invperm)
 
        # where to move the rectangle
        (rectx, recty) = (gray_cube_coordinates[rectnum][0] * 200, gray_cube_coordinates[rectnum][1] * 200)
 
        # append to ``rectangles_svg``
        rectangles_svg += rectangle_svg_store.format(rectx=rectx, recty=recty,
                                                     idcircle=perm_id_circle_svg,
                                                     pmat=perm_mat, ipmat=invperm_mat,
                                                     pform=perm_formula, ipform=invperm_formula,
                                                     arrows=arrows_svg,
                                                     cubenums=invperm_cube, pvect=perm_vector, ipvect=invperm_vector,
                                                     bottomgray=bottomgray, topgray=topgray, beige=rectbeige)
        # append to footer variables
        footer_perm_formulas += '<g transform="translate(0, {y}) scale(.8, .8)">{f}</g>'.format(y=rectnum*24, f=perm_formula)
        footer_perm_matrix += '<g transform="translate(0, {y})">{v}</g>'.format(y=rectnum*24, v=perm_vector)
        footer_invperm_formulas += '<g transform="translate(0, {y}) scale(.8, .8)">{f}</g>'.format(y=rectnum*24, f=invperm_formula)
        footer_invperm_matrix += '<g transform="translate(0, {y})">{v}</g>'.format(y=rectnum*24, v=invperm_vector)
 
    # create SVG
    svg_string = svg_string_store.format(
        rectangles=rectangles_svg,
        pf=footer_perm_formulas,
        pm=footer_perm_matrix,
        ipf=footer_invperm_formulas,
        ipm=footer_invperm_matrix,
        bottombeige=bottombeige,
        topbeige=topbeige
    )
 
    svg_file = open(filename, 'w')
    svg_file.write(svg_string)