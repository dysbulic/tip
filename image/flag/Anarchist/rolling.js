function getQueryVariable(variable) {
  console.info('Here')
  var query = window.location.search.substring(1)
  var vars = query.split('&')
  for(var i = 0; i < vars.length; i++) {
    var pair = vars[i].split('=');
    if(decodeURIComponent(pair[0]) == variable) {
      return decodeURIComponent(pair[1])
    }
  }
}

document.documentElement.addEventListener(
  'load',
  () => {
    const p = new Promise((resolve, reject) => {
      const anim = document.querySelector('#animPosStart')
      const timeout = getQueryVariable('t') || 0
      setTimeout(
        () => {
          anim.beginElement()
          resolve()
        },
        timeout
      )
    })
    return p
  },
  false
)