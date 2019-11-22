<?php
    $content = file_get_contents('http://192.168.135.135/macs.txt');
?>

<html>
    <header>
        <h1>Welcome to the ESP8266 Attendance System</h1>
        <h2><?=$content?></h2>
    </header>
</html>