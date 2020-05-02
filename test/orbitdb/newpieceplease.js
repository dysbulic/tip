class NewPiecePlease {
  constructor (IPFS, OrbitDB) {
    this.OrbitDB = OrbitDB

    const _init = async (ipfs) => {
      this.node = ipfs
      this.orbitdb = await this.OrbitDB.createInstance(this.node)
      this.defaultOptions = { accessController: { write: [this.orbitdb.identity.id] }}
      const docStoreOptions = { ...this.defaultOptions, indexBy: 'hash' }
      this.pieces = await this.orbitdb.docstore('pieces', docStoreOptions)
      await this.pieces.load()

      this.onready()
    }

    IPFS.create({
      preload: { enabled: false },
      repo: './ipfs',
      EXPERIMENTAL: { pubsub: true },
      config: {
        Bootstrap: [],
        Addresses: { Swarm: [] }
      }
    }).then(_init.bind(this))
    .catch(e => { throw e })
  }

  async addNewPiece(hash, instrument = 'Piano') {
    //const existingPiece = this.getPieceByHash(hash)
    //if(existingPiece) {
      // await this.updatePieceByHash(hash, instrument)
      //return
    //}

    const cid = await this.pieces.put({ hash, instrument })
    return cid
  }
}

try {
  const Ipfs = require('ipfs')
  const OrbitDB = require('orbit-db')

  module.exports = exports = new NewPiecePlease(Ipfs, OrbitDB)
} catch (e) {
  console.log(e)
  window.NPP = new NewPiecePlease(window.Ipfs, window.OrbitDB)
}
