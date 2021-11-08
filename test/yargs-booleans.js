#!/usr/bin/env node
'use strict';

var _ = require('lodash');
var argv = require('yargs')
  .options({
    'boolean': {
      alias: 'b',
      description: 'Some boolean flag',
      type: 'boolean'
    }
  })
  .argv;

console.log(_.pick(argv, '_', 'boolean', 'b'));