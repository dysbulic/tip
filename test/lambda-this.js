const context = { a: 'testing' }
const arrow = () => console.debug({ arrow: this.a })
const vanilla = function() { console.debug({ [arguments.callee.name]: this.a }) }
arrow.call(context)
vanilla.call(context)