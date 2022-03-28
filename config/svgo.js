module.exports = {
  js2svg: {
    indent: 2,
    pretty: true,
  },
  plugins: [
    // 'removeDimensions',
    'removeEditorsNSData',
    'removeUnusedNS',
    'removeEmptyAttrs',
    'removeEmptyContainers',
    'removeUnknownsAndDefaults',
    'cleanupIDs',
    'minifyStyles',
    'convertStyleToAttrs',
    'collapseGroups',
    'convertEllipseToCircle',
  ],
}