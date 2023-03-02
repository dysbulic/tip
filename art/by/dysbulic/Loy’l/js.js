var maxStars = 250

const svg = this.documentElement
console.info({ t: this })
// const root = svg.getElementById('starholder')
// const viewBox = svg.getAttribute('viewBox')
// let width, height
// if(viewBox) {
//   ({ width, height } = svg.getBoundingClientRect())
// } else {
//   ([, , width, height] = (
//     viewBox.split(' ').map(parseFloat)
//   ))
// }

// for(var i = 1; i <= maxStars; i++) {
//   const star = {
//     x: Math.random() * width,
//     y: Math.random() * height,
//     rotation: Math.random() * 360,
//     elem: (() => {
//       const elem = document.createElementNS(
//         'http://www.w3.org/2000/svg', 'use'
//       )
//       elem.setAttribute('href', '#star')
//       root.append(elem)
//       return elem
//     })(),
//   }

//   var anim = () => {
//     star.x = star.x + 1 * (-0.5 + Math.random())
//     star.y = star.y + 1
//     if(star.y > height) {
//         star.y = -1.5 * star.elem.getBoundingClientRect().height
//     }
//     star.rotation = star.rotation + 5
//     star.elem.style.transform = (`
//       translate(${star.x}px, ${star.y}px)
//       rotate(${star.rotation}deg)
//     `)

//     requestAnimationFrame(anim)
//   }

//   anim()
// }
