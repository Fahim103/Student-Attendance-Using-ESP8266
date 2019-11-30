<?php

    require('utilities.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['takeAttendance'])) {            
            // Taking attendance for all students
            // Microprocessor_Section_A
            $fileToWorkWith = $_POST['subject'].'_Section_'.$_POST['section'].'.xlsx';
            addCurrentDateColumnInExcelFile($fileToWorkWith);
            giveAttendanceToAllAddressFromTextFileContainingMac($fileToWorkWith);
            echo "Attendance taken successfully for " . $_POST['subject'] . " Section " . $_POST['section'];
        }elseif (isset($_POST['problematicAttendance'])) {
            $fileToWorkWith = $_POST['subject'].'_Section_'.$_POST['section'].'.xlsx';
            $studentRow = searchStudentByID($_POST['studentID'], $fileToWorkWith);
            if($studentRow == -1){
                echo "Student with id '". $_POST['studentID'] ."' not found in file " . $_POST['subject'].'_Section_'.$_POST['section'].'.xlsx';
                exit(); // end script here
            }
            $returnValue = giveAttendanceToOneStudent($fileToWorkWith, $studentRow);
            if($returnValue == 1){
                echo "Attendance taken successfully";
            }else{
                echo "Something went wrong... Please do it manually";
            }
        } 
        else {
            // Assume btnSubmit
            echo "Something went wrong maybe...";
        }
    }
?>