from fastapi import APIRouter

router = APIRouter()

@router.get('/logs')
async def get_logs():
    return {"logs": []}
