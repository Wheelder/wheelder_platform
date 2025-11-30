from fastapi import APIRouter

from controllers.blog_controller import router as blogs
from controllers.user_controller import router as users
from controllers.files_controller import router as files
from controllers.logs_controller import router as logs

router = APIRouter()

router.include_router(blogs, prefix="/blogs")
router.include_router(users, prefix="/users")
router.include_router(files, prefix="/files")
router.include_router(logs, prefix="/logs")