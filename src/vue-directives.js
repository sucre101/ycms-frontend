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
  let result = ''
  el.style = ''

  for (let property in binding.value) {
    if (['border-radius', 'width', 'height', 'border-width', 'font-size', 'margin-left', 'margin-top'].includes(property)) {

      if (['width', 'margin-left'].includes(property)) {
        result += `${property}: ${parseInt(binding.value[property]) + '%'};`
      } else {
        result += `${property}: ${parseInt(binding.value[property]) + 'px'};`
      }
    } else {
      result += `${property}: ${binding.value[property]};`
    }
  }
  el.style = result
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
