#!/usr/bin/env node

import fs from 'node:fs'

const input = await fs.promises.readFile('input.txt', 'utf8')
const sections = input.split('\n\n')
let seeds = []
const map = {}

sections.forEach((section) => {
  const lines = section.split("\n").filter((line) => line.trim() !== '')
  if(lines.length === 1) {
    const [_key, vals] = lines[0].split(':')
    seeds = vals.trim().split(/\s+/).map(Number)
  } else {
    const first = lines.shift()
    const [_, from, to] = first?.match(/(.+)-to-(.+) map:/) ?? []
    if(!from || !to) throw new Error(`Invalid map: "${first}".`)
    const ranges = lines.map((line) => {
      const match = line.match(/^\s*(\d+)\s+(\d+)\s+(\d+)\s*$/)
      if(!match) throw new Error(`Invalid range: "${line}".`)
      const [to, from, length] = match.slice(1, 4).map(Number)
      return { from, to, length }
    })
    map[from] ??= { to, ranges }
  }
})

const deref = (val) => {
  let key = 'seed'
  while(!!map[key]) {
    const transform = map[key].ranges.find(
      ({ from, length }) => val >= from && val < from + length
    )
    if(!!transform) {
      val = val - (transform.from - transform.to)
    }
    key = map[key].to
  }
  return val
}

const minFromSingles = seeds.map(deref).sort((a, b) => a - b).at(0)

let minFromRange = Infinity
for(let i = 0; i < seeds.length - 1; i += 2) {
  const [start, range] = seeds.slice(i, i + 2)
  console.debug({ checking: { start, range } })
  for(let j = 0; j < range; j++) {
    minFromRange = Math.min(deref(start + j), minFromRange)
  }
}

console.info({ minFromSingles, minFromRange })