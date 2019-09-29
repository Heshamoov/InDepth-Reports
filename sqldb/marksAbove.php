<?php	
include ('../config/dbConfig.php');	
$year = $_REQUEST["year"];	
$term = $_REQUEST["term"];	
$grade = $_REQUEST["grade"];	
$subject = $_REQUEST["subject"];	
$gender = $_REQUEST["gender"];	
$category = $_REQUEST["category"];	
$min = $_REQUEST["min"];	
$section = $_REQUEST["section"];

if ($gender === 'Both')	
    $gender = "";	
else
    if ($gender === 'Boys')	
        $gender = " AND (students.Gender = 'm') ";	
    else
        $gender = " AND (students.Gender = 'f') ";	

$sql = "SELECT * FROM (((((((( "
        . "academic_years "
        . "INNER JOIN batches ON academic_years.id = batches.academic_year_id) "
        . "INNER JOIN courses ON batches.course_id = courses.id) "
        . "INNER JOIN exam_groups ON batches.id = exam_groups.batch_id) "
        . "INNER JOIN exams ON exam_groups.id = exams.exam_group_id) "
        . "INNER JOIN subjects ON exams.subject_id = subjects.id) "
        . "INNER JOIN exam_scores ON exams.id = exam_scores.exam_id) "
        . "INNER JOIN students ON exam_scores.student_id = students.id) "
        . "LEFT JOIN student_categories ON students.student_category_id = student_categories.id) "
        . "WHERE ((academic_years.name = '$year') AND (exam_groups.name = '$term' ) AND (courses.course_name = '$grade') "	
        . "AND (exam_scores.marks >= $min )  $subject $section $category  $gender )";	
 // echo $sql;	
$result = $conn->query($sql);	
$rowcount = mysqli_num_rows($result);	
echo $rowcount;	
$conn->close();