import { load } from 'https://deno.land/std/dotenv/mod.ts'
import {
  startOAuthServer,
  getAccessToken,
  initialize,
  refreshAccessToken,
  makeRequest,
} from './Deno networking.ts'

interface Credentials {
  clientId: string
  clientSecret: string
  redirectUri: string
}

interface TokenResponse {
  access_token: string
  expires_in: number
  refresh_token?: string
  scope: string
  token_type: string
}

class YouTubePlaylistGenerator {
  private accessToken: string | null = null
  private readonly AUTH_ENDPOINT = 'https://accounts.google.com/o/oauth2/v2/auth'
  private readonly TOKEN_ENDPOINT = 'https://oauth2.googleapis.com/token'
  private readonly SCOPE = 'https://www.googleapis.com/auth/youtube.force-ssl'
  private readonly API_ENDPOINT = 'https://www.googleapis.com/youtube/v3'
  private startOAuthServer
  private getAccessToken
  private initialize
  private refreshAccessToken
  private makeRequest

  constructor(private credentials: Credentials) {
    this.startOAuthServer = startOAuthServer.bind(this)
    this.getAccessToken = getAccessToken
    this.initialize = initialize
    this.refreshAccessToken = refreshAccessToken
    this.makeRequest = makeRequest
  }

  async createPlaylist(title: string, description = ''): Promise<string> {
    const response = await this.makeRequest('/playlists', 'POST', {
      snippet: {
        title,
        description,
      },
      status: {
        privacyStatus: 'private',
      },
    })

    return (response as { id: string }).id
  }

  async searchVideo(query: string): Promise<string | null> {
    const params = new URLSearchParams({
      part: 'id',
      maxResults: String(1),
      q: `${query} official music video`,
      type: 'video',
    })

    const response = await this.makeRequest(`/search?${params}`)
    const items = (response as { items: Array<{ id: { videoId: string } }> }).items
    return items[0]?.id?.videoId || null
  }

  async addToPlaylist(playlistId: string, videoId: string): Promise<void> {
    await this.makeRequest('/playlistItems', 'POST', {
      snippet: {
        playlistId,
        resourceId: {
          kind: 'youtube#video',
          videoId,
        },
      },
    })
  }

  async generatePlaylistFromFile(
    filePath: string,
    playlistName: string
  ): Promise<string> {
    await this.initialize()

    const fileContent = await Deno.readTextFile(filePath)
    const songs = (
      fileContent
      .split('\n')
      .map((line) => line.trim())
      .filter((line) => line.length > 0)
    )

    const playlistId = await this.createPlaylist(playlistName)
    console.log(`Created playlist: ${playlistId}`)

    for(const song of songs) {
      try {
        console.log(`Searching for: ${song}`)
        const videoId = await this.searchVideo(song)

        if(videoId) {
          await this.addToPlaylist(playlistId, videoId)
          console.log(`Added: ${song}`)
        } else {
          console.log(`Could not find: ${song}`)
        }
      } catch(err) {
        console.error(`Error adding song "${song}":`, err)
      }
    }

    return playlistId
  }
}

if(import.meta.main) {
  const env = await load()

  if(!env['CLIENT_ID']) {
    throw new Error('`$CLIENT_ID` is not set.')
  }
  if(!env['CLIENT_SECRET']) {
    throw new Error('`$CLIENT_SECRET` is not set.')
  }

  const generator = new YouTubePlaylistGenerator({
    clientId: env['CLIENT_ID'],
    clientSecret: env['CLIENT_SECRET'],
    redirectUri: 'http://localhost:8000/oauth2callback',
  })

  try {
    const [songList, listTitle] = Deno.args

    if(!songList) {
      throw new Error(
        'Usage: deno run playlist.ts <songs file> [playlist name]'
      )
    }

    const playlistId = await generator.generatePlaylistFromFile(
      songList, listTitle,
    )
    console.log(`Playlist created: https://www.youtube.com/playlist?list=${playlistId}`)
  } catch(error) {
    console.error({ error })
  }
}