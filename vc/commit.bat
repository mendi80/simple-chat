

SET "tmpdir=A:\temp\website"

REM cd A:\temp\website
REM rm -rf .git && git init -b main && git commit --allow-empty -m "root" && git add --all -- :!index.html && git commit -m "base" git add . & git commit -m "html"
REM git remote add origin https://github.com/vcorona-israel/website.git && git push -u --force origin main

copy /Y A:\Programs\xampp\htdocs\vc\website\index.html %tmpdir%
cd /D %tmpdir%
git status
git commit --all --amend --no-edit
git push origin main --force

cd %~dp0
