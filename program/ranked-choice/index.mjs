#!/usr/bin/env node

// From: https://zachkobrinsky.medium.com/ranked-choice-voting-algorithms-in-javascript-898f06cf5b17

// make seed data (fake voters) for testing
function makeVoters(n, candidates) {
  let voters = []
  for (let i = 0; i < n; i++) {
    let voter = {}
    let choices = [...candidates]
    for (let j = 1; j <= 5; j++) {
      let idx = Math.floor(Math.random() * choices.length)
      let choice = choices[idx]

      voter[j] = choice
      choices.splice(idx, 1)
    }
    voters.push(voter)
  }
//   this makes new singly-linked lists (Voter class) for each voter object and returns them.
  return voters.map(voter => new Voter(voter, 5))
}

console.time("rank choice")

function rankChoice(voters) {
  let results = {}
  let majority = voters.length / 2
  let winners = [],
    losers = []

  //     get everybody's first choice
  voters.forEach((voter, index) => {
    if (voter.root && voter.root.val) {
      results[voter.root.val] = results[voter.root.val] + 1 || 1
    } else {
      // if voter is out of votes, remove them from array
      voters.splice(index, 1)
    }
  })

//   get the highest number of votes and the lowest
  let max = Math.max(...Object.values(results))
  let min = Math.min(...Object.values(results))

//   push the candidates (keys from the results object) into the winners array if their 
//   value matches the highest.
//   I made this as an array in case there were ties
  for (let candidate in results) {
    if (results[candidate] === max) {
      winners.push(candidate)
    }

//   push the candidates (keys from the results object) into the losers array if their 
//     value matches the highest
    if (results[candidate] === min) {
      losers.push(candidate)
    }
  }

//   this is my base case for recursion, and returns the actual winner
//   basically it says, "if there is a candidate whose votes are greater than the 
//   majority, and if there is only one candidate among the winners(no tie), 
//   return that candidate and that's your winner!
  
//   If there is someone who has the majority, but there is a tie, 
//   it will continue to recurse, and get the next choices for voters to break the tie.
  if (max >= majority && winners.length === 1) {
    console.timeEnd("rank choice")
    return winners[0]
  }

//   This part is important. This is where we remove the first choice of voters whose 
//   first choice lost this round.
  voters.forEach(voter => {
    if (voter.root && losers.includes(voter.root.val)) voter.shift()
  })

//   Then we recurse!
  return rankChoice(voters)
}

class Node {
  constructor(val) {
    this.val = val
    this.next = null
  }
}

class Voter {
  constructor(voter, numChoices) {
    this.root = new Node(voter[1])

    let current = this.root
    for (let i = 2; i <= numChoices; i++) {
      current.next = new Node(voter[i])
      current = current.next
    }
    return this
  }

  shift() {
    if (!this.root) return null
    let returnVal = this.root
    if (this.root.next) {
      this.root = this.root.next
    } else {
      this.root = null
    }
    return returnVal
  }
}

let candidates = [
  "Eric Adams",
  "Shaun Donovan",
  "Kathryn Garcia",
  "Ray McGuire",
  "Dianne Morales",
  "Scott Stringer",
  "Maya Wiley",
  "Andrew Yang",
]

// Use a lower number for testing. It can have a length run time. 
// It took seven minutes when I ran it with the approximate number of 
// actual voters (~3 milliion)/
let voters = makeVoters(300, candidates)
console.log(rankChoice(voters))