# Railway GitHub Connection Guide

## Your Repository is Ready ✅
- **GitHub Repo**: https://github.com/Raibach/john.holtportfolio.com.git
- **Branch**: `main`
- **Content**: `public_html/wp-content/` folder

## Quick Connection Steps (2 minutes)

1. **Open Railway Dashboard**
   - Go to: https://railway.app
   - Sign in to your account

2. **Find Your Project**
   - Look for project with URL: `primary-production-6a4bb.up.railway.app`
   - Or go to: https://railway.app/dashboard

3. **Connect GitHub**
   - Click on your WordPress service
   - Go to **Settings** tab
   - Scroll to **Source** section
   - Click **"Connect Repo"** button
   - Authorize Railway (if prompted)
   - Select: `Raibach/john.holtportfolio.com`
   - Branch: `main`
   - Click **Connect**

4. **Done!**
   - Railway will automatically deploy your `wp-content` folder
   - Check the **Deployments** tab to see progress

## Alternative: Railway CLI (if you prefer command line)

```bash
# 1. Login (opens browser)
railway login

# 2. Link to your project
railway link

# 3. Connect GitHub repo (via Railway dashboard after linking)
```

---

**Note**: Your GitHub repository is already set up and ready. Railway just needs to be connected via the web dashboard (takes about 2 minutes).

