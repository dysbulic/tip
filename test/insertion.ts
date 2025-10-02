function addHtmlAttr(filename: string, line: string, loc: number) {
  // match a single div, no attr <div></div>
  const cwd = process.cwd()
  const htmlTagReg1 = /<(\w+)(\s*\/?>)/g
  const htmlTagReg2 = /<(\w+)\s+([\w|:])/g
  // stitching html parameters fileName: location
  const replaceValue = (_whole: string, tag: string, rest: string) => {
    let locAttr = ` data-loc="${filename.replace(cwd, '')}:${loc}"`
    return `<${tag}${tag !== 'title' ? locAttr : ''} ${rest}`
  }
  const result = line.replace(htmlTagReg2, replaceValue)
    .replace(htmlTagReg1, replaceValue)
  return result
}

console.debug({ l1: addHtmlAttr('test.ts', '<testing>', 33) })
console.debug({ l1: addHtmlAttr('test.ts', '<testing/>', 33) })
console.debug({ l1: addHtmlAttr('test.ts', '<testing with="attributes"/>', 33) })
console.debug({ l1: addHtmlAttr('test.ts', '<title>', 33) })
