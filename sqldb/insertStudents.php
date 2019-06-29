<?php
include ('../config/dbConfig.php');


$q = "INSERT INTO students (moe, name, grade, batch) VALUES " . $_REQUEST["q"];
echo $q;
if ($conn->query($q) === TRUE)
    echo "Students information inserted successfully.\n";
else
    echo "\nError: " . $conn->error . "\n";

$countStudents = "SELECT moe FROM students";
$result = $conn->query($countStudents);
$rowcount = mysqli_num_rows($result);
echo $rowcount . " Records inserted.\n";

$conn->close();
