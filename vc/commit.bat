echo on

set "DRV=%CD:~0,2%"
SET "tmpdir=%DRV%\temp\website"

REM cd A:\temp\website  OR git clone https://github.com/vcorona-israel/website.git
REM rm -rf .git && git init -b main && git commit -v --allow-empty -m "root" 
REM git add -v --all -- ':!index.html' && git commit -vm "base" && git add . && git commit -vm "html"
REM git remote add origin git@siders:vcorona-israel/website.git && git push -u --force origin main

copy /Y %DRV%\xampp\htdocs\vc\website\index.html %tmpdir% || goto :error
copy /Y %DRV%\xampp\htdocs\vc\website\404.html %tmpdir% || goto :error
cd /D %tmpdir% || goto :error
REM powershell -Command "(gc index.html) -replace 'aHR0cDovL3NpZGVyczIuZGRucy5uZXQ6NjA3NzcvbWRpc3BhdGNoLnBocA', 'aHR0cDovL3NpZGVyczIuZGRucy5uZXQ6NjA2NjYvbWRpc3BhdGNoLnBocA' | Out-File index.html" || goto :error
REM git pull --rebase --allow-unrelated-histories || goto :error
REM git add "index.html" || goto :error
git status || goto :error
git commit --all --amend --no-edit -v || goto :error
git push origin main --force -v|| goto :error

cd %~dp0 || goto :error

echo Done!
exit /b DONE!

:error
cd %~dp0
echo Failed with error #%errorlevel%.
exit /b %errorlevel%
