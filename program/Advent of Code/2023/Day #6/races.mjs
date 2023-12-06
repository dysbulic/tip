#!/usr/bin/env node

import fs from 'node:fs'

const input = await fs.promises.readFile('input.txt', 'utf-8')
const lines = input.split('\n')

const race = ({ time, record }) => {
  const attempts = Array.from({ length: time + 1 }).map(
    (_, hold)  => {
      const speed = hold
      const distance = (time - hold) * speed
      return {
        distance,
        won: distance > record
      }
    }
  )
  const wins = attempts.filter(({ won }) => won)
  return wins.length
}

const pullList = (str) => (
  str.split(':').at(-1)?.trim()?.split(/\s+/).map(Number) ?? []
)
const times = pullList(lines[0])
const dists = pullList(lines[1])
const winCounts = times.map((time, idx) => race({ time, record: dists[idx] }))

console.info({ winCounts, product: winCounts.reduce((a, b) => a * b, 1) })

const lowmemRace = ({ time, record }) => {
  let count = 0
  for(let hold = 0; hold <= time; hold++) {
    const speed = hold
    const distance = (time - hold) * speed
    count += distance > record ? 1 : 0
  }
  return count
}

const pullConcat = (str) => (
  Number(str.split(':').at(-1)?.replace(/\s/g, ''))
)
const winCount = lowmemRace(
  { time: pullConcat(lines[0]), record: pullConcat(lines[1]) }
)

console.info({ winCount })