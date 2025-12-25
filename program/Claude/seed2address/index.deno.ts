import { mnemonicToSeedSync } from 'npm:@scure/bip39'
import { HDKey } from 'npm:@scure/bip32'
import { keccak_256 } from 'npm:@noble/hashes/sha3.js'
import { bytesToHex } from 'npm:@noble/hashes/utils.js'
import { secp256k1 } from 'npm:@noble/curves/secp256k1.js'

function publicKeyToAddress(publicKey: Uint8Array): string {
  const pubKeyWithoutPrefix = publicKey.slice(1)
  const hash = keccak_256(pubKeyWithoutPrefix)
  const addressBytes = hash.slice(-20)
  return checksumAddress(bytesToHex(addressBytes))
}

function checksumAddress(address: string): string {
  const addr = address.toLowerCase().replace('0x', '')
  const hash = bytesToHex(keccak_256(new TextEncoder().encode(addr)))

  let checksummed = '0x'
  for(let i = 0; i < addr.length; i++) {
    if(parseInt(hash[i], 16) >= 8) {
      checksummed += addr[i].toUpperCase()
    } else {
      checksummed += addr[i]
    }
  }
  return checksummed
}

function deriveAddresses(
  mnemonic: string,
  count: number = 5,
  passphrase: string = ''
): { path: string; address: string; privateKey: string }[] {
  const seed = mnemonicToSeedSync(mnemonic, passphrase)
  const hdkey = HDKey.fromMasterSeed(seed)
  const results: { path: string; address: string; privateKey: string }[] = []

  for (let i = 0; i < count; i++) {
    const path = `m/44'/60'/0'/0/${i}`
    const derived = hdkey.derive(path)

    if (!derived.privateKey) {
      throw new Error(`Failed to derive private key for path ${path}`)
    }

    const publicKey = secp256k1.getPublicKey(derived.privateKey, false)
    const address = publicKeyToAddress(publicKey)

    results.push({
      path,
      address,
      privateKey: '0x' + bytesToHex(derived.privateKey),
    })
  }

  return results
}

const args = Deno.args

if(args.length === 0) {
  console.log('Usage: deno run index.ts <seed phrase> [count] [passphrase]')
  console.log('\nExample:')
  console.log(
    '  deno run index.ts "abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon abandon about" 5'
  )
  Deno.exit(1)
}

const mnemonic = args[0]
const count = parseInt(args[1] || '5', 10)
const passphrase = args[2] || ''

console.log(`\nDeriving ${count} addresses from seed phrase…\n`)

if(passphrase) {
  console.log('(Using BIP-39 passphrase)\n')
}

const addresses = deriveAddresses(mnemonic, count, passphrase)

for(const { path, address, privateKey } of addresses) {
  console.log(`Path: ${path}`)
  console.log(`Address: ${address}`)
  console.log(`Private Key: ${privateKey}`)
  console.log('')
}