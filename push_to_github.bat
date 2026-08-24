@echo off
echo ==========================================
echo Syncing Sailor Theme with GitHub...
echo ==========================================

cd /d "C:\xampp\htdocs\sailor\wp-content\themes\sailor"

if not exist ".git" (
    echo Initializing Git repository...
    git init
    git branch -M main
    git remote add origin https://github.com/mycodevibe-ux/sailor-wp.git
)

git add .
git commit -m "Update Sailor WordPress Theme"
git push -u origin main

echo.
echo ==========================================
echo Successfully Pushed to GitHub!
echo ==========================================
pause
