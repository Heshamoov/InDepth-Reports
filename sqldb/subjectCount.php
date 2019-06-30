<?php

include ('../config/dbConfig.php');


$years = $_REQUEST["years"];
$grades = $_REQUEST["grades"];
$batches = $_REQUEST["batches"];
$subject = $_REQUEST["subject"];
$gender = $_REQUEST["gender"];
$terms = $_REQUEST["terms"];
$category = $_REQUEST["category"];



$sql = "SELECT students.admission_no moe, students.first_name name, students.gender gender, batches.name batch_name, courses.course_name grade,exam_groups.name exam_name, exam_scores.marks marks,subjects.name subject_name\n"
        . "\n"
        . "FROM ((((((((\n"
        . "students\n"
        . "INNER JOIN batches ON students.batch_id = batches.id) \n"
        . "	LEFT JOIN academic_years ON batches.academic_year_id = academic_years.id)     \n"
        . "LEFT JOIN student_categories on students.student_category_id = student_categories.id ) \n "
        . "	\n"
        . "	INNER JOIN courses ON batches.course_id = courses.id)     \n"
        . "\n"
        . "	INNER JOIN exam_groups ON students.batch_id = exam_groups.batch_id)\n"
        . "\n"
        . "\n"
        . "	INNER JOIN exams ON exam_groups.id = exams.exam_group_id)    \n"
        . "	\n"
        . "	INNER JOIN exam_scores\n"
        . "	ON students.id = exam_scores.student_id\n"
        . "       AND exam_scores.exam_id = exams.id)\n"
        . "\n"
        . "	INNER JOIN subjects ON exams.subject_id = subjects.id)";

if ($years == "" and $terms == "" and $grades == "" and $batches == "" and $gender == "" and $category == "") {
    $sql = $sql . " WHERE subjects.name LIKE '$subject%'";
} else {


    $sql = $sql . " WHERE $years $grades $batches $terms   $gender  $category   AND subjects.name LIKE '$subject%'";
}
//        echo $sql;

$result = $conn->query($sql);
$rowcount = mysqli_num_rows($result);
echo $rowcount;

$conn->close();
