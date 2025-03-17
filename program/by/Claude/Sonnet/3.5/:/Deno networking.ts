interface TokenResponse {
  access_token: string
  expires_in: number
  refresh_token?: string
  scope: string
  token_type: string
}

const AUTH_ENDPOINT = 'https://accounts.google.com/o/oauth2/v2/auth'
export async function initialize() {
  try {
    const refreshToken = await Deno.readTextFile('refresh_token.txt')
    await this.refreshAccessToken(refreshToken)
  } catch(_err) {
    const authCode = await this.startOAuthServer()
    console.debug({ Got: authCode })
    await this.getAccessToken(authCode)
  }
}

export async function startOAuthServer() {
  const params = new URLSearchParams({
    client_id: this.credentials.clientId,
    redirect_uri: this.credentials.redirectUri,
    response_type: 'code',
    scope: this.SCOPE,
    access_type: 'offline',
    prompt: 'consent',
  })

  const authUrl = `${this.AUTH_ENDPOINT}?${params}`
  console.log('Please visit this URL to authorize the application:', authUrl)

  let authCode = ''

  const abort = new AbortController()

  const handler = async (request: Request) => {
    const url = new URL(request.url, 'http://localhost:8000')

    console.debug({ Handling: url })

    if(url.searchParams.has('code')) {
      authCode = url.searchParams.get('code')!
      return new Response(
        '🙌🏿 Authorization successful! 🙌🏿 You can close this window. 🖥️',
        {
          status: 200,
          headers: new Headers({ 'content-type': 'text/plain; charset=utf-8' }),
        }
      )
    }
    abort.abort()
    return new Response('Authorization failed.', {
      status: 400,
      headers: new Headers({ 'content-type': 'text/plain; charset=utf-8' }),
    })
  }
  const server = (
    Deno.serve({ port: 8000, signal: abort.signal }, handler)
  )
  await server.finished
  return authCode
}

export async function getAccessToken(code: string) {
  const params = new URLSearchParams({
    client_id: this.credentials.clientId,
    client_secret: this.credentials.clientSecret,
    code,
    grant_type: 'authorization_code',
    redirect_uri: this.credentials.redirectUri,
  })

  const response = await fetch(this.TOKEN_ENDPOINT, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: params,
  })

  if(!response.ok) {
    console.debug({ params })
    throw new Error(`Failed to get access token. (${response.status}: ${response.statusText})`)
  }

  const tokenData: TokenResponse = await response.json()
  this.accessToken = tokenData.access_token

  if(tokenData.refresh_token) {
    await Deno.writeTextFile(
      'refresh_token.txt',
      tokenData.refresh_token
    )
  }
}

export async function refreshAccessToken(refreshToken: string) {
  const params = new URLSearchParams({
    client_id: this.credentials.clientId,
    client_secret: this.credentials.clientSecret,
    refresh_token: refreshToken,
    grant_type: 'refresh_token',
  })

  const response = await fetch(this.TOKEN_ENDPOINT, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: params,
  })

  if(!response.ok) {
    throw new Error('Failed to refresh access token.')
  }

  const tokenData: TokenResponse = await response.json()
  this.accessToken = tokenData.access_token
}

export async function makeRequest(
  { endpoint, method = 'GET', body }: {
    endpoint: string
    method?: string
    body?: unknown
  }
) {
  if(!this.accessToken) {
    throw new Error('Not authenticated.')
  }
  let url = `${this.API_ENDPOINT}${endpoint}`
  if(method === 'GET' && body) {
    url += `?${body}`
    body = undefined
  }
  console.debug({ Fetching: url, With: body })
  const response = await fetch(url, {
    method,
    headers: {
      Authorization: `Bearer ${this.accessToken}`,
      'Content-Type': 'application/json',
    },
    body: body ? JSON.stringify(body) : undefined,
  })

  if(!response.ok) {
    throw new Error(`API request failed: "${await response.text()}".`)
  }

  return response.json()
}
