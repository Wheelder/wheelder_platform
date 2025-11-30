import os

BASE_DIR = os.path.dirname(os.path.dirname(__file__))
DEBUG = os.getenv('DEBUG', 'False') == 'True'
SECRET_KEY = os.getenv('SECRET_KEY', 'super-secret-change-me')

# Simple settings placeholder
STATIC_URL = '/ui/assets/'
TEMPLATES_DIR = os.path.join(BASE_DIR, 'ui')
