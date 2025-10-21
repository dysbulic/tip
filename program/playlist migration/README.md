# Music Playlist Tools

A collection of TypeScript tools for exporting and importing music playlists between Spotify and YouTube Music.

## Overview

This project provides command-line tools to:

- **Export** playlists from Spotify to JSON/CSV
- **Export** playlists from YouTube Music to JSON/CSV (including private playlists)
- **Import** playlists from CSV to YouTube Music
- **Transfer** playlists between services (e.g., Spotify → YouTube Music)

All tools use native Node.js modules with no external API libraries, support OAuth 2.0 authentication where needed, and follow a consistent command-line interface.

## Quick Start

### Installation

```bash
npm install
```

### Configuration

1. Copy the environment template:

```bash
cp .env.example .env
```

2. Add your API credentials to `.env` (see [Setup](#setup) below)

### Basic Usage

**Export a Spotify playlist:**

```bash
npm run spotify -- YOUR_PLAYLIST_ID
```

**Export a YouTube Music playlist:**

```bash
npm run youtube -- YOUR_PLAYLIST_ID
```

**Import tracks from CSV to YouTube Music:**

```bash
npm run youtube-import -- tracks.csv -p "My Playlist"
```

**Transfer Spotify playlist to YouTube Music:**

```bash
# Step 1: Export from Spotify
npm run spotify -- SPOTIFY_PLAYLIST_ID

# Step 2: Import to YouTube Music
npm run youtube-import -- playlist_SPOTIFY_PLAYLIST_ID.csv -p "From Spotify"
```

## Tools

### 1. Spotify Playlist Exporter

Export Spotify playlists to JSON and CSV formats.

**Features:**

- Exports track name, artists, album, duration, Spotify URI, and URL
- Handles pagination for large playlists
- Uses Spotify Web API with Client Credentials flow

**Usage:**

```bash
npm run spotify -- PLAYLIST_ID [options]

Options:
  -f, --format   Output format: json, csv, or both (default: both)
  -o, --output   Custom output filename (without extension)
```

**Examples:**

```bash
# Export to both JSON and CSV
npm run spotify -- 37i9dQZF1DXcBWIGoYBM5M

# Export only JSON
npm run spotify -- 37i9dQZF1DXcBWIGoYBM5M --format json

# Custom filename
npm run spotify -- 37i9dQZF1DXcBWIGoYBM5M -o my-playlist
```

📄 [Full Documentation](README-spotify.md)

### 2. YouTube Music Playlist Exporter

Export YouTube Music playlists including private playlists and videos.

**Features:**

- Exports track title, artist, channel, duration, video ID, URL, and thumbnail
- Handles private playlists and videos using OAuth 2.0
- Attempts to extract artist information from titles and descriptions
- Handles pagination for large playlists
- Automatic token refresh

**Usage:**

```bash
npm run youtube -- PLAYLIST_ID [options]

Options:
  -f, --format   Output format: json, csv, or both (default: both)
  -o, --output   Custom output filename (without extension)
```

**Examples:**

```bash
# Export to both JSON and CSV
npm run youtube -- PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf

# Export only CSV
npm run youtube -- PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf --format csv

# Custom filename
npm run youtube -- PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf -o my-favorites
```

**First Run:**
The first time you run this, you'll be prompted to authenticate via your browser. Your tokens will be saved for future use.

📄 [Full Documentation](README-youtube.md)

### 3. YouTube Music Playlist Importer

Import tracks from CSV files into new YouTube Music playlists.

**Features:**

- Reads CSV files with "Track Name" and "Artist" columns
- Supports multiple CSV files in one command
- Automatically searches YouTube Music for each track
- Removes duplicate tracks across files
- Creates new playlists automatically
- Logs failed tracks to a file for review
- Automatic rate limit handling with retry logic

**Usage:**

```bash
npm run youtube-import -- CSV_FILE(S) -p "Playlist Name"

Required:
  -p, --playlist   Name for the new playlist

Multiple files:
  You can specify multiple CSV files or use wildcards
```

**Examples:**

```bash
# Import single CSV
npm run youtube-import -- tracks.csv -p "My Playlist"

# Import multiple CSVs
npm run youtube-import -- rock.csv pop.csv jazz.csv -p "Music Collection"

# Import all CSVs in directory
npm run youtube-import -- *.csv -p "All Music"

# Transfer Spotify playlist to YouTube Music
npm run spotify -- SPOTIFY_PLAYLIST_ID
npm run youtube-import -- playlist_*.csv -p "From Spotify"
```

**What Happens:**

1. Reads all CSV files and combines tracks
2. Removes duplicates
3. Creates a new private playlist
4. Searches YouTube Music for each track
5. Adds found videos to the playlist
6. Logs any failed tracks to a timestamped file

**Rate Limiting:**
The script automatically handles YouTube API rate limits by waiting and retrying with exponential backoff (60s, 120s, 240s). Large imports may take time.

📄 [Full Documentation](README-youtube-import.md)

## Setup

### Spotify API Credentials

1. Go to [Spotify Developer Dashboard](https://developer.spotify.com/dashboard)
2. Log in with your Spotify account
3. Click "Create an App"
4. Fill in app name and description, accept terms
5. Copy your **Client ID** and **Client Secret**
6. Add to `.env`:

```env
SPOTIFY_CLIENT_ID=your_client_id_here
SPOTIFY_CLIENT_SECRET=your_client_secret_here
```

### YouTube OAuth 2.0 Credentials

1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Create a new project or select existing one
3. Enable **YouTube Data API v3**:
   - Navigate to "APIs & Services" > "Library"
   - Search for "YouTube Data API v3"
   - Click "Enable"
4. Create OAuth 2.0 credentials:
   - Go to "APIs & Services" > "Credentials"
   - Click "Create Credentials" > "OAuth client ID"
   - Configure OAuth consent screen if prompted:
     - User Type: "External"
     - Add your email as a test user
   - Application type: **"Desktop app"** (important!)
   - Click "Create"
   - Copy your **Client ID** and **Client Secret**
5. Add to `.env`:

```env
YOUTUBE_OAUTH_CLIENT_ID=your_client_id.apps.googleusercontent.com
YOUTUBE_OAUTH_CLIENT_SECRET=your_client_secret
```

**Note:** For the YouTube importer, you need the full `youtube` scope (not just `youtube.readonly`) to create and modify playlists.

## Common Workflows

### Backup Spotify Playlist to YouTube Music

```bash
# Export from Spotify
npm run spotify -- YOUR_SPOTIFY_PLAYLIST_ID

# Import to YouTube Music
npm run youtube-import -- playlist_YOUR_SPOTIFY_PLAYLIST_ID.csv -p "Backup"
```

### Combine Multiple Playlists

```bash
# Export multiple playlists
npm run spotify -- PLAYLIST_1
npm run spotify -- PLAYLIST_2
npm run spotify -- PLAYLIST_3

# Combine into one YouTube Music playlist (removes duplicates)
npm run youtube-import -- playlist_*.csv -p "Combined Collection"
```

### Backup All YouTube Music Playlists

```bash
# Export each playlist
npm run youtube -- PLAYLIST_1
npm run youtube -- PLAYLIST_2
npm run youtube -- PLAYLIST_3

# Files are saved as playlist_<ID>.json and playlist_<ID>.csv
```

### Review Failed Imports

After importing, check the generated log file:

```bash
cat failed_tracks_*.txt
```

This shows which tracks couldn't be found and why.

## Output Formats

### JSON Format

Structured data with complete playlist and track information:

```json
{
  "name": "Playlist Name",
  "description": "Playlist description",
  "total_tracks": 50,
  "tracks": [
    {
      "title": "Track Name",
      "artist": "Artist Name",
      "album": "Album Name",
      "duration": "3:45",
      "uri": "spotify:track:xxxxx",
      "url": "https://open.spotify.com/track/xxxxx"
    }
  ]
}
```

### CSV Format

Simple format for spreadsheets and data processing:

```csv
Track Name,Artist,Album,Duration,URI,URL
"Song Title","Artist Name","Album Name","3:45","spotify:track:xxxxx","https://..."
```

## Features

**All Tools:**

- ✅ Clean TypeScript with no semicolons
- ✅ Native Node.js modules only
- ✅ Command-line interface with yargs
- ✅ Environment variables via dotenv
- ✅ Automatic pagination for large playlists
- ✅ Custom output filenames

**Exporters:**

- ✅ Export to JSON and CSV
- ✅ Complete track metadata

**YouTube Tools:**

- ✅ OAuth 2.0 authentication with token persistence
- ✅ Access private playlists and videos
- ✅ Automatic token refresh

**Importer:**

- ✅ Multi-file support with duplicate removal
- ✅ Automatic YouTube search
- ✅ Failed track logging
- ✅ Rate limit handling with automatic retry

## Troubleshooting

### Authentication Issues

**Spotify "Invalid credentials":**

- Verify your Client ID and Client Secret in `.env`
- Ensure you copied them correctly from the Spotify Dashboard

**YouTube "redirect_uri_mismatch":**

- Make sure you created a "Desktop app" OAuth client, not "Web application"
- Desktop apps allow `http://localhost:8080` as redirect URI

**YouTube "Access blocked":**

- Verify YouTube Data API v3 is enabled in your project
- Add yourself as a test user in OAuth consent screen
- Grant all requested permissions during authentication

### Rate Limiting

**YouTube import is slow or pausing:**

- This is normal - the script handles rate limits automatically
- You'll see "Rate limit detected. Waiting X seconds..." messages
- Large imports (200+ tracks) may take 30+ minutes

**"Quota exceeded":**

- You've hit the daily API quota (10,000 units)
- Wait until the next day or request quota increase
- Consider importing in smaller batches

### Import Issues

**"Could not find Track Name column":**

- Ensure your CSV has a column with "Track" and "Name" in the header
- Column names are case-insensitive

**Many tracks "Not found":**

- Check track names and artists are correct in CSV
- Some tracks may not be available on YouTube
- Try searching manually on YouTube to verify
- Check the failed tracks log for details

### Token Issues

**YouTube tokens expired:**

```bash
rm .youtube-tokens.json
npm run youtube -- YOUR_PLAYLIST_ID
```

Re-authenticate when prompted.

**Need different YouTube account:**
Delete `.youtube-tokens.json` and run any YouTube command to re-authenticate with a different account.

## API Quotas

### Spotify

- No strict daily quota
- Rate limited per endpoint
- Handled automatically by the API

### YouTube Data API v3

- Default quota: 10,000 units per day
- Each export: ~1-3 units per video
- Each import: ~100 units per track (search + insert)
- Creating playlist: 50 units

**Example:** Importing 50 tracks uses ~5,050 units (about half daily quota)

**Increase quota:** Request in Google Cloud Console if you need more.

## Technical Details

- **Language:** TypeScript (with no semicolons)
- **Runtime:** Node.js v16+
- **Authentication:**
  - Spotify: Client Credentials flow
  - YouTube: OAuth 2.0 Authorization Code flow
- **APIs:**
  - Spotify Web API
  - YouTube Data API v3
- **Dependencies:** dotenv, yargs (plus type definitions)
- **Output:** JSON and CSV files

## License

CC0-1.0 - Public Domain

This project is dedicated to the public domain. You can use, modify, and distribute it freely without any restrictions or attribution requirements.

## Links

- [Spotify Web API Documentation](https://developer.spotify.com/documentation/web-api/)
- [YouTube Data API v3 Documentation](https://developers.google.com/youtube/v3)
- [Google Cloud Console](https://console.cloud.google.com)
- [Spotify Developer Dashboard](https://developer.spotify.com/dashboard)

## Support

For detailed documentation on each tool, see:

- [Spotify Exporter Documentation](README.Spotify.md)
- [YouTube Music Exporter Documentation](README.YouTube.md)
- [YouTube Music Importer Documentation](README.import.md)

For issues or questions, refer to the troubleshooting sections in the documentation above.
