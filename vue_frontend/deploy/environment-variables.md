# Railway Environment Variables for CORS

If you're deploying to Railway and having CORS issues, you can set these environment variables in your Railway dashboard:

## Required Environment Variables

```bash
# CORS Configuration
CORS_ALLOWED_ORIGINS=https://modeitest.vercel.app,http://localhost:3000,http://localhost:8080,http://localhost:5173,http://127.0.0.1:8080,http://127.0.0.1:3000,http://127.0.0.1:5173
CORS_ALLOWED_ORIGIN_PATTERNS=/^http:\/\/localhost:\d+$/,/^http:\/\/127\.0\.0\.1:\d+$/,/^https:\/\/.*\.vercel\.app$/,/^https:\/\/.*\.railway\.app$/
CORS_ALLOWED_METHODS=GET,POST,PUT,DELETE,PATCH,OPTIONS
CORS_ALLOWED_HEADERS=Accept,Authorization,Content-Type,Origin,X-Requested-With,X-CSRF-TOKEN
CORS_EXPOSED_HEADERS=Authorization,X-RateLimit-Limit,X-RateLimit-Remaining,X-Request-Id
CORS_MAX_AGE=86400
CORS_SUPPORTS_CREDENTIALS=true
```

## How to Set Environment Variables in Railway

1. Go to your Railway project dashboard
2. Click on your Laravel backend service
3. Go to the "Variables" tab
4. Add each variable above as a new environment variable
5. Redeploy your service

## Alternative: Simpler CORS Origins for Development

For development purposes, you can use wildcards (not recommended for production):

```bash
CORS_ALLOWED_ORIGINS=*
CORS_ALLOWED_ORIGIN_PATTERNS=
```

**Note**: Using `*` for origins disables credentials. If you need authentication, specify exact origins instead.

