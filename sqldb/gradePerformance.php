<?php

include ('../config/dbConfig.php');

$years = $_REQUEST["years"];
$terms = $_REQUEST["terms"];
$grades = $_REQUEST["grades"];
$batches = $_REQUEST["batches"];
$subjects = $_REQUEST["subject"];
$gender = $_REQUEST["gender"];
$category = $_REQUEST["category"];


$sql = "SELECT
            students.admission_no 'id',
            students.last_name 'name', CONCAT(courses.course_name, '-',batches.name) as 'grade',
            exam_groups.name 'exam_name', exam_scores.marks 'marks', subjects.name 'subject_name' \n"
        . "FROM (((((((( "
        . "academic_years "
        . "INNER JOIN batches ON academic_years.id = batches.academic_year_id) "
        . "INNER JOIN courses ON batches.course_id = courses.id) "
        . "INNER JOIN exam_groups ON batches.id = exam_groups.batch_id) "
        . "INNER JOIN exams ON exam_groups.id = exams.exam_group_id) "
        . "INNER JOIN subjects ON exams.subject_id = subjects.id) "
        . "INNER JOIN exam_scores ON exams.id = exam_scores.exam_id) "
        . "INNER JOIN students ON exam_scores.student_id = students.id) "
        . "LEFT JOIN student_categories ON students.student_category_id = student_categories.id) \n";

if ($years == "" and $terms == "" and $grades == "" and $batches == "" and $gender == "" and $category == "" and $subjects == "")
{
    $sql = $sql . "WHERE academic_years.name = '2018 - 2019' "
                . "AND courses.course_name = 'GR 1' "
                . "AND exam_groups.name = 'Term1-2019' "
                . "AND batches.name = 'A2019' "
                . "ORDER BY students.id ASC, exam_groups.name";
    
} else {
    $sql = $sql . "WHERE $years $grades $batches $terms  $gender $category $subjects "
                . " GROUP BY
                        exam_groups.name,
                        subjects.name
                    ORDER BY
                        courses.course_name,
                        students.last_name,
                        subjects.name,
                        exam_groups.name";
}

echo $sql;
$result = $conn->query($sql);
$rownumber = 1;
if ($result->num_rows > 0) {
    echo "<thead><tr id =out class= w3-custom  ><th>#</th><th>Name</th>" .
    "<th>Term</th><th>Grade</th>" .
    "<th>Subject</th><th>Score</th></tr></thead><tbody>";

    $first_line = true;
    
    while ($row = $result->fetch_assoc()) {
        $current_id = $row["id"];
        if ($first_line || $current_id != $prev_id) 
        {
            if (!$first_line) echo "</tr>";
            echo "<tr><td>" . $rownumber++ . "</td><td>" . $row["name"] . "</td><td>";
            echo $row["exam_name"] . "</td><td>" . $row["grade"] . "</td><td>" . $row["subject_name"] .
            "</td><td>" . $row["marks"] . "</td>";
            $prev_id = $row["id"];
            $first_line = false;
        } else if ($current_id == $prev_id) {
            echo "<td>" . $row["subject_name"] . "</td><td>" . $row["marks"] . "</td>";
        }

    }
    echo "</tbody>";
} else {
    echo "No Data Found! Try another search.";
}

$conn->close();




