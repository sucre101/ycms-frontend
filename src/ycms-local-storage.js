export class YcmsLocalStorage {
  constructor() {
    this.names = []
    this.watches = []
    this.values = {}
    this.i = 0
    this.app = window.app
  }

  put(name, val) {
    this.values[name] = this.fetch(name)
    localStorage.setItem(name, val)
    app.$emit('ls-change', name, val)
    this.values[name] = val
  }

  fetch(name, def) {
    if (!localStorage.getItem(name)) {
      return def ? def : null
    } else {
      return generic(localStorage.getItem(name))
    }
  }

  remove(name) {
    localStorage.removeItem(name)
  }

  watch(name) {
    if (!this.names.find(el => name == el)) this.names.push(name)
    this.i = setInterval(checkLS, 250)
  }

  check() {
    this.names && this.names.forEach(name => {
      let val = this.fetch(name)

      if (val != this.values[name]) {
        window.app.$emit('ls-change', name, val)
        this.values[name] = val
      }
    })
  }
}