const char configure[] PROGMEM = R"=====(
<!DOCTYPE html>
<html>
    <head>
        <title>Welcome Page</title>
    </head>
    <body>
        Welcome to ESP8266 Attendance system!
    </body>
</html>
)=====";

const char login[] PROGMEM = R"=====(
<!DOCTYPE html>
<html>
    <head>
        <title>Login Page</title>
    </head>
    <body>
        <form action="/login" method="POST">
            <input type="text" name="uname" placeholder="Username">
            </br>
            <input type="password" name="pass" placeholder="Password">
            </br>
            <input type="submit" value="Login">
        </form>
        <p>Try 'admin' and 'admin'</p>
    </body>
</html>
)=====";