const NPP = require('./newpieceplease')
NPP.onready = async () => {
  console.log('node.id:', (await NPP.node.id()).id)
  console.log('orbitdb.id:', NPP.orbitdb.id)
  console.log('orbitdb.identity.id:', NPP.orbitdb.identity.id)
  console.log('pieces.id:', NPP.pieces.id)

  let cid = await NPP.addNewPiece("QmfK41y6mcGSqLNxy8CTSujmL12KKJs4CWT788H7NjdTUa")
  console.info('Add CID:', cid)
  let content = await NPP.node.dag.get(cid)
  console.log(content.value.payload)

  cid = await NPP.addNewPiece("Qmebjp94PKGpQ9oUy1MYyC1z51k82NfWsYJ12cRPRMAYuP")
  console.info('Add CID:', cid)
  content = await NPP.node.dag.get(cid)
  console.log(content.value.payload)

  const pieces = NPP.getPieceByInstrument("Piano")
  console.log(`Got ${pieces.length} piece${pieces.length === 1 ? '' : 's'}`)
  const randomPiece = pieces[pieces.length * Math.random() | 0]
  console.log('RNG:', randomPiece)

  const piece = NPP.getPieceByHash('QmfK41y6mcGSqLNxy8CTSujmL12KKJs4CWT788H7NjdTUa')
  cid = await NPP.incrementPracticeCounter(piece)
  console.info('Inc CID:', cid)
  content = await NPP.node.dag.get(cid)
  console.log(content.value.payload)

  cid = await NPP.deletePieceByHash("QmfK41y6mcGSqLNxy8CTSujmL12KKJs4CWT788H7NjdTUa")
  console.info('Del CID:', cid)
  content = await NPP.node.dag.get(cid)
  console.log(content.value.payload)

  let profileFields = NPP.getAllProfileFields()
  console.log('Pre-Profile:', profileFields)

  await NPP.updateProfileField("username", "aphelionz")
  profileFields = NPP.getAllProfileFields()
  console.log('Post-Profile:', profileFields)
  await NPP.deleteProfileField("username")

  const id = await NPP.node.id()
  console.log(id.addresses)

  //console.log(await NPP.node.bootstrap.list())

  const peers = await NPP.getIpfsPeers()
  console.log(`Connected to ${peers.length} peer${peers.length === 1 ? '' : 's'}`)

  NPP.onmessage = console.log

  let data = { this: { is: { a: 'test' } } } // can be any JSON-serializable value
  const hash = "QmXG8yk8UJjMT6qtE2zSxzz3U7z5jSYRgVWLCUFqAVnByM";
  await NPP.sendMessage(hash, data)

  NPP.oncompaniononline = console.log
  NPP.oncompanionnotfound = () => { throw(e) }
}