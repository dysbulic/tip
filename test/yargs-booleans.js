#!/usr/bin/env node
'use strict'

const _ = require('lodash')
const { argv } = (
  require('yargs')
  .options({
    'boolean': {
      alias: 'b',
      description: 'Some boolean flag',
      type: 'boolean'
    }
  })
)
console.log(_.pick(argv, '_', 'boolean', 'b'))