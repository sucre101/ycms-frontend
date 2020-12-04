// Vue.directive('active', (el, binding) => {
//   let activate = () => el.classList.add('active')
//   let deactivate = () => el.classList.remove('active')
//   let debugInfo =  {el, binding, triggers: []}
//
//   console.log(el, binding)
//
// })

Vue.directive('active', (el, binding) => {

  if (binding.value === getPath) {
    el.classList.add('active')
  } else {
    el.classList.remove('active')
  }

})

Vue.directive('indev', el => {
  el.addEventListener('click', e => {
    e.preventDefault()
    alert('This feature is currently in development')
  })
})



Vue.directive('gref', (el, binding, vnode) => {
  let component = vnode.componentInstance
  window.grefs[binding.arg] = component || el
  if (component) component.$data.gref = binding.arg
})
