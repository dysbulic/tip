var maxStars = 250
var maxSpeed = 0.005 // percent of vh

const svg = document.documentElement
const root = svg.querySelector('#starholder')
const viewBox = svg.getAttribute('viewBox')
let width, height
if(!viewBox) {
  ({ width, height } = svg.getBoundingClientRect())
} else {
  ([, , width, height] = (
    viewBox.split(' ').map(parseFloat)
  ))
}

const stars = Array.from({ length: maxStars }).map(
  () => {
    const elem = document.createElementNS(
      'http://www.w3.org/2000/svg', 'use'
    )
    elem.setAttribute('href', '#star')
    if(root) {
      root.append(elem)
      const bbox = elem.getBBox()
      const center = ({
        x: bbox.width / 2 + bbox.x,
        y: bbox.height / 2 + bbox.y
      })
      elem.style.transformOrigin = `${center.x}px ${center.y}px`
    }

    const randSpeed = Math.random() * maxSpeed * height

    return ({
      speed: randSpeed / 2 + (randSpeed * Math.random()),
      x: Math.random() * width,
      y: 0,
      rotation: Math.random() * 360,
      elem,
    })
  }
)

var anim = () => {
  stars.forEach((star) => {
    star.x += 1 * (-0.5 + Math.random())
    star.y += star.speed / 2 + (star.speed * Math.random())
    if(star.elem) {
      if(star.y > height) star.y = 0
      star.rotation += Math.random() * 5
      star.elem.style.rotate = `${star.rotation}deg`
      star.elem.style.translate = `${star.x}px ${star.y}px`
    }
  })
  requestAnimationFrame(anim)
}

anim()
