from fastapi import APIRouter, Request
from fastapi.responses import HTMLResponse
from fastapi.templating import Jinja2Templates

templates = Jinja2Templates(directory="ui/views")

router = APIRouter()

BLOGS = [
{"id": 1, "title": "Welcome to Wheelder Blog", "content": "This is your first post."},
{"id": 2, "title": "Platform Architecture", "content": "Your multi-service layout explained."}
]

@router.get("/", response_class=HTMLResponse)
async def list_blogs(request: Request):
    return templates.TemplateResponse("blogs/list.html", {"request": request, "blogs": BLOGS})

@router.get("/view/{blog_id}", response_class=HTMLResponse)
async def view_blog(blog_id: int, request: Request):
    blog = next((b for b in BLOGS if b["id"] == blog_id), None)
    return templates.TemplateResponse("blogs/view.html", {"request": request, "blog": blog})