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

### 3. Configure Environment Variables

Copy the example environment file and add your credentials:

```bash
cp .env.example .env
```

Then edit `.env` and add your Spotify credentials:

```
SPOTIFY_CLIENT_ID=your_client_id_here
SPOTIFY_CLIENT_SECRET=your_client_secret_here
```

## Usage

### Find a Playlist ID

From a Spotify playlist URL like:
```
https://open.spotify.com/playlist/37i9dQZF1DXcBWIGoYBM5M
```

The playlist ID is: `37i9dQZF1DXcBWIGoYBM5M`

### Export a Playlist

**Using npm script:**
```bash
npm run spotify -- 37i9dQZF1DXcBWIGoYBM5M
```

**Alternative - using npx ts-node directly:**
```bash
npx ts-node spotify-export.ts 37i9dQZF1DXcBWIGoYBM5M
```

**Export only JSON:**
```bash
npm run spotify -- 37i9dQZF1DXcBWIGoYBM5M --format json
```

**Export only CSV:**
```bash
npm run spotify -- 37i9dQZF1DXcBWIGoYBM5M --format csv
```

**Custom output filename:**
```bash
npm run spotify -- 37i9dQZF1DXcBWIGoYBM5M -o my-favorite-songs
```

**Show help:**
```bash
npm run spotify -- --help
```

### Command Line Options

**Positional Arguments:**

| Argument | Type | Description |
|----------|------|-------------|
| `playlist` | string | Spotify playlist ID to export (required) |

**Options:**

| Option | Alias | Type | Default | Description |
|--------|-------|------|---------|-------------|
| `--format` | `-f` | string | `both` | Output format: `json`, `csv`, or `both` |
| `--output` | `-o` | string | `playlist_{id}` | Output filename (without extension) |
| `--help` | `-h` | - | - | Show help |
| `--version` | `-v` | - | - | Show version |

### Output Files

The program creates files based on the format option:
- `{output}.json` - Full playlist data in JSON format (if format is `json` or `both`)
- `{output}.csv` - Track list in CSV format for spreadsheets (if format is `csv` or `both`)

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

**Authentication Error**: Double-check your credentials in the `.env` file are correct

**Playlist Not Found**: Ensure the playlist is public and the ID is correct

**Rate Limiting**: If exporting many playlists, add delays between requests

**"Cannot find module 'yargs'"**: Run `npm install` to install all dependencies

## License

CC0-1.0