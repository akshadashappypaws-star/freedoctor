@echo off
echo Setting up Git repository for FreeDoctorWeb...

REM Navigate to project directory
cd /d "C:\xampp\htdocs\freedoctor-web"

REM Create README.md if it doesn't exist
if not exist README.md (
    echo # FreeDoctorWeb > README.md
    echo. >> README.md
    echo Modern WhatsApp Doctor Consultation Platform >> README.md
    echo. >> README.md
    echo ## Features >> README.md
    echo - WhatsApp Integration >> README.md
    echo - Doctor-Patient Communication >> README.md
    echo - Appointment Management >> README.md
    echo - Modern UI with Bootstrap 5 >> README.md
    echo. >> README.md
    echo ## Tech Stack >> README.md
    echo - Laravel 10 >> README.md
    echo - Bootstrap 5.3 >> README.md
    echo - TailwindCSS >> README.md
    echo - Vite >> README.md
    echo - WhatsApp Business API >> README.md
)

REM Create .gitignore for Laravel
if not exist .gitignore (
    echo /node_modules > .gitignore
    echo /public/hot >> .gitignore
    echo /public/storage >> .gitignore
    echo /storage/*.key >> .gitignore
    echo /vendor >> .gitignore
    echo .env >> .gitignore
    echo .env.backup >> .gitignore
    echo .phpunit.result.cache >> .gitignore
    echo docker-compose.override.yml >> .gitignore
    echo Homestead.json >> .gitignore
    echo Homestead.yaml >> .gitignore
    echo npm-debug.log* >> .gitignore
    echo yarn-error.log >> .gitignore
    echo yarn.lock >> .gitignore
    echo .DS_Store >> .gitignore
    echo Thumbs.db >> .gitignore
)

REM Initialize Git repository
git init

REM Add all files
git add .

REM Make initial commit
git commit -m "Initial commit: FreeDoctorWeb Laravel application with WhatsApp integration"

REM Set main branch
git branch -M main

REM Add remote origin
git remote add origin https://github.com/abhishekkumarkhantwal/freedoctor-web.git

REM Push to GitHub
git push -u origin main

echo.
echo ✅ Git repository setup complete!
echo 🚀 Project pushed to: https://github.com/abhishekkumarkhantwal/freedoctor-web
echo.
pause
