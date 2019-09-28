<?php
include ('../config/dbConfig.php');
$terms = $_REQUEST["terms"];
$grades = $_REQUEST["grades"];
$years = $_REQUEST["years"];
$batches = $_REQUEST["batches"];
$subjects = $_REQUEST["subject"];
$gender = $_REQUEST["gender"];
$category = $_REQUEST["category"];
$min = $_REQUEST["min"];
$max = $_REQUEST["max"];

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

if ($terms == "" and $grades == "" and $batches == "" and $years == "" and $gender == "" and $subjects == "" and $category == "")
    $sql = $sql . " WHERE academic_years.name = '2018 - 2019' ".
        " AND courses.course_name = 'GR 1' ".
        " AND exam_groups.name = 'Term1-2019' ".
        " AND batches.name = 'A2019' ".
        " AND exam_scores.marks BETWEEN $min AND $max";
else 
    $sql = $sql . "WHERE  $years $grades  $batches  $terms    $gender $category $subjects AND (exam_scores.marks BETWEEN $min AND $max) ";
//        echo $sql;
$result = $conn->query($sql);
$rowcount = mysqli_num_rows($result);
echo $rowcount;

$conn->close();
