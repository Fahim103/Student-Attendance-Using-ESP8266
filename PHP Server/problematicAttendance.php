<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance Home Page</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.css">
    <script src="js/bootstrap.js"></script>
    <style>
        .centerVerticall {
            margin-top: 20vh;
        }

        body {
            background-color: beige;
        }
    </style>
</head>

<body>
    <div class="container text-center centerVerticall">
        <div class="mb-5">
            <h2>Attendance System using ESP8266</h2>
        </div>
        <form id="attendanceFormProblematic" action="./attendance.php" method="POST">
            <div class="form-group row">
                <label for="subject" class="col-sm-2 col-form-label">Subject Name</label>
                <div class="col-sm-10">
                    <select id="subject" required class="form-control">
                        <option value="" selected disabled hidden>Select Subject</option>
                        <option value="1">Microprocessor</option>
                    </select>
                </div>
            </div>
            <div class="form-group row mt-4">
                <label for="section" class="col-sm-2 col-form-label">Section</label>
                <div class="col-sm-10">
                    <select id="section" required class="form-control">
                        <option value="" selected disabled hidden>Select Section</option>
                        <option value="1">A</option>
                        <option value="2">B</option>
                        <option value="3">C</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="studentIDField" class="col-sm-2 col-form-label">Student ID</label>
                <div class="col-sm-10">
                    <input type="text" name="studentIDField" id="studentIDField" required class="form-control">
                </div>
            </div>

            <div class="form-group row mt-5">
                <div class="col-sm-10">
                    <input type="submit" id="takeAttendance" name="takeProblematicAttendance" value="Add Attendance"
                        class="btn btn-primary">
                    <a href="index.php" class="btn btn-danger">Go to Homepage</a>
                </div>
            </div>
        </form>
    </div>
</body>

<script>
    $("#attendanceFormProblematic").submit(function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url = form.attr('action');
        console.log(form);
        console.log("URL IS : " + url);
        var subject = $('#subject :selected').text();
        var section = $('#section :selected').text();
        var studentID = $('#studentIDField').val()
        console.log(subject);
        console.log(section);
        console.log(studentID);
        $('#takeAttendance').attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: url,
            //data: form.serialize(), // serializes the form's elements.
            data: {
                "problematicAttendance": "problematic",
                "subject": subject,
                "section": section,
                "studentID": studentID,
            },
            success: function (data) {
                alert(data); // show response from the php script.
                $('#takeAttendance').removeAttr("disabled");
            }
        });
    });
</script>

</html>