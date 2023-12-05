#!/usr/bin/env node

import fs from 'node:fs';

const input = await fs.promises.readFile('input.txt', 'utf-8')
const lines = input.split('\n')

const sum = (arr) => arr.reduce((acc, val) => acc + val, 0)

const totals = Array.from({ length: lines.length }).fill(1)
const results = lines.map((line, idx) => {
  const [_, name, winningMatch, mineMatch] = (
    Array.from(line.match(/^(Card\s+\d+:)\s+([^|]+)\|\s+(.+)$/) ?? [])
  )
  if(!name) return {}
  const winning = winningMatch?.trim().split(/\s+/).map(Number)
  const mine = mineMatch?.trim().split(/\s+/).map(Number)
  const matches = sum(mine.map((num) => winning.includes(num) ? 1 : 0))
  for(let i = 0; i < matches; i++) {
    totals[idx + 1 + i] += totals[idx]
  }
  return { name, matches, score: matches === 0 ? 0 : 2**(matches - 1) }
})
.filter(({ score }) => score != null)

const total = {
  score: sum(results.map(({ score }) => score)),
  cards: sum(totals.slice(0, results.length)),
}

console.debug({ total, totals: totals.map((cards, idx) => `[${idx + 1}]: ${cards} (${results[idx]?.matches})`) })
