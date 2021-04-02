

SET "xampp=B:\xampp"
SET "targetdir=%xampp%\htdocs\vc\settings"

copy /Y  "%xampp%\apache\conf\httpd.conf" "%targetdir%"
copy /Y  "%xampp%\php\php.ini" "%targetdir%"
copy /Y  "%xampp%\mysql\data\my.ini" "%targetdir%"




