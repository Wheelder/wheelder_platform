from fastapi import APIRouter

router = APIRouter()

@router.get('/users/me')
async def current_user():
    return {"user": None}
