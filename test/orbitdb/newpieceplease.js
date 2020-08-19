class NewPiecePlease {
  constructor (IPFS, OrbitDB) {
    this.IPFS = IPFS
    this.OrbitDB = OrbitDB

    const _init = async (ipfs) => {
      this.node = ipfs
      const peerInfo = await this.node.id()
      this.orbitdb = await this.OrbitDB.createInstance(this.node)
      this.defaultOptions = { accessController: { write: [this.orbitdb.identity.id] }}
      const docStoreOptions = { ...this.defaultOptions, indexBy: 'hash' }
      this.pieces = await this.orbitdb.docstore('pieces', docStoreOptions)
      await this.pieces.load()
      this.user = await this.orbitdb.kvstore('user', this.defaultOptions)
      await this.user.load()
      this.companions = await this.orbitdb.keyvalue('companions', this.defaultOptions)
      await this.companions.load()

      await this.loadFixtureData({
        'username': Math.floor(Math.random() * 1000000),
        'pieces': this.pieces.id,
        'nodeId': peerInfo.id
      })

      this.node.libp2p.on('peer:connect', this.handlePeerConnected.bind(this))
      await this.node.pubsub.subscribe(peerInfo.id, this.handleMessageReceived.bind(this))

      this.companionConnectionInterval = setInterval(this.connectToCompanions.bind(this), 10000)
      this.connectToCompanions()

      this.onready()
    }

    const config = {
      relay: { enabled: true, hop: { enabled: true, active: true } },
      repo: './ipfs',
      EXPERIMENTAL: { pubsub: true },
      // preload: { enabled: false }, // offline
      // config: { Bootstrap: [], Addresses: { Swarm: [] } } // offline
    }
    if(process.env.PWD.slice(-5) === '-peer') {
      console.info('Configuring as Peer')
      config.config = { Addresses: {
        Swarm: ['/ip4/0.0.0.0/tcp/4004', '/ip4/127.0.0.1/tcp/4005/ws'],
        API: '/ip4/127.0.0.1/tcp/5003',
      }}
    }

    IPFS.create(config).then(_init.bind(this)).catch(e => { throw e })
  }

  async getIpfsPeers() {
    return await this.node.swarm.peers()
  }

  async connectToPeer(multiaddr, protocol = '/p2p-circuit/ipfs/') {
    try {
      await this.node.swarm.connect(protocol + multiaddr)
    } catch(e) {
      throw (e)
    }
  }

  handlePeerConnected(ipfsPeer) {
    const ipfsId = ipfsPeer.id.toB58String()
    setTimeout(async () => {
      await this.sendMessage(ipfsId, { userDb: this.user.id })
    }, 2000)
    if(this.onpeerconnect) this.onpeerconnect(ipfsId)
  }

  async sendMessage(topic, message) {
    try {
      const msgString = JSON.stringify(message)
      const messageBuffer = this.IPFS.Buffer.from(msgString)
      await this.node.pubsub.publish(topic, messageBuffer)
    } catch (e) {
      throw (e)
    }
  }

  async handleMessageReceived (msg) {
    const parsedMsg = JSON.parse(msg.data.toString())
    const msgKeys = Object.keys(parsedMsg)

    switch(msgKeys[0]) {
      case 'userDb':
        var peerDb = await this.orbitdb.open(parsedMsg.userDb)
        peerDb.events.on('replicated', async () => {
          if(peerDb.get('pieces')) {
            await this.companions.set(peerDb.id, peerDb.all)
            this.ondbdiscovered && this.ondbdiscovered(peerDb)
          }
        })
        break
      default:
        break
    }

    if(this.onmessage) this.onmessage(msg)
  }

  getCompanions () {
    return this.companions.all
  }

  async connectToCompanions () {
    const companionIds = Object.values(this.companions.all).map(companion => companion.nodeId)
    const connectedPeerIds = await this.getIpfsPeers()
    await Promise.all(companionIds.map(async (companionId) => {
      if (connectedPeerIds.indexOf(companionId) !== -1) return
      try {
        await this.connectToPeer(companionId)
        this.oncompaniononline && this.oncompaniononline()
      } catch (e) {
        this.oncompanionnotfound && this.oncompanionnotfound()
      }
    }))
  }

  async queryCatalog (queryFn) {
    const dbAddrs = Object.values(this.companions.all).map(peer => peer.pieces)

    const allPieces = await Promise.all(dbAddrs.map(async (addr) => {
      const db = await this.orbitdb.open(addr)
      await db.load()

      return db.query(queryFn)
    }))

    return allPieces.reduce((flatPieces, pieces) => flatPieces.concat(pieces), this.pieces.query(queryFn))
  }

  async addNewPiece(hash, instrument = 'Piano') {
    const existingPiece = this.getPieceByHash(hash)
    if(existingPiece) {
      console.info('Updating:', hash, existingPiece)
      return await this.updatePieceByHash(hash, instrument)
    }

    const dbName = 'counter.' + hash.substr(20, 20)
    const counter = await this.orbitdb.counter(dbName, this.defaultOptions)

    return await this.pieces.put({ hash, instrument, counter: counter.id })
  }

  getAllPieces() {
    const pieces = this.pieces.get('')
    return pieces
  }

  getPieceByHash(hash) {
    const singlePiece = this.pieces.get(hash)[0]
    return singlePiece
  }

  getPieceByInstrument(instrument) {
    return this.pieces.query((piece) => piece.instrument === instrument)
  }

  async updatePieceByHash(hash, instrument = 'Piano') {
    const piece = await this.getPieceByHash(hash)
    piece.instrument = instrument
    const cid = await this.pieces.put(piece)
    return cid
  }

  async deletePieceByHash(hash) {
    const cid = await this.pieces.del(hash)
    return cid
  }

  async getPracticeCount(piece) {
    const counter = await this.orbitdb.counter(piece.counter)
    await counter.load()
    return counter.value
  }
    
  async incrementPracticeCounter(piece) {
    const counter = await this.orbitdb.counter(piece.counter)
    const cid = await counter.inc()
    return cid
  }

  async deleteProfileField(key) {
    const cid = await this.user.del(key)
    return cid
  }
    
  getAllProfileFields() {
    return this.user.all;
  }
    
  getProfileField(key) {
    return this.user.get(key)
  }
    
  async updateProfileField(key, value) {
    const cid = await this.user.set(key, value)
    return cid
  }

  async loadFixtureData (fixtureData) {
    const fixtureKeys = Object.keys(fixtureData)
    for (let i in fixtureKeys) {
      let key = fixtureKeys[i]
      if(!this.user.get(key)) await this.user.set(key, fixtureData[key])
    }
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
