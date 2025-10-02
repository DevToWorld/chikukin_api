# Deployment Guides

This directory contains deployment guides and scripts for different hosting scenarios.

## Available Deployment Options

### 1. Laravel Backend Only to www Subdomain
**Recommended for API-only deployments**

- **Guide**: `laravel-backend-www-deployment-guide.md`
- **Script (Linux/Mac)**: `deploy-www.sh`
- **Script (Windows)**: `deploy-www.bat`
- **Config**: `www-deployment-config.env`

**Use this when:**
- You want to deploy only the Laravel API backend
- Your frontend is hosted separately (e.g., Vercel, Netlify)
- You need a clean API endpoint at `https://www.yourdomain.com/api/`

### 2. Domain-Based Separation (Frontend + Backend)
**For hosting both frontend and backend on the same server**

- **Guide**: `domain-separation-guide.md`
- **Frontend**: `linkinc.htaccess`
- **Backend**: `apilinkinc.htaccess`

**Use this when:**
- You want to host both Vue.js frontend and Laravel backend
- You have multiple domains/subdomains
- You need to proxy API requests from frontend to backend

### 3. Other Deployment Options

- **Railway**: See `railway-nginx.conf` and Laravel backend documentation
- **XServer**: See `xserver/` directory
- **Konoha**: See `konoha/` directory

## Quick Start

### For Laravel Backend Only (www deployment):

1. **Update configuration**:
   ```bash
   # Edit the deployment script paths
   nano deploy/deploy-www.sh
   # Update PROJECT_ROOT and WWW_DIR variables
   ```

2. **Run deployment**:
   ```bash
   # Linux/Mac
   ./deploy/deploy-www.sh
   
   # Windows
   deploy/deploy-www.bat
   ```

3. **Configure your domain** to point to the www directory

### For Full Stack (Frontend + Backend):

1. Follow `domain-separation-guide.md`
2. Deploy frontend to `/home/user/www/linkinc/`
3. Deploy backend to `/home/user/www/www/`

## File Structure

```
deploy/
├── README.md                                    # This file
├── laravel-backend-www-deployment-guide.md     # Laravel backend only guide
├── domain-separation-guide.md                  # Full stack deployment guide
├── deploy-www.sh                               # Linux/Mac deployment script
├── deploy-www.bat                              # Windows deployment script
├── www-deployment-config.env                   # Configuration template
├── linkinc.htaccess                           # Frontend .htaccess
├── apilinkinc.htaccess                        # Legacy backend .htaccess
├── railway-nginx.conf                         # Railway deployment config
├── xserver/                                   # XServer specific configs
└── konoha/                                    # Konoha VPS configs
```

## Configuration Files

### .htaccess Files

- `laravel-backend-www.htaccess`: For Laravel backend deployed to www root
- `document-root-public.htaccess`: For Laravel deployed to document root with public folder redirect
- `linkinc.htaccess`: For Vue.js frontend with API proxy
- `apilinkinc.htaccess`: For Laravel API on separate subdomain

### Environment Configuration

Copy `www-deployment-config.env` and update with your settings:
- Project paths
- Domain configuration
- Database settings
- Security settings

## Security Considerations

1. **SSL Certificates**: Ensure HTTPS is configured for all domains
2. **CORS Settings**: Configure appropriate CORS headers for your setup
3. **File Permissions**: Set proper ownership and permissions for Laravel
4. **Environment Variables**: Keep sensitive data in `.env` files
5. **Database Security**: Use strong passwords and limit database access

## Troubleshooting

### Common Issues

1. **Permission Errors**:
   ```bash
   chown -R www-data:www-data /path/to/laravel
   chmod -R 755 /path/to/laravel/storage
   ```

2. **CORS Errors**:
   - Check `.htaccess` CORS headers
   - Verify Laravel CORS configuration
   - Test with browser developer tools

3. **Database Connection**:
   - Verify `.env` database settings
   - Check database server status
   - Test connection with `php artisan tinker`

4. **File Not Found (404)**:
   - Check `.htaccess` rewrite rules
   - Verify Laravel routing
   - Check file permissions

### Debug Mode

For troubleshooting, temporarily enable debug mode in `.env`:
```env
APP_DEBUG=true
APP_ENV=local
```

**Remember to disable in production!**

## Support

If you encounter issues:

1. Check the relevant deployment guide
2. Review the troubleshooting section
3. Check Laravel logs: `storage/logs/laravel.log`
4. Check web server error logs
5. Verify file permissions and ownership

## Updates

To update your deployment:

1. **Laravel Backend**: Run the deployment script again
2. **Frontend**: Rebuild and copy to the appropriate directory
3. **Database**: Run migrations if needed
4. **Configuration**: Update `.env` files as needed
