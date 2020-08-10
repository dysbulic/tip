#!/usr/bin/env node

const multihash = require('multihashes')
const CID = require('cids')
const buffer = Buffer.from('ADD4614B6FE3BB8E7DDDCAAB0EA97C476FBD4FFE288F2A4912CB06F1A47DCFA0', 'hex')
const encoded = multihash.encode(buffer, 'sha2-256')
const cid = new CID(1, 'dag-pb', encoded)
console.log(cid.toString())

