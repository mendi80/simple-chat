

SET "tmpdir=B:\temp\website"

REM cd A:\temp\website
REM rm -rf .git && git init -b main && git commit -v --allow-empty -m "root" 
REM git add -v --all -- :!index.html && git commit -vm "base" && git add . && git commit -vm "html"
REM git remote add origin https://github.com/vcorona-israel/website.git && git push -u --force origin main

copy /Y B:\xampp\htdocs\vc\website\index.html %tmpdir%
cd /D %tmpdir%
git status
git commit --all --amend --no-edit
git push origin main --force

cd %~dp0
