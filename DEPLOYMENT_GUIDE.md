# Free Deployment Guide for Blog App

This Laravel 12 blog app can be deployed for free using several popular platforms. This guide covers the best free options.

## Quick Comparison

| Platform | Database | Storage | Notes |
|----------|----------|---------|-------|
| **Heroku** | ❌ Free tier removed | PostgreSQL paid | ⚠️ No longer recommended |
| **Railway** | ✅ 5GB free | ✅ Free | **Easiest** - $5/month after free credits |
| **Render** | ✅ 256MB free | ✅ Free static | **Good** - completely free tier |
| **Vercel** | ❌ Node only | ✅ Free | Not suitable for Laravel |
| **Replit** | ✅ Free | ✅ Free | Development only, slow |
| **PythonAnywhere** | ❌ Python only | N/A | Not suitable for PHP |

---

## 🚀 **Option 1: Railway (Recommended - Easiest)**

Railway offers a clean free tier with $5 free credits monthly, perfect for hobby projects.

### Prerequisites
- GitHub account (recommended) or email
- Git installed locally

### Steps

#### 1. Prepare Your Repository
```bash
# Initialize git if not done
git init
git add .
git commit -m "Initial commit"

# Connect to GitHub (recommended) or push directly to Railway
git remote add origin https://github.com/YOUR_USERNAME/blog-app.git
git push -u origin main
```

#### 2. Create Railway Account
- Go to [railway.app](https://railway.app)
- Sign up with GitHub or email
- Confirm your email

#### 3. Deploy via Railway Dashboard
1. Click **"New Project"** → **"Deploy from GitHub repo"**
2. Select your `blog-app` repository
3. Railway auto-detects it's a Laravel project
4. Create the project

#### 4. Configure Environment Variables
After deployment starts, go to **Project Settings** → **Variables**:

```
APP_NAME=BlogApp
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE  # Generate with: php artisan key:generate
APP_DEBUG=false
APP_URL=https://your-railway-domain.up.railway.app

DB_CONNECTION=mysql
DB_HOST=${{Mysql.MYSQL_HOST}}
DB_PORT=${{Mysql.MYSQL_PORT}}
DB_DATABASE=${{Mysql.MYSQL_DATABASE}}
DB_USERNAME=${{Mysql.MYSQL_USER}}
DB_PASSWORD=${{Mysql.MYSQL_PASSWORD}}

SESSION_DRIVER=database
CACHE_DRIVER=database
```

#### 5. Add MySQL Database
1. In Railway dashboard, click **"Add Service"** → **"MySQL"**
2. Variables are auto-populated with `${{Mysql.*}}` references

#### 6. Run Database Migrations
Once deployed, in your Railway dashboard:
1. Go to your deployment logs
2. Click **"Settings"** (top-right)
3. Under **"Deploy" → "Deployment Hooks"**, add:
   ```
   php artisan migrate --force
   php artisan key:generate
   ```
4. Or manually via Railway CLI:
   ```bash
   railway run php artisan migrate --force
   ```

#### 7. Redeploy
Push a new commit to trigger deployment:
```bash
git commit --allow-empty -m "Trigger deployment"
git push
```

---

## 🎯 **Option 2: Render (Completely Free)**

Render offers a truly free tier but with limitations (slower cold starts).

### Steps

#### 1. Push to GitHub
Same as Railway step 1 above.

#### 2. Create Render Account
- Go to [render.com](https://render.com)
- Sign up with GitHub
- Connect your GitHub account

#### 3. Create a Web Service
1. Dashboard → **"New"** → **"Web Service"**
2. Select your `blog-app` repository
3. Configure:
   - **Name**: `blog-app`
   - **Environment**: `PHP`
   - **Build Command**: 
     ```
     composer install && npm install && npm run build && php artisan optimize
     ```
   - **Start Command**: 
     ```
     php artisan serve --host=0.0.0.0 --port=10000
     ```
   - **Plan**: Free

#### 4. Add Environment Variables
Go to **Environment** tab, add:

```
APP_NAME=BlogApp
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_KEY_HERE
APP_URL=https://your-app.onrender.com

DB_CONNECTION=mysql
DB_HOST=your-db.c.db.onrender.com
DB_PORT=3306
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_password

SESSION_DRIVER=database
CACHE_DRIVER=database
```

#### 5. Add MySQL Database (Paid - $15/month minimum)
⚠️ **Note**: Render's free tier doesn't include managed databases. You'd need to either:
- Use a **paid Render PostgreSQL** ($15/month)
- Use **free external database** (see SQLite option below)

#### 6. (Alternative) Use SQLite Locally
If you want to stay free on Render:
```bash
# Update .env
DB_CONNECTION=sqlite
# Remove other DB_* variables
```

---

## 💾 **Option 3: Free SQLite + Netlify (Very Budget-Friendly)**

For completely free hosting without databases, use SQLite (already in your app).

### Steps

#### 1. Update `.env` for SQLite
```env
DB_CONNECTION=sqlite
# Comment out or remove all DB_HOST, DB_PORT, etc.
```

#### 2. Create `.htaccess` in `/public`
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>
```

#### 3. Deploy to Netlify (Static Hosting)
⚠️ **Limitation**: Netlify doesn't support PHP. Use **Render** or **Railway** instead.

---

## 🔧 **Option 4: Replit (Development/Learning)**

Best for learning and testing, not production.

### Steps

1. Go to [replit.com](https://replit.com)
2. Click **"Create Repl"** → **"Import from GitHub"**
3. Enter your repo URL
4. Click **"Run"** (installs dependencies automatically)
5. Access via Replit's hosted URL

### Limitations
- Slow (spins down after inactivity)
- 500MB storage
- No paid database (SQLite only)

---

## 📋 **Pre-Deployment Checklist**

Before deploying, ensure:

- [ ] `.env.example` is up-to-date with all required variables
- [ ] Database migrations exist: `php artisan migrate --dry-run`
- [ ] Build passes: `npm run build`
- [ ] No hardcoded local paths or secrets in code
- [ ] `.gitignore` includes: `.env`, `node_modules/`, `/vendor`, `storage/`, `bootstrap/cache/`
- [ ] `APP_DEBUG=false` in production
- [ ] Generate APP_KEY locally: `php artisan key:generate`

---

## 🚨 **Common Issues & Fixes**

### Issue: "SQLSTATE[HY000]: General error: 1030"
**Cause**: SQLite permissions issue
**Fix**: SSH into server and run:
```bash
chmod 666 database.sqlite
chmod 775 database/
```

### Issue: "Class 'PDO' not found"
**Cause**: PHP extensions missing
**Fix**: Check your platform supports SQLite or MySQL drivers (usually included)

### Issue: CSS/JS not loading
**Cause**: Assets not built in production
**Fix**: Ensure `npm run build` runs in build command

### Issue: Database migrations not running
**Fix**: Add to deployment hook or run manually after first deploy:
```bash
php artisan migrate --force
php artisan db:seed  # Optional: seed initial data
```

---

## 🎯 **Recommended Setup for Your Blog**

**Best for free**: Use **Railway** or **Render** with MySQL
- Railway: Free $5 monthly credits (usually enough for hobby site)
- Render: Completely free but slower with limitations

**Commands to remember**:
```bash
# Generate APP_KEY
php artisan key:generate

# Test build
npm run build

# Test migrations
php artisan migrate:fresh --seed

# Optimize for production
php artisan optimize
php artisan config:cache
php artisan route:cache
```

---

## 📚 **Resources**

- [Laravel Deployment Docs](https://laravel.com/docs/12/deployment)
- [Railway Docs](https://docs.railway.app)
- [Render Docs](https://render.com/docs)
- [GitHub Student Developer Pack](https://education.github.com/pack) - Free credits for many platforms

---

**Questions?** Check your platform's documentation or Laravel forums. Good luck! 🚀
