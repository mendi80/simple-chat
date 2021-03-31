

SET "x=A:\Programs\xampp"
SET "s=%x%\htdocs\vc\settings"

copy /Y 

copy /Y  "%x%\apache\conf\httpd.conf" "%s%"
copy /Y  "%x%\php\php.ini" "%s%"





