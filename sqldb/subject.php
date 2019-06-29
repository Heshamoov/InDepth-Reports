<?php

include ('../config/dbConfig.php');


$grades = $_REQUEST["grades"];
$batches = $_REQUEST["batches"];
$subjects = $_REQUEST["subjects"];
$term = $_REQUEST["term"];

$sql = " SELECT students.MOE, students.Name, students.Gender," .
        " marks.Exam_Group, marks.Grade, marks.Batch, marks.Subject, marks.Mark " .
        " FROM marks" .
        " INNER JOIN students ON students.MOE = marks.MOE" .
        " WHERE $grades $batches $subjects $term";
echo $sql;
$result = $conn->query($sql);
$rownumber = 1;
if ($result->num_rows > 0) {
    echo "<thead><tr class= w3-blue ><th>No</th><th>MOE</th>" .
    "<th>Name</th><th>Gender</th><th>Exam Group</th>" .
    "<th>Grade</th><th>Batch</th>" .
    "<th>Subject</th><th>Mark</th></tr></thead><tbody>";

    while ($row = $result->fetch_assoc())
        echo "<tr><td>" . $rownumber++ . "</td><td>" . $row["MOE"] .
        "</td><td>" . $row["Name"] . "</td><td>" . $row["Gender"] .
        "</td><td>" . $row["Exam_Group"] . "</td><td>" . $row["Grade"] .
        "</td><td>" . $row["Batch"] . "</td><td>" . $row["Subject"] .
        "</td><td>" . $row["Mark"] . "</td></tr>";
    echo "</tbody>";
} else
    echo "Data Not Found, try to import it to DB";

$conn->close();
