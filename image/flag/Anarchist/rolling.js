document.documentElement.addEventListener(
  'load',
  () => {
    const p = new Promise((resolve, reject) => {
      const anim = document.querySelector('#animPosStart')
      setTimeout(
        () => {
          anim.beginElement()
          resolve()
        },
        5000
      )
    })
    await p
  },
  false
)