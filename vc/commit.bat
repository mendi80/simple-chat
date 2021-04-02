echo on

set "DRV=%CD:~0,2%"
SET "tmpdir=%DRV%\temp\website"

REM cd A:\temp\website  OR git clone https://github.com/vcorona-israel/website.git
REM rm -rf .git && git init -b main && git commit -v --allow-empty -m "root" 
REM git add -v --all -- :!index.html && git commit -vm "base" && git add . && git commit -vm "html"
REM git remote add origin https://github.com/vcorona-israel/website.git && git push -u --force origin main

copy /Y %DRV%\xampp\htdocs\vc\website\index.html %tmpdir% || goto :error
cd /D %tmpdir% || goto :error
git status 
git commit --all --amend --no-edit || goto :error
git push origin main --force 

cd %~dp0


:error
echo Failed with error #%errorlevel%.
exit /b %errorlevel%
