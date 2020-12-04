// window.l = function() { console.log(...arguments) }
//
// window.axios = require('axios');
//
// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Inspect FormData object
// window.fdInspect = fd => {
//   for (var pair of fd.entries()){
//     if (pair[1] instanceof File) {
//       console.log(pair[0], pair[1], `size: ${pair[1].size / 1024} KB`)
//     } else {
//       console.log(pair[0]+ ': '+ pair[1])
//     }
//   }
// }

// window.toKebabCase = str =>
//    str
//     .match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g)
//     .map(x => x.toLowerCase())
//     .join('-');

// Show ajax response notifications
// window.showResponse = res => {
//   // If server sent {async: 'compiling'} waits for iframe to reload
//   if (res.async && res.async == 'compiling') {
//     this.notifier.async(new Promise((res, rej) => {
//       let phoneFrame = document.getElementsByTagName('iframe')[0]
//       phoneFrame.onload = () => res(true)
//       // TODO: catch errors from ionic processes inputs
//       setTimeout(() => rej('Whoops something gone wrong'), 15000)
//     }), 'Compiled')
//   }
//
//   if (res.message) this.notifier.info(res.message)
//
//   if (res.success) this.notifier.success(res.success)
//
//   if (res.errors) {
//     for(let err in res.errors) {
//       res.errors[err].forEach(e => this.notifier.alert(e))
//     }
//   }
// }

// window.get = async (url) => {
//   // let csrf = document.querySelector('meta[name="csrf-token"]').content
//   let res
//   await fetch(url/*, {'X-CSRF-Token': csrf}*/).then(r => res = r.json())
//   return res
// }
//
// window.post = async (url, body) => {
//   let csrf = document.querySelector('meta[name="csrf-token"]').content
//   let headers = {
//     'X-CSRF-Token': csrf,
//     // redirect: 'follow',
//   }
//
//   if (type(body) == 'formdata') {
//     headers['X-Requested-With'] = 'XMLHttpRequest'
//   } else {
//     headers['Content-Type'] = 'application/json'
//     body = JSON.stringify(body)
//   }
//
//   let res = await fetch(url, { method: 'POST', headers, body })
//
//   let isJson = res.headers.get('content-type').includes('json')
//   if (isJson) {
//     let json = await res.json()
//     showResponse(json)
//     return json
//   }
//
//   if (res.redirected) window.location.href = res.url
// }

// window.type = obj => {
//   let str = Object.prototype.toString.call(obj)
//   return /object (\w+)/.exec(str)[1].toLowerCase()
// }

// window.getPath = url => /(?<!\/)(\/(?!\/).+?)[^?]*/.exec(url)[0]

window.getPath = (param = 3) => {
  return location.pathname.split('/').filter((i, l) => {
    return l === param;
  })[0]
}

window.setTitle = text => {
  let title = document.getElementById('title')
  title.innerText = text;
}

// window.getPath = location.pathname.split('/').filter((i, l) => {
//   return l === 3;
// })[0]

window.generic = str => {
  switch (str) {
    case 'true':
      return true
    case 'false':
      return false
  }

  if (str.match(/^-{0,1}\d+$/)) return parseInt(str)
  if (str.match(/^\d+\.\d+$/)) return parseFloat(str)

  return str
}

// window.flatTree = (tree, childrenProp = 'children', flat = tree) => {
//   let getChildren = branches => branches.reduce((acc, branch) => {
//     return acc.concat(branch[childrenProp])
//   }, [])
//   let hasChildren = getChildren(tree).length
//   let nextBranhces = getChildren(tree)
//   while(hasChildren) {
//     flat = flat.concat(nextBranhces)
//     nextBranhces = getChildren(nextBranhces)
//     hasChildren = nextBranhces.length
//   }
//   return flat
// }

// window.empty = obj => !Object.values(obj).length
//
// // FIXME: adding methods to generic prototypes is bad
// Array.prototype.pull = function(arg) {
//   let index
//   if (typeof arg == 'function') {
//     index = this.findIndex(arg)
//   } else {
//     index = this.indexOf(arg)
//   }
//   if ([undefined, -1].includes(index)) return null
//   return this.splice(index, 1)[0]
// }
//
// if (!Array.prototype.last) {
//   Array.prototype.last = function(){
//     return this[this.length - 1];
//   };
// }

// window.pull = val => {
//   return {
//     val,
//     from(source) {
//       let index = source.indexOf(val)
//       return source.splice(index, 1)[0]
//     }
//   }
// }
//
// window.waitDefined = (ref, interval = 250) => {
//   return new Promise((res, rej) => {
//     setInterval(() => {
//       try {
//         let real = eval(ref)
//         if (type(real) != 'undefined') res(real)
//       } catch(e) {}
//     }, interval)
//   })
// }
//
// window.pushPath = (path) => {
//   history.pushState(null, null, path)
//   window.dispatchEvent(new Event('pushPath'))
// }

// window.string2Obj = objString => JSON.parse(JSON.stringify(objString))

// window.s = (arg) => {
//   let _this = {
//     objects: [],
//
//     onEnter(fun) {
//       Array.from(_this.objects).forEach(el => {
//         el.addEventListener('keyup', e => e.keyCode == 13 && fun(e))
//       })
//     },
//
//     formData() {
//       return new FormData(_this.objects[0])
//     },
//   }
//
//   if (type(arg) == 'string') _this.objects = document.querySelectorAll(arg)
//   else _this.objects = arg
//
//   return _this
// }

// window.dimensions = b64 => {
//   let img = new Image
//   img.src = b64
//   let result
//   img.onload = function() { /*result =*/ l({w: this.width, h: this.height})}
//   // return waitDefined('result')
// }

window.injectMapSearcherScript = callback => {
  if (!window.google) {
    window.initService = callback;
    let script = document.createElement('script');
    script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDMAMhiqxgufHIBYC9tHTtXdsZ2Mkg5X18&libraries=places&callback=initService';
    document.body.appendChild(script);
  } else {
    callback();
    return true;
  }
}
