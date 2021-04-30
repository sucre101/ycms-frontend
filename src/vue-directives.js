// Vue.directive('active', (el, binding) => {
//   let activate = () => el.classList.add('active')
//   let deactivate = () => el.classList.remove('active')
//   let debugInfo =  {el, binding, triggers: []}
//
//   console.log(el, binding)
//
// })

// Vue.directive('active', (el, binding) => {
//
//   if (binding.value === getPath) {
//     el.classList.add('active')
//   } else {
//     el.classList.remove('active')
//   }
//
// })
//
// Vue.directive('indev', el => {
//   el.addEventListener('click', e => {
//     e.preventDefault()
//     alert('This feature is currently in development')
//   })
// })
//
//
//
// Vue.directive('gref', (el, binding, vnode) => {
//   let component = vnode.componentInstance
//   window.grefs[binding.arg] = component || el
//   if (component) component.$data.gref = binding.arg
// })

const injectCss = (el, binding) => {
  let _result = ''
  el.style = ''

  for (let style in binding.value) {

    let _thatStyle = binding.value[style]

    switch (style) {
      case 'border-radius':
        _thatStyle = parseInt(binding.value[style]) + 'px'
        break
      case 'width':
        _thatStyle = parseInt(binding.value[style]) + 'px'
        break
      case 'height':
        _thatStyle = parseInt(binding.value[style]) + 'px'
        break
      case 'border-width':
        _thatStyle = parseInt(binding.value[style]) + 'px'
        break
      case 'font-size':
        _thatStyle = parseInt(binding.value[style]) + 'px'
        break
    }

    _result += `${style}: ${_thatStyle};`
  }


  el.style = _result
}

const active = (el, binding) => {
  if (binding.value === getPath() || (binding.value === getPath(1) && binding.value !== getPath())) {
    el.classList.add('active')
  } else {
    el.classList.remove('active')
  }
}

const imgPreview = (el, binding) => {
  if (binding.value !== null) {
    let block = document.createElement('div')
    let img = document.createElement('img')
    img.src = binding.value
    block.appendChild(img)
    block.classList.add('img-preview')
    el.appendChild(block);
  }
}

module.exports = {
  injectCss,
  active,
  imgPreview
}
