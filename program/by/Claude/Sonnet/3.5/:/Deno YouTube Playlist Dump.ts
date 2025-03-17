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

class YouTubePlaylistExporter {
  private accessToken: string | null = null
  private readonly AUTH_ENDPOINT = 'https://accounts.google.com/o/oauth2/v2/auth'
  private readonly TOKEN_ENDPOINT = 'https://oauth2.googleapis.com/token'
  private readonly SCOPE = 'https://www.googleapis.com/auth/youtube.readonly'
  private readonly API_ENDPOINT = 'https://www.googleapis.com/youtube/v3'
  private startOAuthServer
  private getAccessToken
  private initialize
  private refreshAccessToken
  private makeRequest

  constructor(private credentials: Credentials) {
    this.startOAuthServer = startOAuthServer.bind(this)
    this.getAccessToken = getAccessToken.bind(this)
    this.initialize = initialize.bind(this)
    this.refreshAccessToken = refreshAccessToken.bind(this)
    this.makeRequest = makeRequest.bind(this)
  }

  private extractPlaylistId(url: string): string {
    const regex = /[?&]list=([^&]+)/
    const match = url.match(regex)
    if(!match) {
      throw new Error('Invalid playlist URL')
    }
    return match[1]
  }

  async exportPlaylist(playlistURL: string, outputFile: string): Promise<void> {
    await this.initialize()

    console.debug('Initialized.')

    const playlistId = this.extractPlaylistId(playlistURL)

    console.debug({ Playlist: playlistURL})

    const titles: Array<string> = []
    let nextPageToken: string | undefined

    do {
      const params = new URLSearchParams({
        // part: 'snippet,contentDetails',
        part: 'contentDetails',
        playlistId,
        maxResults: '50',
      })

      if(nextPageToken) {
        params.set('pageToken', nextPageToken)
      }

      const response = await (
        this.makeRequest({
          endpoint: '/playlistItems',
          body: params.toString(),
        })
      ) as {
        items: Array<{
          snippet: {
            title: string
            videoOwnerChannelTitle: string
          }
          contentDetails: {
            videoId: string
          }
        }>
        nextPageToken?: string
      }

      console.debug(`${response.items.length} items fetched.`)

      console.debug({ 'First Item': response.items[0] })

      const vids = await (
        this.makeRequest({
          endpoint: '/videos',
          body: new URLSearchParams({
            id: response.items[0].contentDetails.videoId,
            part: 'snippet',
          }),
        })
      )
      console.dir({ vids }, { depth: null })


      // for(const item of response.items) {
      //   const { title, videoOwnerChannelTitle } = item.snippet
      //   if(videoOwnerChannelTitle) {
      //     titles.push(`${title} - ${videoOwnerChannelTitle}`)
      //   } else {
      //     titles.push(title)
      //   }
      // }

      ;({ nextPageToken } = response)
    } while(nextPageToken)

    await Deno.writeTextFile(outputFile, titles.join('\n'))
    console.log(`Exported ${titles.length} titles to ${outputFile}`)
  }
}

if(import.meta.main) {
  const [playlistUrl, outputFile] = Deno.args

  if(!playlistUrl || !outputFile) {
    throw new Error(
      'Usage: deno run export_playlist.ts <playlist url> <output file>'
    )
  }

  const env = await load()

  if(!env.CLIENT_ID) {
    throw new Error('`$CLIENT_ID` not set.')
  }
  if(!env.CLIENT_SECRET) {
    throw new Error('`$CLIENT_SECRET` not set.')
  }


  const exporter = new YouTubePlaylistExporter({
    clientId: env['CLIENT_ID'],
    clientSecret: env['CLIENT_SECRET'],
    redirectUri: 'http://localhost:8000/oauth2callback',
  })

  try {
    await exporter.exportPlaylist(playlistUrl, outputFile)
  } catch(err) {
    console.error('Error:', err)
    Deno.exit(1)
  }
}