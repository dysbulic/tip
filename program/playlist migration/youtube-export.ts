import * as fs from 'fs'
import * as https from 'https'
import * as http from 'http'
import * as dotenv from 'dotenv'
import yargs from 'yargs'
import { hideBin } from 'yargs/helpers'
import { URL } from 'url'

// Load environment variables from .env file
dotenv.config()

interface YouTubeTrack {
  title: string
  artist: string
  channel: string
  description: string
  duration: string
  video_id: string
  url: string
  thumbnail_url: string
}

interface PlaylistExport {
  name: string
  description: string
  channel: string
  total_tracks: number
  tracks: YouTubeTrack[]
}

interface OAuthTokens {
  access_token: string
  refresh_token?: string
  expires_in: number
  token_type: string
}

class YouTubeMusicOAuthExporter {
  private clientId: string
  private clientSecret: string
  private accessToken: string = ''
  private refreshToken: string = ''
  private tokenFile: string = '.youtube-tokens.json'

  constructor(clientId: string, clientSecret: string) {
    this.clientId = clientId
    this.clientSecret = clientSecret
    this.loadTokens()
  }

  /**
   * Load saved tokens from file
   */
  private loadTokens(): void {
    try {
      if (fs.existsSync(this.tokenFile)) {
        const tokens = JSON.parse(fs.readFileSync(this.tokenFile, 'utf-8'))
        this.accessToken = tokens.access_token || ''
        this.refreshToken = tokens.refresh_token || ''
      }
    } catch (error) {
      // File doesn't exist or is invalid, will need to authenticate
    }
  }

  /**
   * Save tokens to file
   */
  private saveTokens(tokens: OAuthTokens): void {
    fs.writeFileSync(this.tokenFile, JSON.stringify(tokens, null, 2))
    this.accessToken = tokens.access_token
    if (tokens.refresh_token) {
      this.refreshToken = tokens.refresh_token
    }
  }

  /**
   * Start OAuth flow with local server
   */
  async authenticate(): Promise<void> {
    if (this.accessToken) {
      console.log('Using existing access token...')
      try {
        // Test if token is valid
        await this.apiRequest('/youtube/v3/channels?part=id&mine=true')
        console.log('Token is valid!')
        return
      } catch (error) {
        console.log('Token expired, refreshing...')
        if (this.refreshToken) {
          try {
            await this.refreshAccessToken()
            return
          } catch (e) {
            console.log('Refresh failed, need new authentication')
          }
        }
      }
    }

    console.log('Starting OAuth authentication...')
    const redirectUri = 'http://localhost:8080/oauth2callback'
    const scope = 'https://www.googleapis.com/auth/youtube.readonly'

    const authUrl = `https://accounts.google.com/o/oauth2/v2/auth?` +
      `client_id=${this.clientId}&` +
      `redirect_uri=${encodeURIComponent(redirectUri)}&` +
      `response_type=code&` +
      `scope=${encodeURIComponent(scope)}&` +
      `access_type=offline&` +
      `prompt=consent`

    console.log('\nPlease visit this URL to authorize the application:')
    console.log(authUrl)
    console.log('\nWaiting for authorization...')

    const code = await this.startCallbackServer(redirectUri)
    await this.exchangeCodeForTokens(code, redirectUri)
    console.log('Authentication successful!')
  }

  /**
   * Start local server to receive OAuth callback
   */
  private startCallbackServer(redirectUri: string): Promise<string> {
    return new Promise((resolve, reject) => {
      const server = http.createServer((req, res) => {
        if (req.url?.startsWith('/oauth2callback')) {
          const url = new URL(req.url, redirectUri)
          const code = url.searchParams.get('code')

          if (code) {
            res.writeHead(200, { 'Content-Type': 'text/html' })
            res.end('<h1>Authorization successful!</h1><p>You can close this window and return to the terminal.</p>')
            server.close()
            resolve(code)
          } else {
            res.writeHead(400, { 'Content-Type': 'text/html' })
            res.end('<h1>Authorization failed</h1><p>No code received.</p>')
            server.close()
            reject(new Error('No authorization code received'))
          }
        }
      })

      server.listen(8080, () => {
        console.log('Local server started on http://localhost:8080')
      })

      server.on('error', (error) => {
        reject(error)
      })
    })
  }

  /**
   * Exchange authorization code for tokens
   */
  private async exchangeCodeForTokens(code: string, redirectUri: string): Promise<void> {
    const postData = new URLSearchParams({
      code: code,
      client_id: this.clientId,
      client_secret: this.clientSecret,
      redirect_uri: redirectUri,
      grant_type: 'authorization_code'
    }).toString()

    return new Promise((resolve, reject) => {
      const options = {
        hostname: 'oauth2.googleapis.com',
        path: '/token',
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Content-Length': postData.length
        }
      }

      const req = https.request(options, (res) => {
        let body = ''
        res.on('data', (chunk) => body += chunk)
        res.on('end', () => {
          if (res.statusCode === 200) {
            const tokens = JSON.parse(body)
            this.saveTokens(tokens)
            resolve()
          } else {
            reject(new Error(`Token exchange failed: ${res.statusCode} ${body}`))
          }
        })
      })

      req.on('error', reject)
      req.write(postData)
      req.end()
    })
  }

  /**
   * Refresh access token using refresh token
   */
  private async refreshAccessToken(): Promise<void> {
    const postData = new URLSearchParams({
      client_id: this.clientId,
      client_secret: this.clientSecret,
      refresh_token: this.refreshToken,
      grant_type: 'refresh_token'
    }).toString()

    return new Promise((resolve, reject) => {
      const options = {
        hostname: 'oauth2.googleapis.com',
        path: '/token',
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Content-Length': postData.length
        }
      }

      const req = https.request(options, (res) => {
        let body = ''
        res.on('data', (chunk) => body += chunk)
        res.on('end', () => {
          if (res.statusCode === 200) {
            const tokens = JSON.parse(body)
            this.saveTokens(tokens)
            resolve()
          } else {
            reject(new Error(`Token refresh failed: ${res.statusCode} ${body}`))
          }
        })
      })

      req.on('error', reject)
      req.write(postData)
      req.end()
    })
  }

  /**
   * Make an API request to YouTube Data API with OAuth token
   */
  private async apiRequest(endpoint: string): Promise<any> {
    return new Promise((resolve, reject) => {
      const options = {
        hostname: 'www.googleapis.com',
        path: endpoint,
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${this.accessToken}`,
          'Accept': 'application/json'
        }
      }

      const req = https.request(options, (res) => {
        let body = ''
        res.on('data', (chunk) => body += chunk)
        res.on('end', () => {
          if (res.statusCode === 200) {
            resolve(JSON.parse(body))
          } else {
            const error = JSON.parse(body)
            reject(new Error(`API request failed: ${res.statusCode} - ${error.error?.message || body}`))
          }
        })
      })

      req.on('error', reject)
      req.end()
    })
  }

  /**
   * Parse ISO 8601 duration to human-readable format
   */
  private parseDuration(isoDuration: string): string {
    const match = isoDuration.match(/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/)
    if (!match) return '0:00'

    const hours = parseInt(match[1] || '0')
    const minutes = parseInt(match[2] || '0')
    const seconds = parseInt(match[3] || '0')

    if (hours > 0) {
      return `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
    }
    return `${minutes}:${seconds.toString().padStart(2, '0')}`
  }

  /**
   * Try to extract artist from title or description
   * Common patterns: "Artist - Title", "Artist: Title", "Title by Artist"
   */
  private extractArtist(title: string, description: string, channel: string): string {
    // Try common title patterns
    const patterns = [
      /^([^-]+?)\s*-\s*(.+)$/,           // "Artist - Title"
      /^([^:]+?)\s*:\s*(.+)$/,           // "Artist: Title"
      /^(.+?)\s+by\s+([^(]+)/i,          // "Title by Artist"
      /^([^|]+?)\s*\|\s*(.+)$/,          // "Artist | Title"
    ]

    for (const pattern of patterns) {
      const match = title.match(pattern)
      if (match) {
        // First group is usually the artist
        const potentialArtist = match[1].trim()
        // Don't return generic terms or very long strings
        if (potentialArtist.length > 3 && potentialArtist.length < 50 &&
            !potentialArtist.toLowerCase().includes('official')) {
          return potentialArtist
        }
      }
    }

    // Try to find "Artist:" or "By:" in description
    const descLines = description.split('\n').slice(0, 5) // Check first 5 lines
    for (const line of descLines) {
      const artistMatch = line.match(/^(?:Artist|By|Performed by):\s*(.+?)$/i)
      if (artistMatch) {
        return artistMatch[1].trim()
      }
    }

    // Fall back to channel name, but clean up common suffixes
    return channel
      .replace(/\s*-\s*Topic$/i, '')
      .replace(/\s*VEVO$/i, '')
      .trim()
  }

  /**
   * Fetch all tracks from a playlist (handles pagination)
   */
  async getPlaylistTracks(playlistId: string): Promise<YouTubeTrack[]> {
    const tracks: YouTubeTrack[] = []
    let pageToken: string | undefined = undefined
    const maxResults = 50
    let pageNumber = 0
    let totalItemsFetched = 0
    let skippedPrivate = 0
    let skippedDeleted = 0

    do {
      pageNumber++
      const pageParam = pageToken ? `&pageToken=${pageToken}` : ''
      const endpoint = `/youtube/v3/playlistItems?part=snippet,contentDetails&playlistId=${playlistId}&maxResults=${maxResults}${pageParam}`

      console.log(`Fetching page ${pageNumber}...`)
      const response = await this.apiRequest(endpoint)
      totalItemsFetched += response.items.length
      console.log(`  Received ${response.items.length} items (total so far: ${totalItemsFetched})`)

      // Get video IDs to fetch durations
      const videoIds = response.items
        .map((item: any) => item.contentDetails.videoId)
        .join(',')

      // Fetch video details to get durations
      const videoEndpoint = `/youtube/v3/videos?part=contentDetails&id=${videoIds}`
      const videoResponse = await this.apiRequest(videoEndpoint)

      // Create a map of video IDs to durations
      const durationMap = new Map<string, string>()
      for (const video of videoResponse.items) {
        durationMap.set(video.id, this.parseDuration(video.contentDetails.duration))
      }

      for (const item of response.items) {
        const videoId = item.contentDetails.videoId
        const snippet = item.snippet

        // Skip private/deleted videos
        if (snippet.title === 'Private video') {
          skippedPrivate++
          continue
        }
        if (snippet.title === 'Deleted video') {
          skippedDeleted++
          continue
        }

        const channel = snippet.videoOwnerChannelTitle || snippet.channelTitle
        const artist = this.extractArtist(snippet.title, snippet.description || '', channel)

        tracks.push({
          title: snippet.title,
          artist: artist,
          channel: channel,
          description: snippet.description,
          duration: durationMap.get(videoId) || 'N/A',
          video_id: videoId,
          url: `https://www.youtube.com/watch?v=${videoId}`,
          thumbnail_url: snippet.thumbnails?.default?.url || ''
        })
      }

      pageToken = response.nextPageToken
      if (pageToken) {
        console.log(`  Next page token exists, continuing...`)
      } else {
        console.log(`  No more pages`)
      }
    } while (pageToken)

    console.log(`\nSummary:`)
    console.log(`  Total items fetched: ${totalItemsFetched}`)
    console.log(`  Videos added: ${tracks.length}`)
    console.log(`  Private videos skipped: ${skippedPrivate}`)
    console.log(`  Deleted videos skipped: ${skippedDeleted}`)
    console.log(`  Missing: ${totalItemsFetched - tracks.length - skippedPrivate - skippedDeleted}`)

    return tracks
  }

  /**
   * Get playlist metadata and all tracks
   */
  async exportPlaylist(playlistId: string): Promise<PlaylistExport> {
    console.log(`Fetching playlist: ${playlistId}`)

    const playlistEndpoint = `/youtube/v3/playlists?part=snippet&id=${playlistId}`
    const playlistData = await this.apiRequest(playlistEndpoint)

    if (!playlistData.items || playlistData.items.length === 0) {
      throw new Error('Playlist not found')
    }

    const playlist = playlistData.items[0]
    const tracks = await this.getPlaylistTracks(playlistId)

    return {
      name: playlist.snippet.title,
      description: playlist.snippet.description || '',
      channel: playlist.snippet.channelTitle,
      total_tracks: tracks.length,
      tracks: tracks
    }
  }

  /**
   * Export playlist to JSON file
   */
  async exportToJSON(playlistId: string, outputPath: string): Promise<void> {
    const playlist = await this.exportPlaylist(playlistId)
    fs.writeFileSync(outputPath, JSON.stringify(playlist, null, 2))
    console.log(`Playlist exported to ${outputPath}`)
  }

  /**
   * Export playlist to CSV file
   */
  async exportToCSV(playlistId: string, outputPath: string): Promise<void> {
    const playlist = await this.exportPlaylist(playlistId)

    const headers = ['Title', 'Artist', 'Channel', 'Duration', 'Video ID', 'URL']
    const rows = playlist.tracks.map(track => [
      `"${track.title.replace(/"/g, '""')}"`,
      `"${track.artist.replace(/"/g, '""')}"`,
      `"${track.channel.replace(/"/g, '""')}"`,
      track.duration,
      track.video_id,
      track.url
    ])

    const csv = [headers.join(','), ...rows.map(row => row.join(','))].join('\n')
    fs.writeFileSync(outputPath, csv)
    console.log(`Playlist exported to ${outputPath}`)
  }
}

/**
 * Main execution
 */
async function main() {
  // Parse command line arguments with yargs
  const argv = await yargs(hideBin(process.argv))
    .command('$0 <playlist>', 'Export a YouTube Music playlist', (yargs) => {
      return yargs
        .positional('playlist', {
          describe: 'YouTube playlist ID to export',
          type: 'string',
          demandOption: true
        })
    })
    .option('format', {
      alias: 'f',
      type: 'string',
      choices: ['json', 'csv', 'both'],
      default: 'both',
      description: 'Output format'
    })
    .option('output', {
      alias: 'o',
      type: 'string',
      description: 'Output filename (without extension)',
      default: undefined
    })
    .example('$0 PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf', 'Export playlist to both JSON and CSV')
    .example('$0 PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf --format json', 'Export only to JSON')
    .example('$0 PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf -o my-playlist', 'Export with custom filename')
    .help()
    .alias('help', 'h')
    .version('1.0.0')
    .alias('version', 'v')
    .argv

  const playlistId = argv.playlist as string
  const format = argv.format
  const outputBase = argv.output || `playlist_${playlistId}`

  // Get OAuth credentials from environment variables
  const clientId = process.env.YOUTUBE_OAUTH_CLIENT_ID
  const clientSecret = process.env.YOUTUBE_OAUTH_CLIENT_SECRET

  if (!clientId || !clientSecret) {
    console.error('Error: YOUTUBE_OAUTH_CLIENT_ID and YOUTUBE_OAUTH_CLIENT_SECRET environment variables are required')
    console.error('Create a .env file with your OAuth credentials:')
    console.error('  YOUTUBE_OAUTH_CLIENT_ID=your_client_id')
    console.error('  YOUTUBE_OAUTH_CLIENT_SECRET=your_client_secret')
    console.error('\nGet your credentials from: https://console.cloud.google.com/apis/credentials')
    console.error('Create OAuth 2.0 Client ID (Desktop app type)')
    console.error('Enable the YouTube Data API v3 for your project')
    process.exit(1)
  }

  try {
    const exporter = new YouTubeMusicOAuthExporter(clientId, clientSecret)
    await exporter.authenticate()

    // Export based on format option
    if (format === 'json' || format === 'both') {
      await exporter.exportToJSON(playlistId, `${outputBase}.json`)
    }

    if (format === 'csv' || format === 'both') {
      await exporter.exportToCSV(playlistId, `${outputBase}.csv`)
    }

    console.log('Export completed successfully!')
  } catch (error) {
    console.error('Export failed:', error)
    process.exit(1)
  }
}

// Run if called directly
if (require.main === module) {
  main()
}

export { YouTubeMusicOAuthExporter, YouTubeTrack, PlaylistExport }