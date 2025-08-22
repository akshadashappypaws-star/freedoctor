@echo off
echo Starting Laravel Task Scheduler...
echo This will process scheduled WhatsApp messages automatically.
echo.
echo Make sure to keep this window open for the scheduler to work.
echo Press Ctrl+C to stop the scheduler.
echo.

:loop
php artisan schedule:run
timeout /t 60 /nobreak > nul
goto loop
