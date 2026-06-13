import JSON5 from 'https://unpkg.com/json5@2/dist/index.min.mjs'

class Caption {
  line = null
  start = null
  prev = null
  next = null
  _end = null

  constructor({ line, start, prev, next }) {
    this.line = line
    this.start = start
    this.prev = prev
    this.next = next
  }

  activeAt(time) {
    return (
      time >= this.start
      && (this.end == null || time <= this.end)
    )
  }

  get end() {
    if(this._end != null) {
      return this._end
    }
    return this.next?.start
  }

  set end(val) {
    if(this.next != null) {
      this.next.start = val
    } else {
      this._end = val
    }
  }
}

function timed(secs) {
  const minutes = Math.floor(secs / 60)
  secs = secs % 60
  return (
    `${String(minutes).padStart(2, '0')}:${secs.toFixed(3).padStart(6, '0')}`
  )
}

function loadDocInfo() {
  const params = new URLSearchParams(window.location.search)

  const [artist, title] = [
    params.get('artist') ?? '𝘈𝘳𝘵𝘪𝘴𝘵',
    params.get('title') ?? '𝘛𝘪𝘵𝘭𝘦',
  ]
  ;['head > title', 'body > h1'].forEach((selector) => {
    const elem = document.querySelector(selector)
    if(elem) {
      elem.textContent = `🎤: ${artist} — ${title}`
    } else {
      console.error({ 'No Content Found': selector })
    }
  })
  const video = document.createElement('section')
  video.innerHTML = `
    <video controls src="by/${artist}/entitled/${title}/mp4.mp4">
      <track default kind="captions" src="by/${artist}/entitled/${title}/karaoke.vtt" srclang="en"/>
    </video>

  `
  video.setAttribute('id', 'video')
  document.querySelector('body').appendChild(video)
}

function onLoad() {
  loadDocInfo()

  const vid = document.querySelector('video')
  if(!vid) throw new Error('Couldn’t find `video` element.')
  const { track: { cues: cueList } } = (
    vid.querySelector('track[kind="captions"]')
  )
  const cues = Array.from(cueList)

  function color() {
    const time = vid.currentTime
    const cue = cues.find((cue) => (
      cue.startTime <= time && cue.endTime >= time
    ))
    if(cue) {
      const offset = time - cue.startTime
      const Δ = cue.endTime - cue.startTime
      if(Δ != 0) {
        vid.style.setProperty('--break', `${50 * offset / Δ}%`)
      }
    }
    requestAnimationFrame(color)
  }
  requestAnimationFrame(color)

  const input = document.querySelector('[type="file"]')
  const out = {
    current: document.querySelector('#current'),
    next: document.querySelector('#next'),
    unknown: document.querySelector('#unknown'),
  }

  let captions = null
  let currCap = 0

  window.addEventListener('keydown', (evt) => {
    if(evt.key === 't') {
      console.debug({ time: vid.currentTime })
    } else if(evt.key === 'l') {
      input.click()
    } else if(evt.key === 'b') {
      captions[currCap++].end = vid.currentTime
    } else if(evt.key === 'd') {
      downloadVTT()
    } else if(evt.key === 'c') {
      captions.forEach((cap) => {
        cap.start = null
        cap.end = null
      })
    }
  })

  input.addEventListener('change', async (evt) => {
    const [file] = Array.from(input.files)
    const lines = (await file.text()).split(/\n+/g)
    captions = lines.map((line) => new Caption({
      line
    }))
    captions.forEach((cap, idx) => {
      cap.next = captions.at(idx + 1) ?? null
      if(idx > 0) {
        cap.prev = captions.at(idx - 1)
      }
    })
    captions[0].start = 0
    for(cueIdx = 0; cueIdx < Math.min(cues.length, captions.length); cueIdx++) {
      captions[cueIdx].start = cues[cueIdx].startTime
      captions[cueIdx].end = cues[cueIdx].endTime
    }

    console.debug({ captions, ac: captions[0].activeAt(0) })
  })

  function caps() {
    const currIdx = captions?.findIndex(
      (cap) => cap.activeAt(vid.currentTime)
    )
    out.current.textContent = (
      `Current: ${currIdx >= 0 ? `Line #${currIdx + 1}: ${captions[currIdx].line}` : 'none'}`
    )
    out.next.textContent = (
      `Next: ${currIdx > 0 ? captions[currIdx + 1]?.line : 'none'}`
    )
    out.unknown.textContent = (
      `Unknown: ${currCap < (captions?.length ?? 0) - 1 ? captions[currCap + 1].line : 'unknown'}`
    )
    requestAnimationFrame(caps)
  }
  requestAnimationFrame(caps)

  function downloadVTT() {
    let out = "WEBVTT\n\n"
    out += (
      captions.map((cap) => (
        `${timed(cap.start)} --> ${timed(cap.end)}\n${cap.line}`
      ))
      .join('\n\n')
    )
    const blob = new Blob([out], { type: 'text/plain; charset=utf-8' })
    const url = URL.createObjectURL(blob)
    window.open(url, '_blank')
  }
}

if(document.readyState === 'complete') {
  onLoad()
} else {
  window.addEventListener(
    'DOMContentLoaded',
    onLoad,
    { once: true },
  )
}