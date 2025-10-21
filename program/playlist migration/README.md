# Spotify Playlist Exporter

A TypeScript program to export Spotify playlists to JSON and CSV formats using the Spotify Web API.

## Features

- Export any public Spotify playlist
- Handles pagination automatically for large playlists
- Exports to both JSON and CSV formats
- Includes track details: name, artists, album, duration, URI, and Spotify URL
- Uses native Node.js modules (no external API libraries required)

## Prerequisites

- Node.js (v16 or higher)
- A Spotify Developer account
- Spotify API credentials (Client ID and Client Secret)

## Setup

### 1. Get Spotify API Credentials

1. Go to [Spotify Developer Dashboard](https://developer.spotify.com/dashboard)
2. Log in with your Spotify account
3. Click "Create an App"
4. Fill in the app name and description
5. Accept the terms and create the app
6. You'll see your **Client ID** and **Client Secret** on the app dashboard

### 2. Install Dependencies

```bash
npm install
```

### 3. Set Environment Variables

Create a `.env` file or set environment variables:

```bash
export SPOTIFY_CLIENT_ID="your_client_id_here"
export SPOTIFY_CLIENT_SECRET="your_client_secret_here"
```

Or on Windows:
```cmd
set SPOTIFY_CLIENT_ID=your_client_id_here
set SPOTIFY_CLIENT_SECRET=your_client_secret_here
```

## Usage

### Find a Playlist ID

From a Spotify playlist URL like:
```
https://open.spotify.com/playlist/37i9dQZF1DXcBWIGoYBM5M
```

The playlist ID is: `37i9dQZF1DXcBWIGoYBM5M`

### Export a Playlist

**Option 1: Using environment variable**
```bash
export SPOTIFY_PLAYLIST_ID="37i9dQZF1DXcBWIGoYBM5M"
npm start
```

**Option 2: Pass as command line argument**
```bash
npm start 37i9dQZF1DXcBWIGoYBM5M
```

### Output Files

The program creates two files:
- `playlist_<PLAYLIST_ID>.json` - Full playlist data in JSON format
- `playlist_<PLAYLIST_ID>.csv` - Track list in CSV format for spreadsheets

## JSON Output Format

```json
{
  "name": "Playlist Name",
  "description": "Playlist description",
  "total_tracks": 50,
  "tracks": [
    {
      "name": "Track Name",
      "artists": ["Artist 1", "Artist 2"],
      "album": "Album Name",
      "duration_ms": 234567,
      "uri": "spotify:track:xxxxx",
      "external_url": "https://open.spotify.com/track/xxxxx"
    }
  ]
}
```

## CSV Output Format

```
Track Name,Artists,Album,Duration (ms),Spotify URI,URL
"Song Title","Artist Name","Album Name",234567,spotify:track:xxxxx,https://...
```

## Using as a Library

You can also import and use the exporter in your own TypeScript code:

```typescript
import { SpotifyPlaylistExporter } from './spotify-export';

const exporter = new SpotifyPlaylistExporter(clientId, clientSecret);
await exporter.authenticate();

// Export to JSON
await exporter.exportToJSON(playlistId, 'output.json');

// Export to CSV
await exporter.exportToCSV(playlistId, 'output.csv');

// Or get playlist data directly
const playlist = await exporter.exportPlaylist(playlistId);
console.log(playlist);
```

## Limitations

- Only works with **public** playlists (Client Credentials flow doesn't support user-specific data)
- For private playlists, you'd need to implement OAuth 2.0 Authorization Code flow
- Rate limits apply per Spotify's API terms

## Troubleshooting

**Authentication Error**: Double-check your Client ID and Client Secret are correct

**Playlist Not Found**: Ensure the playlist is public and the ID is correct

**Rate Limiting**: If exporting many playlists, add delays between requests

## License

MIT