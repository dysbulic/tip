#!/usr/bin/env node

import { Glob } from 'glob'
import fs from 'fs'

const outdir = 'svglinks'
await fs.promises.mkdir(outdir, { recursive: true })

const iter = new Glob('**/*.svg', { ignore: ['**/node_modules/**', '**/build/**', '**/dist/**'] })
for await (const file of iter) {
  let to = file.replace(/(\/)?(svg)?\.svg$/, '')
  to = to.replace(/\//g, '⁄')
  to = `${outdir}/${to}.svg`
  const from = `${outdir.replace(/[^/]+/g, '..')}/${file}`
  try {
    await fs.promises.access(to, fs.constants.F_OK)
    console.debug(`Exists: ${to}`)
  } catch(err) {
    try {
      console.debug(`Link: ${from} → ${to}`)
      await fs.promises.symlink(from, to)
    } catch(err) {
      console.error(`Error: ${err.message}`)
    }
  }
}
