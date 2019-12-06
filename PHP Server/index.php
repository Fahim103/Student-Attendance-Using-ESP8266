<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance Home Page</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
    <form id="attendanceForm" action="./attendance.php" method="POST">
        <label>Subject Name</label>
        <select id="subject" required>
            <option value="" selected disabled hidden>Select Subject</option>
            <option value="1">Microprocessor</option>
        </select>
        <br />

        <label>Section</label>
        <select id="section" required>
            <option value="" selected disabled hidden>Select Section</option>
            <option value="1">A</option>
            <option value="2">B</option>
            <option value="3">C</option>
        </select>
        <br />
        <input type="submit" id="takeAttendance" name="takeAttendance" value="Take Attendance">
    </form>

    <a href="problematicAttendance.php">Problematic?</a>
</body>

    <script>
        $("#attendanceForm").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url = form.attr('action');
        console.log(form);
        console.log("URL IS : " + url);
        var subject = $('#subject :selected').text();
        var section = $('#section :selected').text();
        console.log(subject);
        console.log(section);
        $('#takeAttendance').attr("disabled", "disabled");
        $.ajax({
            type: "POST",
            url: url,
            //data: form.serialize(), // serializes the form's elements.
            data: {
                "takeAttendance" : "takeAttendance",
                "subject" : subject,
                "section" : section,
            },
            success: function(data)
            {
                alert(data); // show response from the php script.
                $('#takeAttendance').removeAttr("disabled");
            }
            });
        });
    </script>
</html>