<?php

include ('../config/dbConfig.php');

//$year = $_REQUEST["year"];
$term = $_REQUEST["term"];
$grade = $_REQUEST["grade"];
$subject = $_REQUEST["subject"];
$gender = $_REQUEST["gender"];
$category = $_REQUEST["category"];
$min = $_REQUEST["min"];
$section = $_REQUEST["section"];

//echo $min;

if ($gender === 'Both')
    $gender = "";
else if ($gender === 'Boys')
    $gender = " AND (students.Gender = 'm') ";
else if ($gender === 'Girls')
    $gender = " AND (students.Gender = 'f') ";








$sql = "SELECT students.admission_no moe, students.first_name name, students.gender gender, batches.name batch_name, courses.course_name grade,exam_groups.name exam_name, exam_scores.marks marks,subjects.name subject_name\n"
        . "\n"
        . "FROM (((((((\n"
        . "students\n"
        . "LEFT JOIN student_categories on students.student_category_id = student_categories.id ) \n "
        . "INNER JOIN batches ON students.batch_id = batches.id) \n"
        . "	INNER JOIN courses ON batches.course_id = courses.id)     \n"
        . "	INNER JOIN exam_groups ON students.batch_id = exam_groups.batch_id)\n"
        . "	INNER JOIN exams ON exam_groups.id = exams.exam_group_id)    \n"
        . "	\n"
        . "	INNER JOIN exam_scores\n"
        . "	ON students.id = exam_scores.student_id\n"
        . "       AND exam_scores.exam_id = exams.id)\n"
        . "	INNER JOIN subjects ON exams.subject_id = subjects.id) "
        . "    WHERE ((exam_groups.name = '$term' ) AND (courses.course_name = '$grade') "
        . "    AND (exam_scores.marks >= $min )  $subject $section $category  $gender )";

// echo $sql;

$result = $conn->query($sql);
$rowcount = mysqli_num_rows($result);
echo $rowcount;

$conn->close();
