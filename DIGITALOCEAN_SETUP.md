# DigitalOcean App Platform Deployment Guide

## Prerequisites
- DigitalOcean account (https://www.digitalocean.com)
- GitHub repo pushed with all files
- DigitalOcean CLI (optional)

## Step-by-Step Deployment

### Step 1: Prepare Your GitHub Repo
Make sure these files are pushed to `bafa2024/wheelder_platform`:
```
index.py
wsgi.py
requirements.txt
.env (add to .gitignore - don't commit!)
app.yaml (just created)
Procfile
runtime.txt
```

### Step 2: Create DigitalOcean App

1. Go to https://cloud.digitalocean.com
2. Click **"Create"** → **"App"**
3. Select **"GitHub"** as source
4. Authorize GitHub and select `bafa2024/wheelder_platform` repo
5. Choose branch: **main**
6. Click **"Next"**

### Step 3: Configure App

1. **Name**: `wheelder` (or your choice)
2. **Region**: Choose closest to your users
3. **Auto-deploy**: Enable (auto-redeploy on push)
4. Click **"Edit Configuration"** → Choose **app.yaml** setup (already created)
5. Click **"Next"**

### Step 4: Set Environment Variables

Add these in the **Environment** tab:
```
FLASK_ENV = production
DEBUG = False
PORT = 8080
SECRET_KEY = generate-strong-random-key-here
```

To generate SECRET_KEY:
```bash
python -c "import secrets; print(secrets.token_hex(32))"
```

### Step 5: Add Database (Optional)

If you need MySQL:
1. Click **"Create a New Component"** → **"Database"**
2. Choose **MySQL 8**
3. Name it `wheelder-db`
4. DigitalOcean auto-adds connection string as env variable

### Step 6: Deploy

1. Review all settings
2. Click **"Create App"**
3. Wait 3-5 minutes for deployment
4. Get your live URL: `https://your-app-xxxxx.ondigitalocean.app`

## Update index.py for DigitalOcean

Your `index.py` should use port from environment:

```python
from flask import Flask
import os

app = Flask(__name__)

@app.route('/')
def hello_world():
    return 'Hello World'

if __name__ == '__main__':
    port = int(os.getenv('PORT', 8080))
    app.run(host='0.0.0.0', port=port, debug=False)
```

## Auto-Deployment

Every time you push to `main` branch:
```bash
git add .
git commit -m "Update app"
git push origin main
```

DigitalOcean automatically:
1. Detects push
2. Pulls code
3. Installs requirements
4. Runs your app
5. Updates live URL

## View Logs

1. Open your App in DigitalOcean dashboard
2. Click **"Runtime Logs"** tab
3. See real-time logs

Or via CLI:
```bash
doctl apps list
doctl apps logs <app-id>
```

## Custom Domain (Optional)

1. App Settings → **Domains**
2. Add your custom domain
3. Update DNS settings (DigitalOcean shows instructions)

## Update .gitignore

```
.env
__pycache__/
*.pyc
venv/
.DS_Store
```

## Troubleshooting

| Issue | Solution |
|-------|----------|
| App crashes | Check Runtime Logs for errors |
| ModuleNotFoundError | Ensure `requirements.txt` has all dependencies |
| Port error | Make sure app uses `PORT` env variable |
| Build fails | Check syntax in Python files |

## Commands via CLI (Optional)

Install DigitalOcean CLI:
```bash
choco install doctl  # Windows
```

View apps:
```bash
doctl apps list
```

View logs:
```bash
doctl apps logs <app-id>
```

Redeploy manually:
```bash
doctl apps create-deployment <app-id>
```

## Next Steps

1. ✅ Push code to GitHub
2. ✅ Create App on DigitalOcean
3. ✅ Set environment variables
4. ✅ Deploy and test
5. ✅ Add custom domain (optional)
6. ✅ Monitor logs

Your app will be live at: `https://your-app-xxxxx.ondigitalocean.app`
