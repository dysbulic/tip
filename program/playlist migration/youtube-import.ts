import * as fs from 'node:fs'
import * as https from 'node:https'
import * as http from 'node:http'
import * as dotenv from 'dotenv'
import yargs from 'yargs'
import { hideBin } from 'yargs/helpers'
import { URL } from 'node:url'

// Load environment variables from .env file
dotenv.config()

interface CSVTrack {
  trackName: string
  artist: string
}

interface FailedTrack {
  trackName: string
  artist: string
  reason: string
}

interface PlaylistVideo {
  videoId: string
  title: string
  channel: string
}

interface SearchResult {
  videoId: string
  title: string
  channelTitle: string
}

interface OAuthTokens {
  access_token: string
  refresh_token?: string
  expires_in: number
  token_type: string
}

class YouTubeMusicPlaylistImporter {
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
    const scope = 'https://www.googleapis.com/auth/youtube'

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
  private async apiRequest(endpoint: string, method: string = 'GET', body?: string): Promise<any> {
    const maxRetries = 3
    let retryCount = 0

    while (retryCount <= maxRetries) {
      try {
        return await this.makeRequest(endpoint, method, body)
      } catch (error: any) {
        const isRateLimited = this.isRateLimitError(error)

        if (isRateLimited && retryCount < maxRetries) {
          retryCount++
          const waitTime = this.getRetryWaitTime(retryCount)
          console.log(`\n⚠️  Rate limit detected. Waiting ${waitTime / 1000} seconds before retry ${retryCount}/${maxRetries}...`)
          await new Promise(resolve => setTimeout(resolve, waitTime))
          continue
        }

        throw error
      }
    }

    throw new Error('Max retries exceeded')
  }

  /**
   * Check if error is a rate limit error
   */
  private isRateLimitError(error: any): boolean {
    const errorMessage = error?.message?.toLowerCase() || ''
    return (
      errorMessage.includes('rate limit') ||
      errorMessage.includes('quota') ||
      errorMessage.includes('429') ||
      errorMessage.includes('too many requests') ||
      errorMessage.includes('usageLimits') ||
      errorMessage.includes('rateLimitExceeded')
    )
  }

  /**
   * Calculate wait time for retry with exponential backoff
   */
  private getRetryWaitTime(retryCount: number): number {
    // Start with 60 seconds, then exponential backoff
    const baseWait = 60000 // 60 seconds
    return baseWait * Math.pow(2, retryCount - 1)
  }

  /**
   * Make the actual HTTP request
   */
  private async makeRequest(endpoint: string, method: string = 'GET', body?: string): Promise<any> {
    return new Promise((resolve, reject) => {
      const options = {
        hostname: 'www.googleapis.com',
        path: endpoint,
        method: method,
        headers: {
          'Authorization': `Bearer ${this.accessToken}`,
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'Content-Length': body ? Buffer.byteLength(body) : 0
        }
      }

      const req = https.request(options, (res) => {
        let responseBody = ''
        res.on('data', (chunk) => responseBody += chunk)
        res.on('end', () => {
          if (res.statusCode === 200 || res.statusCode === 201) {
            resolve(JSON.parse(responseBody))
          } else {
            const error = JSON.parse(responseBody)
            reject(new Error(`API request failed: ${res.statusCode} - ${error.error?.message || responseBody}`))
          }
        })
      })

      req.on('error', reject)
      if (body) {
        req.write(body)
      }
      req.end()
    })
  }

  /**
   * Parse CSV file
   */
  parseCSV(filePath: string): CSVTrack[] {
    const content = fs.readFileSync(filePath, 'utf-8')
    const lines = content.split('\n').filter(line => line.trim())

    if (lines.length === 0) {
      throw new Error('CSV file is empty')
    }

    // Parse header
    const header = lines[0].split(',').map(h => h.trim().replace(/^"|"$/g, ''))
    const trackNameIndex = header.findIndex(h =>
      h.toLowerCase().includes('track') && h.toLowerCase().includes('name')
    )
    const artistIndex = header.findIndex(h => h.toLowerCase().includes('artist'))

    if (trackNameIndex === -1) {
      throw new Error('Could not find "Track Name" column in CSV')
    }
    if (artistIndex === -1) {
      throw new Error('Could not find "Artist" column in CSV')
    }

    // Parse rows
    const tracks: CSVTrack[] = []
    for (let i = 1; i < lines.length; i++) {
      const line = lines[i]
      const values = this.parseCSVLine(line)

      if (values.length > Math.max(trackNameIndex, artistIndex)) {
        const trackName = values[trackNameIndex].trim()
        const artist = values[artistIndex].trim()

        if (trackName && artist) {
          tracks.push({ trackName, artist })
        }
      }
    }

    return tracks
  }

  /**
   * Parse a CSV line handling quoted fields
   */
  private parseCSVLine(line: string): string[] {
    const result: string[] = []
    let current = ''
    let inQuotes = false

    for (let i = 0; i < line.length; i++) {
      const char = line[i]

      if (char === '"') {
        if (inQuotes && line[i + 1] === '"') {
          current += '"'
          i++
        } else {
          inQuotes = !inQuotes
        }
      } else if (char === ',' && !inQuotes) {
        result.push(current)
        current = ''
      } else {
        current += char
      }
    }

    result.push(current)
    return result
  }

  /**
   * Search for a track on YouTube Music
   */
  async searchTrack(trackName: string, artist: string): Promise<SearchResult | null> {
    const query = `${artist} ${trackName}`
    const endpoint = `/youtube/v3/search?part=snippet&q=${encodeURIComponent(query)}&type=video&videoCategoryId=10&maxResults=5`

    try {
      const response = await this.apiRequest(endpoint)

      if (response.items && response.items.length > 0) {
        const firstResult = response.items[0]
        return {
          videoId: firstResult.id.videoId,
          title: firstResult.snippet.title,
          channelTitle: firstResult.snippet.channelTitle
        }
      }
    } catch (error: any) {
      // Check if it's a rate limit error that wasn't handled
      if (this.isRateLimitError(error)) {
        console.error(`  ⚠️  Rate limit error (max retries exceeded): ${error.message}`)
      } else {
        console.error(`  Search error: ${error.message}`)
      }
    }

    return null
  }

  /**
   * Find playlist by name
   */
  async findPlaylistByName(name: string): Promise<string | null> {
    let pageToken: string | undefined = undefined

    do {
      const pageParam = pageToken ? `&pageToken=${pageToken}` : ''
      const endpoint = `/youtube/v3/playlists?part=snippet&mine=true&maxResults=50${pageParam}`

      try {
        const response = await this.apiRequest(endpoint)

        for (const playlist of response.items) {
          if (playlist.snippet.title === name) {
            return playlist.id
          }
        }

        pageToken = response.nextPageToken
      } catch (error) {
        console.error(`Error searching for playlist: ${error}`)
        return null
      }
    } while (pageToken)

    return null
  }

  /**
   * Get all video IDs from a playlist
   */
  async getPlaylistVideoIds(playlistId: string): Promise<Set<string>> {
    const videoIds = new Set<string>()
    let pageToken: string | undefined = undefined

    do {
      const pageParam = pageToken ? `&pageToken=${pageToken}` : ''
      const endpoint = `/youtube/v3/playlistItems?part=contentDetails&playlistId=${playlistId}&maxResults=50${pageParam}`

      try {
        const response = await this.apiRequest(endpoint)

        for (const item of response.items) {
          if (item.contentDetails?.videoId) {
            videoIds.add(item.contentDetails.videoId)
          }
        }

        pageToken = response.nextPageToken
      } catch (error) {
        console.error(`Error fetching playlist videos: ${error}`)
        break
      }
    } while (pageToken)

    return videoIds
  }

  /**
   * Get all videos from a playlist with metadata
   */
  async getPlaylistVideos(playlistId: string): Promise<PlaylistVideo[]> {
    const videos: PlaylistVideo[] = []
    let pageToken: string | undefined = undefined

    do {
      const pageParam = pageToken ? `&pageToken=${pageToken}` : ''
      const endpoint = `/youtube/v3/playlistItems?part=snippet&playlistId=${playlistId}&maxResults=50${pageParam}`

      try {
        const response = await this.apiRequest(endpoint)

        for (const item of response.items) {
          if (item.snippet?.resourceId?.videoId) {
            videos.push({
              videoId: item.snippet.resourceId.videoId,
              title: item.snippet.title,
              channel: item.snippet.videoOwnerChannelTitle || item.snippet.channelTitle
            })
          }
        }

        pageToken = response.nextPageToken
      } catch (error) {
        console.error(`Error fetching playlist videos: ${error}`)
        break
      }
    } while (pageToken)

    return videos
  }

  /**
   * Normalize string for fuzzy matching (lowercase, remove special chars, extra spaces)
   */
  private normalizeForMatching(str: string): string {
    return str
      .toLowerCase()
      .replace(/[^\w\s]/g, ' ')  // Replace special chars with spaces
      .replace(/\s+/g, ' ')       // Collapse multiple spaces
      .trim()
  }

  /**
   * Check if a track likely matches an existing video
   * Returns the matching video if found
   */
  private findMatchingVideo(trackName: string, artist: string, existingVideos: PlaylistVideo[]): PlaylistVideo | null {
    const normalizedTrack = this.normalizeForMatching(trackName)
    const normalizedArtist = this.normalizeForMatching(artist)

    for (const video of existingVideos) {
      const normalizedTitle = this.normalizeForMatching(video.title)
      const normalizedChannel = this.normalizeForMatching(video.channel)

      // Check various common patterns
      const patterns = [
        // "Artist - Track"
        `${normalizedArtist} ${normalizedTrack}`,
        // "Track - Artist" (less common but happens)
        `${normalizedTrack} ${normalizedArtist}`,
        // Just track name (if channel matches artist)
        normalizedTrack,
      ]

      for (const pattern of patterns) {
        // Check if title contains this pattern
        if (normalizedTitle.includes(pattern)) {
          // Additional check: artist name should be in title or channel
          if (normalizedTitle.includes(normalizedArtist) || normalizedChannel.includes(normalizedArtist)) {
            return video
          }
        }
      }

      // Also check reverse: does title contain both track and artist somewhere?
      if (normalizedTitle.includes(normalizedTrack) &&
          (normalizedTitle.includes(normalizedArtist) || normalizedChannel.includes(normalizedArtist))) {
        return video
      }
    }

    return null
  }

  /**
   * Create a new playlist
   */
  async createPlaylist(name: string, description: string = ''): Promise<string> {
    const body = JSON.stringify({
      snippet: {
        title: name,
        description: description
      },
      status: {
        privacyStatus: 'private'
      }
    })

    const response = await this.apiRequest('/youtube/v3/playlists?part=snippet,status', 'POST', body)
    return response.id
  }

  /**
   * Find or create playlist
   */
  async findOrCreatePlaylist(name: string, description: string = '', useExisting: boolean = true): Promise<{ id: string, existed: boolean }> {
    if (useExisting) {
      console.log(`Checking if playlist "${name}" already exists...`)
      const existingId = await this.findPlaylistByName(name)

      if (existingId) {
        console.log(`Found existing playlist: ${existingId}`)
        return { id: existingId, existed: true }
      }

      console.log('Playlist not found, creating new one...')
    }

    const newId = await this.createPlaylist(name, description)
    return { id: newId, existed: false }
  }

  /**
   * Add video to playlist
   */
  async addToPlaylist(playlistId: string, videoId: string): Promise<void> {
    const body = JSON.stringify({
      snippet: {
        playlistId: playlistId,
        resourceId: {
          kind: 'youtube#video',
          videoId: videoId
        }
      }
    })

    await this.apiRequest('/youtube/v3/playlistItems?part=snippet', 'POST', body)
  }

  /**
   * Import tracks from CSV to playlist
   */
  async importFromCSV(csvPath: string, playlistName: string, useExisting: boolean = true): Promise<void> {
    console.log(`Reading CSV file: ${csvPath}`)
    const tracks = this.parseCSV(csvPath)
    console.log(`Found ${tracks.length} tracks in CSV\n`)

    // Find or create playlist
    const { id: playlistId, existed } = await this.findOrCreatePlaylist(
      playlistName,
      `Imported from ${csvPath}`,
      useExisting
    )

    if (!existed) {
      console.log(`Playlist created: ${playlistId}`)
    }
    console.log(`View at: https://www.youtube.com/playlist?list=${playlistId}\n`)

    // Get existing videos in playlist if it already existed
    let existingVideos: PlaylistVideo[] = []
    let existingVideoIds = new Set<string>()
    if (existed) {
      console.log('Fetching existing videos in playlist...')
      existingVideos = await this.getPlaylistVideos(playlistId)
      existingVideoIds = new Set(existingVideos.map(v => v.videoId))
      console.log(`  Found ${existingVideos.length} existing videos\n`)
    }

    let added = 0
    let skipped = 0
    let notFound = 0
    const failedTracks: FailedTrack[] = []
    const skippedTracks: FailedTrack[] = []

    for (let i = 0; i < tracks.length; i++) {
      const track = tracks[i]
      console.log(`[${i + 1}/${tracks.length}] Checking: ${track.artist} - ${track.trackName}`)

      // First, check if track matches an existing video
      const matchingVideo = this.findMatchingVideo(track.trackName, track.artist, existingVideos)
      if (matchingVideo) {
        console.log(`  ⊘ Already in playlist: ${matchingVideo.title}`)
        skipped++
        skippedTracks.push({
          trackName: track.trackName,
          artist: track.artist,
          reason: `Already in playlist as: ${matchingVideo.title}`
        })
        continue
      }

      // Not found in existing videos, search for it
      console.log(`  Searching...`)
      const result = await this.searchTrack(track.trackName, track.artist)

      if (result) {
        // Double-check the video isn't already there (by ID)
        if (existingVideoIds.has(result.videoId)) {
          console.log(`  Found: ${result.title} (${result.channelTitle})`)
          console.log(`  ⊘ Already in playlist, skipping`)
          skipped++
          skippedTracks.push({
            trackName: track.trackName,
            artist: track.artist,
            reason: `Already in playlist as: ${result.title}`
          })
        } else {
          console.log(`  Found: ${result.title} (${result.channelTitle})`)
          try {
            await this.addToPlaylist(playlistId, result.videoId)
            console.log(`  ✓ Added to playlist`)
            added++
            existingVideoIds.add(result.videoId) // Update our local cache
            existingVideos.push({
              videoId: result.videoId,
              title: result.title,
              channel: result.channelTitle
            })
          } catch (error) {
            console.error(`  ✗ Failed to add: ${error}`)
            failedTracks.push({
              trackName: track.trackName,
              artist: track.artist,
              reason: `Failed to add to playlist: ${error}`
            })
          }
        }
      } else {
        console.log(`  ✗ Not found`)
        notFound++
        failedTracks.push({
          trackName: track.trackName,
          artist: track.artist,
          reason: 'Not found on YouTube Music'
        })
      }

      // Small delay to avoid rate limits
      if (i < tracks.length - 1) {
        await new Promise(resolve => setTimeout(resolve, 500))
      }
    }

    console.log(`\nImport complete!`)
    console.log(`  Added: ${added}`)
    if (skipped > 0) {
      console.log(`  Skipped (already in playlist): ${skipped}`)
    }
    console.log(`  Not found: ${notFound}`)
    console.log(`  Total: ${tracks.length}`)
    console.log(`\nPlaylist: https://www.youtube.com/playlist?list=${playlistId}`)

    // Write log file if any failed or skipped tracks
    if (failedTracks.length > 0 || skippedTracks.length > 0) {
      const logFile = this.writeImportLog(failedTracks, skippedTracks, playlistName)
      console.log(`\nImport log written to: ${logFile}`)
    }
  }

  /**
   * Import tracks from multiple CSV files to a single playlist
   */
  async importFromMultipleCSVs(csvPaths: string[], playlistName: string, useExisting: boolean = true): Promise<void> {
    console.log(`Reading ${csvPaths.length} CSV file(s)...\n`)

    // Parse all CSV files
    const allTracks: CSVTrack[] = []
    for (const csvPath of csvPaths) {
      console.log(`Reading: ${csvPath}`)
      try {
        const tracks = this.parseCSV(csvPath)
        console.log(`  Found ${tracks.length} tracks`)
        allTracks.push(...tracks)
      } catch (error) {
        console.error(`  Error reading ${csvPath}: ${error}`)
      }
    }

    if (allTracks.length === 0) {
      console.error('\nNo tracks found in any CSV files')
      return
    }

    // Remove duplicates based on track name and artist
    const uniqueTracks = this.removeDuplicates(allTracks)
    const duplicatesRemoved = allTracks.length - uniqueTracks.length

    console.log(`\nTotal tracks: ${allTracks.length}`)
    if (duplicatesRemoved > 0) {
      console.log(`Duplicates removed: ${duplicatesRemoved}`)
      console.log(`Unique tracks: ${uniqueTracks.length}`)
    }

    // Find or create playlist
    const sources = csvPaths.length > 3
      ? `${csvPaths.length} CSV files`
      : csvPaths.join(', ')
    const { id: playlistId, existed } = await this.findOrCreatePlaylist(
      playlistName,
      `Imported from ${sources}`,
      useExisting
    )

    if (!existed) {
      console.log(`Playlist created: ${playlistId}`)
    }
    console.log(`View at: https://www.youtube.com/playlist?list=${playlistId}\n`)

    // Get existing videos in playlist if it already existed
    let existingVideos: PlaylistVideo[] = []
    let existingVideoIds = new Set<string>()
    if (existed) {
      console.log('Fetching existing videos in playlist...')
      existingVideos = await this.getPlaylistVideos(playlistId)
      existingVideoIds = new Set(existingVideos.map(v => v.videoId))
      console.log(`  Found ${existingVideos.length} existing videos\n`)
    }

    let added = 0
    let skipped = 0
    let notFound = 0
    const failedTracks: FailedTrack[] = []
    const skippedTracks: FailedTrack[] = []

    for (let i = 0; i < uniqueTracks.length; i++) {
      const track = uniqueTracks[i]
      console.log(`[${i + 1}/${uniqueTracks.length}] Checking: ${track.artist} - ${track.trackName}`)

      // First, check if track matches an existing video
      const matchingVideo = this.findMatchingVideo(track.trackName, track.artist, existingVideos)
      if (matchingVideo) {
        console.log(`  ⊘ Already in playlist: ${matchingVideo.title}`)
        skipped++
        skippedTracks.push({
          trackName: track.trackName,
          artist: track.artist,
          reason: `Already in playlist as: ${matchingVideo.title}`
        })
        continue
      }

      // Not found in existing videos, search for it
      console.log(`  Searching...`)
      const result = await this.searchTrack(track.trackName, track.artist)

      if (result) {
        // Double-check the video isn't already there (by ID)
        if (existingVideoIds.has(result.videoId)) {
          console.log(`  Found: ${result.title} (${result.channelTitle})`)
          console.log(`  ⊘ Already in playlist, skipping`)
          skipped++
          skippedTracks.push({
            trackName: track.trackName,
            artist: track.artist,
            reason: `Already in playlist as: ${result.title}`
          })
        } else {
          console.log(`  Found: ${result.title} (${result.channelTitle})`)
          try {
            await this.addToPlaylist(playlistId, result.videoId)
            console.log(`  ✓ Added to playlist`)
            added++
            existingVideoIds.add(result.videoId) // Update our local cache
            existingVideos.push({
              videoId: result.videoId,
              title: result.title,
              channel: result.channelTitle
            })
          } catch (error) {
            console.error(`  ✗ Failed to add: ${error}`)
            failedTracks.push({
              trackName: track.trackName,
              artist: track.artist,
              reason: `Failed to add to playlist: ${error}`
            })
          }
        }
      } else {
        console.log(`  ✗ Not found`)
        notFound++
        failedTracks.push({
          trackName: track.trackName,
          artist: track.artist,
          reason: 'Not found on YouTube Music'
        })
      }

      // Small delay to avoid rate limits
      if (i < uniqueTracks.length - 1) {
        await new Promise(resolve => setTimeout(resolve, 500))
      }
    }

    console.log(`\nImport complete!`)
    console.log(`  Added: ${added}`)
    if (skipped > 0) {
      console.log(`  Skipped (already in playlist): ${skipped}`)
    }
    console.log(`  Not found: ${notFound}`)
    console.log(`  Total: ${uniqueTracks.length}`)
    console.log(`\nPlaylist: https://www.youtube.com/playlist?list=${playlistId}`)

    // Write log file if any failed or skipped tracks
    if (failedTracks.length > 0 || skippedTracks.length > 0) {
      const logFile = this.writeImportLog(failedTracks, skippedTracks, playlistName)
      console.log(`\nImport log written to: ${logFile}`)
    }
  }

  /**
   * Remove duplicate tracks based on track name and artist
   */
  private removeDuplicates(tracks: CSVTrack[]): CSVTrack[] {
    const seen = new Set<string>()
    const unique: CSVTrack[] = []

    for (const track of tracks) {
      const key = `${track.artist.toLowerCase()}|${track.trackName.toLowerCase()}`
      if (!seen.has(key)) {
        seen.add(key)
        unique.push(track)
      }
    }

    return unique
  }

  /**
   * Write import log with failed and skipped tracks
   */
  private writeImportLog(failedTracks: FailedTrack[], skippedTracks: FailedTrack[], playlistName: string): string {
    if (failedTracks.length === 0 && skippedTracks.length === 0) {
      return ''
    }

    const timestamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, -5)
    const sanitizedName = playlistName.replace(/[^a-z0-9]/gi, '_').toLowerCase()
    const logFileName = `import_log_${sanitizedName}_${timestamp}.txt`

    const logContent: string[] = [
      `Import Log`,
      `Playlist: ${playlistName}`,
      `Date: ${new Date().toISOString()}`,
      `Total Skipped: ${skippedTracks.length}`,
      `Total Failed: ${failedTracks.length}`,
      '',
      '='.repeat(80),
      ''
    ]

    if (skippedTracks.length > 0) {
      logContent.push(`SKIPPED TRACKS (already in playlist)`)
      logContent.push('')
      for (const skipped of skippedTracks) {
        logContent.push(`Artist: ${skipped.artist}`)
        logContent.push(`Track: ${skipped.trackName}`)
        logContent.push(`Reason: ${skipped.reason}`)
        logContent.push('')
      }
      logContent.push('='.repeat(80))
      logContent.push('')
    }

    if (failedTracks.length > 0) {
      logContent.push(`FAILED TRACKS (not found or errors)`)
      logContent.push('')
      for (const failed of failedTracks) {
        logContent.push(`Artist: ${failed.artist}`)
        logContent.push(`Track: ${failed.trackName}`)
        logContent.push(`Reason: ${failed.reason}`)
        logContent.push('')
      }
    }

    fs.writeFileSync(logFileName, logContent.join('\n'))
    return logFileName
  }

  /**
   * Write failed tracks to log file (legacy method for backward compatibility)
   */
  private writeFailedTracksLog(failedTracks: FailedTrack[], playlistName: string): string {
    return this.writeImportLog(failedTracks, [], playlistName)
  }
}

/**
 * Main execution
 */
async function main() {
  // Parse command line arguments with yargs
  const argv = await yargs(hideBin(process.argv))
    .command('$0 <csv...>', 'Import tracks from CSV files to YouTube Music playlist', (yargs) => {
      return yargs
        .positional('csv', {
          describe: 'Path(s) to CSV file(s) with Track Name and Artist columns',
          type: 'string',
          array: true,
          demandOption: true
        })
    })
    .option('playlist', {
      alias: 'p',
      type: 'string',
      description: 'Name for the playlist',
      demandOption: true
    })
    .option('new', {
      alias: 'n',
      type: 'boolean',
      description: 'Always create a new playlist (do not use existing)',
      default: false
    })
    .example('$0 tracks.csv -p "My Playlist"', 'Import to existing or create new playlist')
    .example('$0 tracks.csv -p "My Playlist" --new', 'Always create new playlist')
    .example('$0 tracks1.csv tracks2.csv -p "Combined"', 'Import multiple CSVs')
    .example('$0 *.csv --playlist "All Songs"', 'Import all CSV files in directory')
    .help()
    .alias('help', 'h')
    .version('1.0.0')
    .alias('version', 'v')
    .argv

  const csvPaths = argv.csv as string[]
  const playlistName = argv.playlist
  const useExisting = !argv.new

  // Check if CSV files exist
  for (const csvPath of csvPaths) {
    if (!fs.existsSync(csvPath)) {
      console.error(`Error: CSV file not found: ${csvPath}`)
      process.exit(1)
    }
  }

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
    const importer = new YouTubeMusicPlaylistImporter(clientId, clientSecret)
    await importer.authenticate()
    await importer.importFromMultipleCSVs(csvPaths, playlistName, useExisting)
  } catch (error) {
    console.error('Import failed:', error)
    process.exit(1)
  }
}

// Run if called directly
if (typeof(require) === 'undefined' || require.main === module) {
  main()
}

export { YouTubeMusicPlaylistImporter }