<?php

require 'vendor/autoload.php';

$macAddressFileName = './mac.txt'; // TODO : THIS WILL BE RETRIVED FROM ESP8266 -> CHANGE IP TO
$macAddressFileName = 'http://192.168.135.135/macs.txt'; // TODO: USE THIS FOR FINAL PROJECT VERSION
// $fileNameWithExtention = 'SubjectName_Section_A.xlsx'; used for test purpose
$foundMac = false;
$rowNumberOfMatchedMac = '';
$macFileLines = file($macAddressFileName, FILE_IGNORE_NEW_LINES);

/**
 * A method to return spreadsheet 
 */
function getSpreadSheetOfFile($fileToWorkWith){
    $reader = PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fileToWorkWith);
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($fileToWorkWith);
    return $spreadsheet;
}


/**
 * A method to search for given mac address
 * Might modify it in future to return row number of the mac adress found
 */
function searchMacAddress($macAddress, $fileNameWithExtention){
    global $rowNumberOfMatchedMac, $foundMac;

    $searchTermForMacAddress = $macAddress;
    $spreadsheet = getSpreadSheetOfFile($fileNameWithExtention);

    $sheet = $spreadsheet->getActiveSheet();
    $columnName = 'D';
    for($row=1; $row <= $sheet->getHighestDataRow(); $row++){
        $currentMacCell = $sheet->getCell($columnName.$row); // Get the current Mac cell
        if($currentMacCell->getValue() == $searchTermForMacAddress){ // Check if it matches with search
            $foundMac = true;
            $rowNumberOfMatchedMac = $row;
            break;
        }
    }
}

/**
 * A method to search for given student by their id
 * @param string $studentID
 * @param string $fileNameWithExtension
 * @return int $row 
 * Returns the row number
 */
function searchStudentByID($studentID, $fileNameWithExtention){

    $spreadsheet = getSpreadSheetOfFile($fileNameWithExtention);

    $sheet = $spreadsheet->getActiveSheet();
    $columnName = 'A';
    for($row=1; $row <= $sheet->getHighestDataRow(); $row++){
        $currentMacCell = $sheet->getCell($columnName.$row); // Get the current Mac cell
        if($currentMacCell->getValue() == $studentID){ // Check if it matches with search
            return $row;
        }
    }

    return -1;
}

/**
 * A method which prints just info of person whose mac addres was found
 */
function printInfoOfPersonWhoseMacWasFound($fileNameWithExtention, $rowNumberOfMatchedMac){
    if($rowNumberOfMatchedMac == '')
        return;

    $spreadsheet = getSpreadSheetOfFile($fileNameWithExtention);
    // Get the value of the person whose mac was searched
    $highestColumn = $spreadsheet->getActiveSheet()->getHighestDataColumn($rowNumberOfMatchedMac);
    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
    for($col=1; $col <= $highestColumnIndex; ++$col){
        $value = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($col, $rowNumberOfMatchedMac)->getValue();
        echo $value;
        echo '<hr>';
    }
}

/**
 * This method will be called ONCE only everyday before attendance to generate a new column
 * with current date and initialize every row with 0 for that column
 */
function addCurrentDateColumnInExcelFile($file){
    $fileToWorkWith = $file;
    $spreadsheet = getSpreadSheetOfFile($fileToWorkWith);
    $highestRow = $spreadsheet->getActiveSheet()->getHighestDataRow();
    $highestColumn = $spreadsheet->getActiveSheet()->getHighestDataColumn();

    $spreadsheet =  PhpOffice\PhpSpreadsheet\IOFactory::load($fileToWorkWith);

    $lastColumnTitle = $spreadsheet->getActiveSheet()->getCell($highestColumn.'1', false);
    if($lastColumnTitle == date("Y/m/d")){
        //echo "Column Already exists";
        return;

    }else{
        $highestColumn++; // Increment Column value

        $spreadsheet->getActiveSheet()->insertNewColumnBefore($highestColumn, 1);

        $spreadsheet->getActiveSheet()->setCellValue($highestColumn.'1', date("Y/m/d"));
        for($row=2; $row <= $highestRow; $row++){
            $spreadsheet->getActiveSheet()->setCellValue($highestColumn.$row, '0');    
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($fileToWorkWith);
    }
}


function giveAttendanceToEachStudent($file){
    global $rowNumberOfMatchedMac;

    $fileToWorkWith = $file;
    $spreadsheet = getSpreadSheetOfFile($fileToWorkWith);
    $highestRow = $spreadsheet->getActiveSheet()->getHighestDataRow();
    $highestColumn = $spreadsheet->getActiveSheet()->getHighestDataColumn();

    $spreadsheet =  PhpOffice\PhpSpreadsheet\IOFactory::load($fileToWorkWith);

    $spreadsheet->getActiveSheet()->setCellValue($highestColumn.$rowNumberOfMatchedMac, '1');
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save($fileToWorkWith);
}

/**
 * Used for problematic cause
 */
function giveAttendanceToOneStudent($file, $rowNumber){

    $spreadsheet = getSpreadSheetOfFile($file);
    $highestRow = $spreadsheet->getActiveSheet()->getHighestDataRow();
    $highestColumn = $spreadsheet->getActiveSheet()->getHighestDataColumn();

    $spreadsheet =  PhpOffice\PhpSpreadsheet\IOFactory::load($file);

    $spreadsheet->getActiveSheet()->setCellValue($highestColumn.$rowNumber, '1');
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save($file);

    return 1;
}

/**
 * The function that is supposed to run when the text file contains all the mac address for the students to give attendace to
 */
function giveAttendanceToAllAddressFromTextFileContainingMac($file){
    global $macFileLines, $foundMac, $rowNumberOfMatchedMac;
    $fileToWorkWith = $file;
    
    foreach ($macFileLines as $line_num => $line) {
        searchMacAddress($line, $fileToWorkWith);
        if($foundMac){
            giveAttendanceToEachStudent($fileToWorkWith);
            $rowNumberOfMatchedMac = '';
            $foundMac = false;
        }      
    }
}

?>