  type strUUID = '{number}-{number}-{number}'
  type bckUUID = `${number}-${number}-${number}`
  export const one: strUUID = '1-1-1'
  export const two: bckUUID = '2-2-2'
  export const three: strUUID = '3-3-3' as '{number}-{number}-{number}'
