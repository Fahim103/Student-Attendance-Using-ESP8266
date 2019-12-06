const char success[] PROGMEM = R"=====(
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Registration</title>
    <style type="text/css">
        :root {
            --dark-main-primary-color: #f5f5dc;
            --main-primary-color: #78909C;
            --btn-primary-color: #3F51B5;
            --btn-primary-hover-color: #5C6BC0;
        }

        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        body {
            background: var(--dark-main-primary-color);
            color: #fff;
        }

        #registration-container {
            margin: 30px auto;
            max-width: 400px;
            padding: 20px;
        }

        .form-wrap {
            background-color: #fff;
            padding: 15px 25px;
            color: #333;
            ;
        }

        .form-wrap h1 {
            text-align: center;
        }

        .form-wrap .form-group {
            margin-top: 15px;
        }

        .gender input {
            margin: 10px;
            margin-top: 20px;
        }

        .form-wrap .form-group label {
            display: block;
            color: #666;
        }

        .form-wrap .form-group input {
            width: 100%;
            padding: 10px;
            border: #ddd 1px solid;
            border-radius: 5px;
        }

        .form-wrap button {
            width: 100%;
            display: block;
            padding: 10px;
            margin-top: 20px;
            color: #fff;
            background-color: var(--btn-primary-color);
            cursor: pointer;
            border: none;
        }

        .form-wrap button:hover {
            width: 100%;
            display: block;
            padding: 10px;
            margin-top: 20px;
            color: #fff;
            background-color: var(--btn-primary-hover-color);
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div id="registration-container">
        <div class="form-wrap">
            <h1>Success!</h1>
        </div>
    </div>
</body>

</html>
)=====";