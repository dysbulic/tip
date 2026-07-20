<script lang="ts">
  import { onMount } from 'svelte'

  let left_gutter = $state('0.25in')
  let top_gutter = $state('0.5in')
  let label_width = $state('1in')
  let label_height = $state('1in')
  let cols = $state(String(8))
  let rows = $state(String(8))
  let template = $state<Document | null>(null)

  function pullParams(params: URLSearchParams) {
    document.documentElement.style.setProperty(
      '--left-gutter',
      params.get('left_gutter') ?? left_gutter,
    )
    document.documentElement.style.setProperty(
      '--top-gutter',
      params.get('top_gutter') ?? top_gutter,
    )
    document.documentElement.style.setProperty(
      '--label-width',
      params.get('label_width') ?? label_width,
    )
    document.documentElement.style.setProperty(
      '--label-height',
      params.get('label_height') ?? label_height,
    )
    document.documentElement.style.setProperty(
      '--cols',
      params.get('cols') ?? cols,
    )
    document.documentElement.style.setProperty(
      '--rows',
      params.get('rows') ?? rows,
    )
  }

  onMount(() => {
    const params = (
      new URLSearchParams(window.location.search)
    )
    pullParams(params)
  })

  const doms = $derived(
    (template == null ? (
      null
    ) : (
      Array.from({ length: cols * rows }).map(
        (_, idx) => {
          const dom = template?.cloneNode(true) as Document
          const serial = dom.querySelector('.serial')
          if(serial) {
            serial.textContent = serial.textContent.replace(
              /{serial}/g,
              String(idx + 1),
            )
            serial.style.setProperty('text-anchor', 'end')
          }
          return dom
        }
      )
    ))
  )

  $inspect(doms)
</script>

<svelete:head>
  <title>Labels Sheet</title>
</svelete:head>

{#if !template}
  <form onsubmit={async (evt: SubmitEvent) => {
    evt.preventDefault()
    const form = evt.target as HTMLFormElement
    const params = new FormData(form)
    pullParams(new URLSearchParams(params as any))

    const file = params.get('template') as File
    console.debug({ file })
    if(!file) return
    template = await new Promise((resolve, reject) => {
      const reader = new FileReader()
      reader.onload = (e) => {
        const dom = new DOMParser().parseFromString(
          e.target.result as string,
          'image/svg+xml',
        )
        resolve(dom)
      }
      reader.onerror = (e) => {
        reject(e)
      }
      reader.readAsText(file)
    })


    const style = document.createElement('style')
    style.setAttribute('type', 'text/css')
    style.innerHTML = params.get('css') as string ?? ''
    document.head.appendChild(style)
  }}>
    <label>
      <span>Template</span>
      <input name="template" required type="file" onchange={async (evt) => {
      }} />
    </label>
    <label>
      <span>Left Gutter</span>
      <input name="left_gutter" value={left_gutter} />
    </label>
    <label>
      <span>Top Gutter</span>
      <input name="top_gutter" value={top_gutter} />
    </label>
    <label>
      <span>Label Width</span>
      <input name="label_width" value={label_width} />
    </label>
    <label>
      <span>Label Height</span>
      <input name="label_height" value={label_height} />
    </label>
    <label>
      <span>Columns</span>
      <input type="number" name="cols" value={cols} />
    </label>
    <label>
      <span>Rows</span>
      <input type="number" name="rows" value={rows} />
    </label>
    <label>
      <span>CSS</span>
      <textarea name="css">:nth-child(4n + 4 of .serial) &#123;
        gap: 2rem;
      &#125;</textarea>
    </label>
    <input type="submit" value="Generate" />
  </form>
{:else}
  <section id="labels">
    {#each { length: cols }, col }
      {#each { length: rows }, row }
        <div class="label">
          {@html doms[col * rows + row].documentElement.outerHTML}
        </div>
      {/each}
    {/each}
  </section>
{/if}

<style>
  :root {
  }

  :global(body) {
    margin-inline-start: var(--left-gutter, 0);
    margin-block-start: var(--top-gutter, 0);
    padding: 0;
  }

  form {
    display: grid;
    grid-template-columns: 1fr;
  }

  label {
    display: subgrid;
    grid-template-columns: 1fr 1fr;

    & span:after {
      content: ': ';
    }
  }

  #labels {
    display: grid;
    grid-template-columns: repeat(var(--cols, 1), var(--label-width, 1fr));
    gap: 0;
  }

  :global(#serial) {
    text-anchor: end;
  }

  :global(.label:nth-child(8n + 4) .serial) {
    : 2rem;
  }
</style>