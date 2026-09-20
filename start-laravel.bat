```bat
@echo off
title Laravel Development Environment

echo ==========================================
echo      Starting Laravel Development
echo ==========================================

:: Laravel Server
start "Laravel Server" cmd /k "php artisan serve"

:: Queue Worker
start "Laravel Queue" cmd /k "php artisan queue:work"

:: Scheduler
//start "Laravel Scheduler" cmd /k "php artisan schedule:work"

:: Laravel Reverb WebSocket
//start "Laravel Reverb" cmd /k "php artisan reverb:start"

echo.
echo ==========================================
echo All Laravel services started!
echo ==========================================
echo.
pause
```
