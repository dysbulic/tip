const root = document.documentElement
let hue = 0
const max = 256
const update = () => {
  // root.style.setProperty('--fur', `hsl(${hue += 3 % max}, 60%, 50%)`)
  // root.style.setProperty('--highlight', `hsl(${hue + max / 2  % max}, 60%, 50%)`)
  root.style.setProperty('--fur', 'white')
  root.style.setProperty('--highlight', 'purple')
  window.requestAnimationFrame(update)
}
window.requestAnimationFrame(update)
