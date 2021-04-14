window.flatTree = (tree, childrenProp = 'children', flat = []) => {
  // l(flat, tree)
  flat = flat.concat(tree)
  return tree.map(branch => {
    l(branch[childrenProp].length, flat)
    if (branch[childrenProp].length) return flatTree(branch[childrenProp], childrenProp, flat)
  })
}