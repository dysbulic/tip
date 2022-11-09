#!/usr/bin/env node

import Prompt from 'prompt-sync'

const prompt = Prompt({sigint: true})

const choices = ['Rock', 'Paper', 'Scissors']
let playerScore = 0 
let computerScore  = 0 

const getComputerChoice = () => ( 
  choices[Math.floor(Math.random() * choices.length)].toUpperCase()
)

const getUserChoice = () => {
  let ret
  const options = [...choices, 'Quit'].map((opt) => opt.toUpperCase())
  const messageToTheUser = (
    `[${playerScore}/${computerScore}]: Make Your Selection `
    + `(${choices.join(', ')}, or Quit): `
  )
  while(ret == null) {
    const res = prompt(messageToTheUser)?.toUpperCase()
    ret = options.find((c) => res && c.startsWith(res))
  }
  return ret
}

const scoreRound = (userChoice, computerChoice) => { 
  console.info(`You chose, ${userChoice}, and the computer, ${computerChoice}`)

  if(computerChoice === userChoice) {
    console.log('It was a draw.')
  } else if(
    (computerChoice === 'ROCK' && userChoice === 'PAPER')
    || (computerChoice === 'PAPER' && userChoice === 'SCISSORS')
    || (computerChoice === 'SCISSORS' && userChoice === 'ROCK')
  ) {
    console.log('You won!')
    ++playerScore
  } else {
    console.log('🖥 won!')
    ++computerScore 
  }
 }

let done = false
while(!done) {
  const userChoice = getUserChoice()
  done = userChoice === 'QUIT'
  if(!done) {
    const computerChoice = getComputerChoice()
    scoreRound(userChoice, computerChoice)
  }
}
