from fastapi import FastAPI, Request
from fastapi.responses import HTMLResponse
from fastapi.staticfiles import StaticFiles
from fastapi.templating import Jinja2Templates
from main_router import router

app = FastAPI(title="Wheelder Platform")

app.mount("/static", StaticFiles(directory="ui/assets"), name="static")


app.include_router(router)

templates = Jinja2Templates(directory="ui/views")

@app.get("/", response_class=HTMLResponse)
async def landing(request: Request):
    return templates.TemplateResponse("landing/index.html", {"request": request})

@app.get("/api/health")
async def health():
    return {"status": "ok", "engine": "FastAPI running"}


@app.get("/info")
def app_info():
    return {"status": "ok", "engine": "FastAPI running"}