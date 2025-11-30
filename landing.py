from fastapi import APIRouter
from fastapi.responses import HTMLResponse

router = APIRouter()

@router.get('/', response_class=HTMLResponse)
async def landing():
    # Render the landing template or simple HTML
    return "<h1>Welcome to Wheelder</h1>"
