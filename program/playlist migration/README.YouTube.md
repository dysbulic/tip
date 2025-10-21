# YouTube Music Playlist Exporter

A TypeScript program to export YouTube Music playlists (including private playlists and videos) using OAuth 2.0 authentication.

## Features

- Export playlists from your own YouTube account (private or public)
- Access private and unlisted videos in playlists
- OAuth 2.0 authentication with token persistence
- Automatic token refresh
- Handles pagination for large playlists
- Exports to both JSON and CSV formats
- Detailed logging showing what videos are skipped

## Setup

### 1. Create OAuth 2.0 Credentials

1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Create a new project or select an existing one
3. Enable the **YouTube Data API v3**:
   - Go to "APIs & Services" > "Library"
   - Search for "YouTube Data API v3"
   - Click "Enable"
4. Create OAuth 2.0 credentials:
   - Go to "APIs & Services" > "Credentials"
   - Click "Create Credentials" > "OAuth client ID"
   - If prompted, configure the OAuth consent screen:
     - User Type: "External"
     - Add your email as a test user
     - Scopes: Add `https://www.googleapis.com/auth/youtube.readonly`
   - Application type: **"Desktop app"**
   - Name: Whatever you want (e.g., "YouTube Playlist Exporter")
   - Click "Create"
   - **Download the JSON** or copy the Client ID and Client Secret

### 2. Configure Environment Variables

Add your OAuth credentials to `.env`:

```env
YOUTUBE_OAUTH_CLIENT_ID=your_client_id_here.apps.googleusercontent.com
YOUTUBE_OAUTH_CLIENT_SECRET=your_client_secret_here
```

### 3. First Run - Authentication

The first time you run the exporter, it will:
1. Print an authorization URL
2. Open a browser window (or prompt you to open the URL)
3. Ask you to log in with your Google account
4. Request permission to view your YouTube data
5. Redirect to `localhost:8080` with an authorization code
6. Exchange the code for access and refresh tokens
7. Save the tokens to `.youtube-tokens.json`

**Important:** The authorization URL will only work if you:
- Added yourself as a test user in the OAuth consent screen
- Used "Desktop app" as the application type

## Usage

### First Time (Requires Authentication)

```bash
npm run youtube -- PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf
```

This will:
1. Print: "Please visit this URL to authorize the application: https://..."
2. Open the URL in your browser
3. Ask you to log in and grant permission
4. Save your tokens for future use

### Subsequent Runs

After the first authentication, tokens are saved in `.youtube-tokens.json`. The exporter will:
- Automatically reuse the saved access token
- Automatically refresh the token if expired
- Only prompt for re-authentication if the refresh token is invalid

```bash
npm run youtube -- PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf
```

### Export Options

**Export only JSON:**
```bash
npm run youtube -- PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf --format json
```

**Export only CSV:**
```bash
npm run youtube -- PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf --format csv
```

**Custom output filename:**
```bash
npm run youtube -- PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf -o my-playlist
```

**Show help:**
```bash
npm run youtube -- --help
```

## Token Storage

Tokens are stored in `.youtube-tokens.json` in the project directory. This file contains:
- `access_token` - Short-lived token (expires in ~1 hour)
- `refresh_token` - Long-lived token used to get new access tokens
- `expires_in` - Token expiration time
- `token_type` - Usually "Bearer"

**Security Note:** Keep this file secure and don't commit it to version control (it's in `.gitignore`).

## Re-authentication

If you need to re-authenticate (e.g., tokens are invalid or you want to use a different account):

1. Delete `.youtube-tokens.json`
2. Run the exporter again - it will prompt for authentication

```bash
rm .youtube-tokens.json
npm run youtube -- YOUR_PLAYLIST_ID
```

## Troubleshooting

**"Error: redirect_uri_mismatch"**
- Make sure you created a "Desktop app" OAuth client, not "Web application"
- Desktop app clients allow `http://localhost:8080` as a redirect URI

**"Access blocked: This app's request is invalid"**
- Verify you enabled YouTube Data API v3 in your Google Cloud project
- Check that you configured the OAuth consent screen
- Add yourself as a test user if the app is not published

**"Token expired" or "Invalid credentials"**
- Delete `.youtube-tokens.json` and run again to re-authenticate

**Still getting "Private video" in output**
- Make sure you're logged in with the account that owns the playlist
- Verify the videos are actually still available (not truly deleted)
- Check that the OAuth scope includes `youtube.readonly`

**Port 8080 already in use**
- Close any application using port 8080
- Or modify the `redirectUri` in the code to use a different port (e.g., 8081)

## Output Format

Same as the Spotify exporter - exports to JSON and CSV with track details including:
- Title
- Channel/Artist
- Duration
- Video ID
- YouTube URL
- Thumbnail URL

## License

CC0-1.0