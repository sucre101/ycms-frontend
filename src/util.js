window.getPath = (param = 3) => {
  return location.pathname.split('/').filter((i, l) => {
    return l === param;
  })[0]
}

window.setTitle = text => {
  let title = document.getElementById('title')
  if (title) {
    title.innerText = text
  }
  document.title = text
}

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
