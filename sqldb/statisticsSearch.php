<?php

include ('../config/dbConfig.php');

$years = $_REQUEST["years"];
$terms = $_REQUEST["terms"];
$grades = $_REQUEST["grades"];
$batches = $_REQUEST["batches"];
$subjects = $_REQUEST["subject"];
$gender = $_REQUEST["gender"];
$category = $_REQUEST["category"];


$sql = "SELECT students.admission_no moe, students.first_name name, students.gender gender, batches.name batch_name, "
     . "courses.course_name grade,exam_groups.name exam_name, exam_scores.marks marks, subjects.name subject_name \n"
     . "FROM (((((((( "
        . "academic_years "
        . "INNER JOIN batches ON academic_years.id = batches.academic_year_id) "
        . "INNER JOIN courses ON batches.course_id = courses.id) "
        . "INNER JOIN exam_groups ON batches.id = exam_groups.batch_id) "
        . "INNER JOIN exams ON exam_groups.id = exams.exam_group_id) "
        . "INNER JOIN subjects ON exams.subject_id = subjects.id) "
        . "INNER JOIN exam_scores ON exams.id = exam_scores.exam_id) "
        . "INNER JOIN students ON exam_scores.student_id = students.id) "
        . "LEFT JOIN student_categories ON students.student_category_id = student_categories.id) ";

if ($years == "" and $terms == "" and $grades == "" and $batches == "" and $gender == "" and $category == "" and $subjects == "")
{
    $sql = $sql . "WHERE academic_years.name = '2018 - 2019' "
                . "AND courses.course_name = 'GR 1' "
                . "AND exam_groups.name = 'Term1-2019' "
                . "AND batches.name = 'A2019' "
                . "ORDER BY students.id ASC, exam_groups.name";
    
} else {
    $sql = $sql . "WHERE $years $grades $batches $terms  $gender $category $subjects "
                . " ORDER BY students.id ASC, exam_groups.name ";
}

//echo $sql;
$result = $conn->query($sql);
$rownumber = 1;
if ($result->num_rows > 0) {
    echo "<thead><tr id =out class= w3-custom  ><th>#</th><th>MOE</th>" .
    "<th>Name</th>" .
    "<th>Gender</th>" .
    "<th>Exam Group</th>" .
    "<th>Grade</th><th>Section</th>" .
    "<th>Subject</th><th>Score</th></tr></thead><tbody>";


    while ($row = $result->fetch_assoc()) {

        echo "<tr><td>" . $rownumber++ . "</td><td>" . $row["moe"] .
        "</td><td>" . $row["name"] . "</td><td>";

        if ($row["gender"] == 'f') {
            $row["gender"] = "F";
        } else {
            $row["gender"] = "M";
        }
        echo $row["gender"] . "</td><td>" . $row["exam_name"] . "</td><td>" . $row["grade"] .
        "</td><td>" . $row["batch_name"] . "</td><td>" . $row["subject_name"] .
        "</td><td>" . $row["marks"] . " \n </td></tr>";
    }
    echo "</tbody>";
} else {
    echo "No Data Found! Try another search.";
}

$conn->close();




