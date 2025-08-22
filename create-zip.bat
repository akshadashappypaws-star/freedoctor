@echo off
echo Creating FreeDoctor production ZIP file...

powershell -Command "Compress-Archive -Path @('app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', 'storage', 'tests', 'vendor', 'artisan', 'composer.json', 'composer.lock', '.env.hostinger', 'setup-hostinger.sh') -DestinationPath 'freedoctor-production.zip' -Force"

if exist "freedoctor-production.zip" (
    echo ✅ ZIP file created successfully: freedoctor-production.zip
    dir freedoctor-production.zip
) else (
    echo ❌ Failed to create ZIP file
)

pause
