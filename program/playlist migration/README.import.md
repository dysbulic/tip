# YouTube Music Playlist Importer

A TypeScript program to import tracks from a CSV file into a new YouTube Music playlist by automatically searching for and adding matching videos.

## Features

- Reads tracks from CSV file(s) (Track Name and Artist columns)
- Supports importing from multiple CSV files at once
- Automatically removes duplicate tracks across files
- Automatically searches YouTube Music for each track
- Creates a new private playlist
- Adds found videos to the playlist
- **Logs failed tracks** to a timestamped file for review
- OAuth 2.0 authentication with token persistence
- Progress tracking with detailed logging
- Handles rate limiting with delays between requests

## How It Works

1. **Reads CSV**: Parses your CSV file to extract track names and artists
2. **Searches YouTube**: For each track, searches YouTube using "Artist Track Name"
3. **Finds Best Match**: Uses the first search result (usually most relevant)
4. **Creates Playlist**: Creates a new private playlist with your chosen name
5. **Adds Videos**: Adds each found video to the playlist

## Prerequisites

- Node.js (v16 or higher)
- YouTube OAuth 2.0 credentials (same as the exporter)
- CSV file with "Track Name" and "Artist" columns

## Setup

Same as the YouTube Music exporter - you need OAuth 2.0 credentials with **write access** (not just read-only).

### OAuth Scope Difference

**Important:** This script requires the full `https://www.googleapis.com/auth/youtube` scope (not just `youtube.readonly`) because it needs to:
- Create playlists
- Add videos to playlists

If you previously authenticated with read-only scope, you'll need to re-authenticate:
```bash
rm .youtube-tokens.json
npm run youtube-import -- tracks.csv -p "My Playlist"
```

## CSV Format

Your CSV file must have columns named "Track Name" and "Artist" (case-insensitive, can include other words like "Artists").

**Example CSV:**
```csv
Track Name,Artist,Album,Duration
"Bohemian Rhapsody","Queen","A Night at the Opera",355000
"Stairway to Heaven","Led Zeppelin","Led Zeppelin IV",482000
"Hotel California","Eagles","Hotel California",391000
```

The script will ignore extra columns and only use Track Name and Artist.

## Usage

### Basic Import (Single CSV)

```bash
npm run youtube-import -- tracks.csv -p "My Playlist Name"
```

### Multiple CSV Files

You can import tracks from multiple CSV files into a single playlist:

```bash
npm run youtube-import -- tracks1.csv tracks2.csv tracks3.csv -p "Combined Playlist"
```

The script will:
1. Read all CSV files
2. Combine all tracks
3. Remove duplicates (same artist + track name)
4. Create a single playlist with all unique tracks

### Using Wildcards

Import all CSV files in a directory:

```bash
npm run youtube-import -- *.csv -p "All My Music"
```

Or import specific pattern:

```bash
npm run youtube-import -- playlist_*.csv -p "All Playlists"
```

### Examples

**Import single CSV file:**
```bash
npm run youtube-import -- exported-playlist.csv --playlist "Imported Favorites"
```

**Import multiple CSV files:**
```bash
npm run youtube-import -- rock.csv pop.csv jazz.csv -p "Music Collection"
```

**Import all CSV files in current directory:**
```bash
npm run youtube-import -- *.csv -p "Everything"
```

**Import Spotify export:**
```bash
# First export from Spotify
npm run spotify -- 37i9dQZF1DXcBWIGoYBM5M

# Then import to YouTube
npm run youtube-import -- playlist_37i9dQZF1DXcBWIGoYBM5M.csv -p "Spotify Playlist"
```

**Combine multiple Spotify playlists:**
```bash
# Export multiple Spotify playlists
npm run spotify -- PLAYLIST_ID_1
npm run spotify -- PLAYLIST_ID_2
npm run spotify -- PLAYLIST_ID_3

# Combine into one YouTube playlist
npm run youtube-import -- playlist_*.csv -p "All Spotify Playlists"
```

### Command Line Options

**Positional Arguments:**

| Argument | Type | Description |
|----------|------|-------------|
| `csv` | string(s) | Path(s) to CSV file(s) - can specify multiple files (required) |

**Options:**

| Option | Alias | Type | Description |
|--------|-------|------|-------------|
| `--playlist` | `-p` | string | Name for the new playlist (required) |
| `--help` | `-h` | - | Show help |
| `--version` | `-v` | - | Show version |

## Output

The script will show progress for each track:

**Single CSV:**
```
Reading CSV file: tracks.csv
Found 50 tracks in CSV

Creating playlist...
Playlist created: PLxxxxxxxxxxxxxx
View at: https://www.youtube.com/playlist?list=PLxxxxxxxxxxxxxx

[1/50] Searching: Queen - Bohemian Rhapsody
  Found: Queen - Bohemian Rhapsody (Official Video) (Queen Official)
  ✓ Added to playlist

[2/50] Searching: Led Zeppelin - Stairway to Heaven
  Found: Led Zeppelin - Stairway to Heaven (Official Audio) (Led Zeppelin)
  ✓ Added to playlist

...

Import complete!
  Added: 47
  Not found: 3
  Total: 50

Playlist: https://www.youtube.com/playlist?list=PLxxxxxxxxxxxxxx

Failed tracks log written to: failed_tracks_my_playlist_2025-10-21T15-30-45.txt
```

**Multiple CSVs:**
```
Reading 3 CSV file(s)...

Reading: rock.csv
  Found 30 tracks
Reading: pop.csv
  Found 25 tracks
Reading: jazz.csv
  Found 20 tracks

Total tracks: 75
Duplicates removed: 5
Unique tracks: 70

Creating playlist...
Playlist created: PLxxxxxxxxxxxxxx
View at: https://www.youtube.com/playlist?list=PLxxxxxxxxxxxxxx

[1/70] Searching: Queen - Bohemian Rhapsody
  Found: Queen - Bohemian Rhapsody (Official Video) (Queen Official)
  ✓ Added to playlist

...

Import complete!
  Added: 67
  Not found: 3
  Total: 70

Playlist: https://www.youtube.com/playlist?list=PLxxxxxxxxxxxxxx

Failed tracks log written to: failed_tracks_combined_playlist_2025-10-21T15-30-45.txt
```

## Failed Tracks Log

When tracks cannot be found or added to the playlist, they are automatically logged to a file for review. The log file includes:

- Playlist name and import date
- Artist and track name for each failed track
- Reason for failure (not found or failed to add)

**Log file format:**
```
Failed Tracks Log
Playlist: My Playlist
Date: 2025-10-21T15:30:45.123Z
Total Failed: 3

================================================================================

Artist: The Beatles
Track: Yesterday (Remastered 2009)
Reason: Not found on YouTube Music

Artist: Pink Floyd
Track: Comfortably Numb - Live
Reason: Not found on YouTube Music

Artist: Queen
Track: Bohemian Rhapsody
Reason: Failed to add to playlist: Video unavailable
```

**Log file naming:**
- Format: `failed_tracks_<playlist_name>_<timestamp>.txt`
- Example: `failed_tracks_my_playlist_2025-10-21T15-30-45.txt`
- Location: Current directory

You can use this log to:
- Manually search for tracks that couldn't be found
- Try different search terms or spellings
- Identify patterns in failed imports (e.g., specific artists or live versions)
- Re-import only the failed tracks after fixing issues

## Authentication

First run will require OAuth authentication (same as the exporter):

1. Script prints an authorization URL
2. Open URL in your browser
3. Log in with your Google account
4. Grant permissions (including playlist management)
5. Tokens are saved to `.youtube-tokens.json`

**Note:** If you previously used the exporter with read-only scope, delete `.youtube-tokens.json` to re-authenticate with write permissions.

## Duplicate Handling

When importing from multiple CSV files, the script automatically removes duplicates based on:
- Artist name (case-insensitive)
- Track name (case-insensitive)

If the same track appears in multiple CSV files, it will only be added once to the playlist.

**Example:**
- `rock.csv` contains: "Queen - Bohemian Rhapsody"
- `pop.csv` contains: "Queen - Bohemian Rhapsody"
- Result: Track is only added once to the playlist

## Limitations

- **Search Accuracy**: The script uses YouTube's search API, which may not always find the exact track you want (especially for remixes, live versions, covers)
- **First Result**: Always picks the first search result - may not be the version you prefer
- **Rate Limits**: Subject to YouTube Data API quota (10,000 units/day default)
- **Private Playlists**: Created playlists are private by default (you can make them public on YouTube)
- **No Duplicate Detection**: If you run the import twice, it will add duplicates to the playlist

## API Quota Usage

Each import uses approximately:
- 100 units per track (search + insert)
- Creating playlist: 50 units

Example: Importing 50 tracks uses about 5,050 units (about half your daily quota).

## Workflow: Spotify to YouTube Music

You can use this to transfer playlists from Spotify to YouTube Music:

**Single playlist:**
```bash
# Step 1: Export from Spotify
npm run spotify -- YOUR_SPOTIFY_PLAYLIST_ID

# Step 2: Import to YouTube Music
npm run youtube-import -- playlist_YOUR_SPOTIFY_PLAYLIST_ID.csv -p "From Spotify"
```

**Multiple playlists combined:**
```bash
# Step 1: Export multiple Spotify playlists
npm run spotify -- PLAYLIST_ID_1
npm run spotify -- PLAYLIST_ID_2
npm run spotify -- PLAYLIST_ID_3

# Step 2: Combine all into one YouTube Music playlist
npm run youtube-import -- playlist_*.csv -p "Spotify Collection"
```

This is useful for:
- Combining themed playlists into one master playlist
- Merging personal and shared playlists
- Creating a backup of all your Spotify playlists in YouTube Music

## Troubleshooting

**"Could not find Track Name column"**
- Make sure your CSV has a column with "Track" and "Name" in the header
- Check that the CSV is properly formatted with commas

**"Could not find Artist column"**
- Make sure your CSV has a column with "Artist" in the header

**"Not found" for many tracks**
- Verify the track names and artists are spelled correctly
- Some tracks may not be available on YouTube
- Try searching manually on YouTube to see if they exist

**"Quota exceeded"**
- You've hit your daily API quota limit
- Wait until the next day or request a quota increase

**"Insufficient permissions"**
- Delete `.youtube-tokens.json` and re-authenticate
- Make sure you grant all requested permissions

## Tips

- Review the CSV before importing to ensure track names and artists are accurate
- Start with a small test playlist (5-10 tracks) before importing large playlists
- The playlist is created as private - you can change visibility on YouTube afterwards
- Search results are usually accurate for popular songs, but may struggle with:
  - Obscure tracks
  - Songs with special characters
  - Multiple versions (live, acoustic, remix)

## License

CC0-1.0