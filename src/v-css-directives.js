Object.entries({
  w: 'width',
  h: 'height',
  m: 'margin',
  mt: 'marginTop',
  mb: 'marginBottom',
}).forEach(pair => Vue.directive(pair[0], (el, binding) => {
  el.style[pair[1]] = binding.arg + 'px'
}))