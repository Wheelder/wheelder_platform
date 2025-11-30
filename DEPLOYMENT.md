# Deployment Guide for Wheeleder

## Files You Need for Deployment

### 1. **index.py** - Your Flask Application
- Main application file that runs your code
- Contains all routes and logic

### 2. **requirements.txt** - Dependencies
- Lists all Python packages your app needs
- Command: `pip install -r requirements.txt`

### 3. **.env** - Environment Variables
- Stores sensitive data like API keys and passwords
- NOT pushed to GitHub (add to `.gitignore`)
- Used by your app: `os.getenv('VARIABLE_NAME')`

### 4. **wsgi.py** - WSGI Server Entry Point
- Gunicorn uses this to run your app in production
- Loads environment variables from `.env`

### 5. **Procfile** - Heroku/Cloud Run Instructions
- Tells the server how to start your app
- Format: `web: gunicorn wsgi:app`

### 6. **runtime.txt** - Python Version
- Specifies which Python version to use
- Prevents version mismatch issues

## Deployment Options

### Option 1: Heroku (Easiest for Beginners)

1. Create account at https://www.heroku.com
2. Install Heroku CLI
3. Run these commands:
```bash
cd c:\xampp\htdocs\wheelder
heroku login
heroku create your-app-name
git push heroku main
```
Your app runs at: `https://your-app-name.herokuapp.com`

### Option 2: PythonAnywhere (Best for Python)

1. Go to https://www.pythonanywhere.com
2. Create account
3. Upload your files via web interface
4. Configure in Web tab: 
   - Source code: `/home/username/wheelder`
   - WSGI file: `wsgi.py`
5. Reload the web app

Your app runs at: `https://username.pythonanywhere.com`

### Option 3: Render (Free Tier Available)

1. Go to https://render.com
2. Connect your GitHub repo
3. Create new Web Service
4. Settings:
   - Runtime: Python
   - Build: `pip install -r requirements.txt`
   - Start: `gunicorn wsgi:app`
5. Deploy

Your app runs at: `https://your-app-name.onrender.com`

### Option 4: Railway (Simple & Modern)

1. Go to https://railway.app
2. Create project
3. Deploy from GitHub or upload files
4. Set environment variables
5. Railway auto-deploys

Your app runs at: `https://railway.app`

### Option 5: DigitalOcean (Most Control)

1. Create droplet (Ubuntu 22.04)
2. SSH into server
3. Install Python, pip, nginx
4. Upload files using `scp` or GitHub
5. Run with Gunicorn + Nginx

Commands:
```bash
python -m venv venv
source venv/bin/activate
pip install -r requirements.txt
gunicorn wsgi:app --bind 0.0.0.0:5000
```

## Local Testing Before Deployment

1. Install requirements:
```bash
pip install -r requirements.txt
```

2. Run locally:
```bash
python index.py
```

3. Visit: `http://localhost:5000`

## Production Checklist

- [ ] Set `DEBUG=False` in `.env`
- [ ] Change `SECRET_KEY` to a strong random value
- [ ] Add `.env` to `.gitignore`
- [ ] Test all routes locally
- [ ] Commit and push to GitHub
- [ ] Deploy using chosen platform

## Common Issues

**Issue: ModuleNotFoundError**
- Solution: Run `pip install -r requirements.txt`

**Issue: Port already in use**
- Solution: Change port in `.env` or run: `lsof -ti:5000 | xargs kill -9`

**Issue: App crashes on deploy**
- Solution: Check logs on your hosting platform
- For Heroku: `heroku logs --tail`

## Next Steps

1. Choose a deployment platform above
2. Update `.env` with your settings
3. Push code to GitHub
4. Deploy using platform instructions
5. Test your live app
