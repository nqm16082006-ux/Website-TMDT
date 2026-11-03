@echo off
chcp 65001 >nul
title Khởi chạy Website TMDT Điện Máy XANH
echo ====================================================================
echo   KHỞI CHẠY WEBSITE THƯƠNG MẠI ĐIỆN TỬ (ĐIỆN MÁY XANH - TECHSTORE)
echo ====================================================================
echo.
echo [1] Trang Khách Hàng: http://localhost:8000
echo [2] Trang Quản Trị  : http://localhost:8000/admin.php
echo.
echo [*] Tài khoản Admin : admin@dienmayxanh.com  ^| Mật khẩu: 123456
echo [*] Tài khoản User  : minh.ngo@example.com   ^| Mật khẩu: 123456
echo.
echo [*] Trạng thái CSDL : techstore_db đã nạp sẵn vào MySQL.
echo Nhấn Ctrl + C để dừng máy chủ bất cứ lúc nào.
echo ====================================================================
echo.

start http://localhost:8000

cd /d "F:\laragon\www\Website TMDT"
"F:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe" -S localhost:8000
pause
