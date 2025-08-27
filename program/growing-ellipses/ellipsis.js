class GrowingEllipsis extends HTMLElement {
  max = 3
  character = '.'
  timeout = 1_000
  count = 0
  display = null

  constructor() {
    super()
    this.loop = this.render.bind(this)
  }

  connectedCallback() {
    const shadow = this.attachShadow({ mode: 'open' })
    this.display = document.createElement('span')
    shadow.appendChild(this.display)

    this.max = Number(this.attributes.count?.value ?? this.max)
    this.character = (
      this.attributes.character?.value ?? this.character
    )
    this.timeout = Number(this.attributes.timeout?.value ?? this.timeout)

    this.loop()
  }

  render() {
    this.display.textContent = (
      this.character.repeat((++this.count % this.max) + 1)
    )
    setTimeout(this.loop, this.timeout)
  }
}

customElements.define("growing-ellipsis", GrowingEllipsis);