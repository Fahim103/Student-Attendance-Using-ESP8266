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

    <form id="attendanceFormProblematic" action="./attendance.php" method="POST">
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
        
        <label for="studentIDField">Student ID</label>
        <input type="text" name="studentIDField" id="studentIDField" required>
        <br />
        <input type="submit" name="takeProblematicAttendance" value="Add Attendance">
    </form>
    <a href="index.php">Go to Homepage</a>
</body>

    <script>
        $("#attendanceFormProblematic").submit(function(e) {
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
        $.ajax({
            type: "POST",
            url: url,
            //data: form.serialize(), // serializes the form's elements.
            data: {
                "problematicAttendance" : "problematic",
                "subject" : subject,
                "section" : section,
                "studentID" : studentID,
            },
            success: function(data)
            {
                alert(data); // show response from the php script.
            }
            });
        });
    </script>
</html>