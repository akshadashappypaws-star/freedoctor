@echo off
echo ===========================================
echo  FreeDoctorWeb - GitHub Push Script
echo ===========================================
echo.

REM Navigate to project directory
cd /d "C:\xampp\htdocs\freedoctor-web"

echo 📋 Setting Git configuration...
"C:\Program Files\Git\bin\git.exe" config user.name "abhishekkumarkhantwal"
"C:\Program Files\Git\bin\git.exe" config user.email "abhishekkumarkhantwal@gmail.com"

echo 📋 Checking repository status...
"C:\Program Files\Git\bin\git.exe" status

echo.
echo 🌐 Setting remote origin to your repository...
"C:\Program Files\Git\bin\git.exe" remote set-url origin https://github.com/abhishekkumarkhantwal/freedoctor-web.git

echo.
echo 📤 Pushing to GitHub...
echo ⚠️  You will be prompted for authentication:
echo    Username: abhishekkumarkhantwal
echo    Password: [Use your Personal Access Token]
echo.

"C:\Program Files\Git\bin\git.exe" push -u origin main

echo.
if %ERRORLEVEL% EQU 0 (
    echo ✅ SUCCESS! Code pushed to GitHub successfully!
    echo 🌐 Repository URL: https://github.com/abhishekkumarkhantwal/freedoctor-web
    echo.
    echo 📋 Repository Details:
    echo    - 330+ files committed
    echo    - Modern WhatsApp integration
    echo    - Enhanced UI with Bootstrap 5 + TailwindCSS
    echo    - Laravel 10 backend
    echo    - Production-ready build system
) else (
    echo ❌ Error occurred during push. Please check:
    echo    1. Repository exists on GitHub
    echo    2. Personal Access Token is valid
    echo    3. Internet connection is stable
)

echo.
echo 🔗 Next Steps:
echo    1. Visit: https://github.com/abhishekkumarkhantwal/freedoctor-web
echo    2. Add repository description and topics
echo    3. Configure branch protection rules
echo    4. Set up GitHub Actions (optional)

echo.
pause
