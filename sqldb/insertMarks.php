<?php

include ('../config/dbConfig.php');


$q = "INSERT INTO marks (moe, exam_group, grade, batch, subject, mark) VALUES " . $_REQUEST["q"];
echo $q;
if ($conn->query($q) === TRUE)
    echo "\nStudents marks inserted.\n";
else
    echo "\nError: " . $conn->error . "\n";

$deleteDuplicatdRows = "DELETE t1 FROM marks t1 " .
        "INNER JOIN marks t2 " .
        "WHERE t1.Serial > t2.Serial " .
        "AND t1.moe = t2.moe AND t1.Subject = t2.Subject AND t1.Exam_Group = t2.Exam_Group";

//    echo $deleteDuplicatdRows;
if ($conn->query($deleteDuplicatdRows) === TRUE)
    echo "Duplication deleted.\n";
else
    echo "\nError: " . $conn->error . "\n";

$countMarks = "SELECT mark FROM marks";
$result = $conn->query($countMarks);
$rowcount = mysqli_num_rows($result);
echo $rowcount . " Mark inserted.\n";

$conn->close();
