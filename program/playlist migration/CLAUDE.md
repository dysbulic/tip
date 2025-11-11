# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a TypeScript-based toolkit for migrating music playlists between Spotify and YouTube Music. The project consists of three main tools that work together to export, transfer, and import playlists across platforms.

**Runtime**: This project runs using Deno, not Node.js/npm.

## Core Commands

### Running the Tools with Deno

```bash
# Export Spotify playlist to JSON/CSV
deno run --allow-net --allow-read --allow-write --allow-env spotify-export.ts PLAYLIST_ID [--format json|csv|both] [-o output-name]

# Export YouTube Music playlist to JSON/CSV
deno run --allow-net --allow-read --allow-write --allow-env youtube-export.ts PLAYLIST_ID [--format json|csv|both] [-o output-name]

# Import CSV tracks to YouTube Music playlist
deno run --allow-net --allow-read --allow-write --allow-env youtube-import.ts file.csv [file2.csv ...] -p "Playlist Name"
```

### Required Deno Permissions

All scripts require these permissions:
- `--allow-net` - API requests to Spotify/YouTube and OAuth callback server
- `--allow-read` - Read `.env`, `.youtube-tokens.json`, and CSV files
- `--allow-write` - Write JSON/CSV exports and token files
- `--allow-env` - Access environment variables for API credentials

### Common Workflows

```bash
# Transfer Spotify playlist to YouTube Music
deno run --allow-net --allow-read --allow-write --allow-env spotify-export.ts SPOTIFY_ID
deno run --allow-net --allow-read --allow-write --allow-env youtube-import.ts playlist_SPOTIFY_ID.csv -p "Imported Playlist"

# Combine multiple playlists (removes duplicates)
deno run --allow-net --allow-read --allow-write --allow-env youtube-import.ts playlist_*.csv -p "Combined Collection"
```

## Architecture

### Code Structure

The project follows a class-based architecture with three main exporters/importers:

1. **`spotify-export.ts`** - `SpotifyPlaylistExporter` class
   - Uses Spotify Web API with Client Credentials flow (no user auth needed)
   - Exports public playlists only
   - Handles pagination automatically

2. **`youtube-export.ts`** - `YouTubeMusicOAuthExporter` class
   - Uses OAuth 2.0 with token persistence in `.youtube-tokens.json`
   - Can access private playlists and videos
   - Includes artist extraction logic from video titles/descriptions
   - Skips private/deleted videos automatically

3. **`youtube-import.ts`** - `YouTubeMusicPlaylistImporter` class
   - Reads CSV files with "Track Name" and "Artist" columns
   - Supports multi-file import with duplicate removal
   - Implements rate limiting with exponential backoff (60s, 120s, 240s)
   - Creates failed tracks log files with timestamps

### Authentication Patterns

**Spotify**: Simple Client Credentials flow - stores token in memory only.

**YouTube**: OAuth 2.0 flow with persistence:
- Tokens stored in `.youtube-tokens.json` (gitignored)
- Automatic token refresh when expired
- Local callback server on port 8080 for authorization
- **Important**: Exporter uses `youtube.readonly` scope, importer uses full `youtube` scope
- If switching between tools, may need to delete token file and re-authenticate

### Shared Patterns

All three tools follow the same structure:
1. Class-based with constructor taking OAuth credentials
2. `authenticate()` method for API setup
3. Export/import methods that handle pagination
4. CLI interface using yargs with consistent argument patterns
5. Support for both JSON and CSV output formats
6. Native Node.js modules only (fs, https, http, url) - compatible with Deno

### CSV Format Requirements

The importer expects CSV files with these column headers (case-insensitive):
- A column containing both "Track" and "Name" (e.g., "Track Name")
- A column containing "Artist" (e.g., "Artist", "Artists")

Both exporters produce compatible CSV files automatically.

### Rate Limiting Strategy

The YouTube importer implements sophisticated rate limiting:
- 500ms delay between requests during normal operation
- Detects rate limit errors via multiple indicators (429, quota, rateLimitExceeded)
- Automatic retry with exponential backoff (60s → 120s → 240s)
- Max 3 retries before giving up on a track
- All rate limit logic is in `youtube-import.ts` lines 242-289

### Error Handling & Logging

**Failed Track Logging**: When imports fail, a timestamped log file is generated:
- Format: `failed_tracks_<sanitized_playlist_name>_<timestamp>.txt`
- Includes artist, track name, and failure reason for each failed track
- Generated in project root directory

**Duplicate Removal**: Multi-file imports use case-insensitive matching:
- Key format: `${artist.toLowerCase()}|${trackName.toLowerCase()}`
- Implemented in `removeDuplicates()` method (youtube-import.ts:632-645)

## Environment Configuration

Required environment variables in `.env`:

```env
# Spotify (Client Credentials from developer dashboard)
SPOTIFY_CLIENT_ID=your_client_id
SPOTIFY_CLIENT_SECRET=your_client_secret

# YouTube (OAuth 2.0 Desktop App credentials from Google Cloud Console)
YOUTUBE_OAUTH_CLIENT_ID=your_client_id.apps.googleusercontent.com
YOUTUBE_OAUTH_CLIENT_SECRET=your_client_secret
```

**Critical**: YouTube credentials MUST be "Desktop app" type, not "Web application", to allow `http://localhost:8080` redirect URI.

## Code Style

- TypeScript with no semicolons
- ES2020 target
- Strict mode enabled
- Native Node.js modules preferred (`https`, `http`, `fs`, `url`)
- Promise-based async patterns
- Class methods are private unless exported for library use

## API Quotas & Limits

**Spotify**: No strict daily quota, rate limits handled automatically by API.

**YouTube Data API v3**:
- Default quota: 10,000 units/day
- Search: ~100 units per query
- Playlist insert: ~50 units per video
- Playlist creation: 50 units
- Large imports (100+ tracks) can exhaust daily quota

## File Outputs

All tools output to current directory with these patterns:
- `playlist_<PLAYLIST_ID>.json` - Full playlist metadata + tracks
- `playlist_<PLAYLIST_ID>.csv` - Track list for imports/spreadsheets
- `failed_tracks_<name>_<timestamp>.txt` - Import failure log

## Token Management

YouTube tokens are stored in `.youtube-tokens.json` containing:
- `access_token` - expires in ~1 hour
- `refresh_token` - long-lived, used to refresh access token
- Token refresh is automatic and transparent

To re-authenticate with different account or fix token issues:
```bash
rm .youtube-tokens.json
deno run --allow-net --allow-read --allow-write --allow-env youtube-export.ts YOUR_PLAYLIST_ID
```

## Development Notes

- The codebase uses `require.main === module` pattern to allow files to be run directly or imported as libraries
- Uses Node.js-style imports (`import * as fs from 'fs'`) which Deno supports via its Node compatibility layer
- Dependencies (dotenv, yargs) are imported from npm via Deno's node_modules resolution
- When modifying authentication logic, be aware that YouTube exporter and importer use different OAuth scopes and will require separate token files if this changes
