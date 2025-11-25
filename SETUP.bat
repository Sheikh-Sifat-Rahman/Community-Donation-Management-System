@echo off
echo ========================================
echo Community Donation System - Setup
echo ========================================
echo.

echo Step 1: Starting XAMPP Control Panel...
start "" "C:\xampp\xampp-control.exe"
echo.

echo Step 2: Please do the following in XAMPP Control Panel:
echo   1. Click "Start" button next to Apache
echo   2. Click "Start" button next to MySQL
echo.

echo Step 3: Setup Database
echo   After starting Apache and MySQL:
echo   1. Open your browser and go to: http://localhost/phpmyadmin
echo   2. Create a new database named: community_donation
echo   3. Click on the database and go to "Import" tab
echo   4. Select the file: C:\xampp\htdocs\Community Donation\database\schema.sql
echo   5. Click "Go" to import
echo.

echo Step 4: Access the Website
echo   Open your browser and go to:
echo   http://localhost/Community Donation/
echo.

echo ========================================
echo Setup Instructions Complete!
echo ========================================
pause
