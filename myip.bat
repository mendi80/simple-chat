

@echo on

:: WhatIsMyIP.cmd - returns public IP address
:: requires: wget.exe

if [%1]==[-h] goto :HELP
if [%1]==[--help] goto :HELP
if [%1]==[/?] goto :HELP

wget -q -O %temp%\MyIP http://www.whatismyip.com/automation/n09230945.asp
for /f "delims= " %%G in (%temp%\myip) do set PublicIP=%%G & del %temp%\MyIP
echo. & echo Your public IP address is %PublicIP% & echo.
if [%1]==[--clip] echo %PublicIP% | clip
goto :EOF

:HELP
echo. & echo Usage: whatismyip [--clip] & echo.
goto :EOF

:EOF

