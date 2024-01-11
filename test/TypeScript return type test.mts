const test = async () => ({ a: 'a' })
const t: { a: string } = await test()
console.debug({ 't.b': t.b })