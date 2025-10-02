Xserver Frontend Deploy

Overview
- Upload the Vue build output (`dist/` contents) to the domain’s document root.
- Place the provided `.htaccess` for SPA fallback and `/storage` rewrite.

Steps
1) Build locally:
   - `npm ci && npm run build`
   - Ensure API base URL points to your API domain (e.g., https://api.example.com)

2) Upload to Xserver:
   - Upload the contents of `dist/` (index.html, css/js assets) into the public folder (document root).

3) Add `.htaccess`:
   - Copy `deploy/xserver/.htaccess` to the document root. This:
     - Rewrites `/storage/*` to `https://api.example.com/storage/*` (optional if API returns absolute URLs)
     - Enables SPA history fallback to `index.html`

4) SSL/HTTPS:
   - Enable SSL for the domain in Xserver panel.
   - Access `https://www.example.com` to verify.

Notes
- If you set `PUBLIC_STORAGE_URL=https://api.example.com/storage` on the API, images/PDF URLs will already be absolute and the `/storage` rewrite here is not needed.
- The router runs in hash mode, so no additional server-side rewrite is needed other than serving `index.html`.

