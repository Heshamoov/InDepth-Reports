<?php

include ('../config/dbConfig.php');

$years = $_REQUEST["years"];
$terms = $_REQUEST["terms"];
$grades = $_REQUEST["grades"];
$batches = $_REQUEST["batches"];
$subjects = $_REQUEST["subjects"];
$gender = $_REQUEST["gender"];
$category = $_REQUEST["category"];
//    echo $subjects;
//    $sql =  " SELECT moe, name, students.gender," .
//            " marks.exam_group, marks.grade, marks.batch, marks.subject, marks.mark " .
//            " FROM marks" .
//            " INNER JOIN students ON marks.moe = students.admission_no " .
//            " WHERE $terms $grades $batches $gender $subjects";

if ($years == "" and $terms == "" and $grades == "" and $batches == "" and $gender == "" and $category == "" and $subjects == "") {

    $sql = "SELECT students.admission_no moe, students.first_name name, students.gender gender, batches.name batch_name, courses.course_name grade,exam_groups.name exam_name, exam_scores.marks marks,subjects.name subject_name\n"
            . "\n"
            . "FROM ((((((((\n"
            . "students\n"
            . "LEFT JOIN student_categories on students.student_category_id  = student_categories.id) \n "
            . "INNER JOIN batches ON students.batch_id = batches.id) \n"
            . "	LEFT JOIN academic_years ON batches.academic_year_id = academic_years.id)     \n"
            . "	INNER JOIN courses ON batches.course_id = courses.id)     \n"
            . "	INNER JOIN exam_groups ON students.batch_id = exam_groups.batch_id)\n"
            . "	INNER JOIN exams ON exam_groups.id = exams.exam_group_id)    \n"
            . "	INNER JOIN exam_scores\n"
            . "	ON students.id = exam_scores.student_id\n"
            . "       AND exam_scores.exam_id = exams.id)\n"
            . "	INNER JOIN subjects ON exams.subject_id = subjects.id) ORDER BY students.id ASC, exam_groups.name";
} else {
    $sql = "SELECT students.admission_no moe, students.first_name name, students.gender gender, batches.name batch_name, courses.course_name grade,exam_groups.name exam_name, exam_scores.marks marks,subjects.name subject_name\n"
            . "FROM ((((((((\n"
            . "students\n"
            . "LEFT JOIN student_categories on students.student_category_id = student_categories.id) \n "
            . "INNER JOIN batches ON students.batch_id = batches.id) \n"
            . "	LEFT JOIN academic_years ON batches.academic_year_id = academic_years.id)     \n"
            . "	INNER JOIN courses ON batches.course_id = courses.id)     \n"
            . "	INNER JOIN exam_groups ON students.batch_id = exam_groups.batch_id)\n"
            . "	INNER JOIN exams ON exam_groups.id = exams.exam_group_id)    \n"
            . "	\n"
            . "	INNER JOIN exam_scores\n"
            . "	ON students.id = exam_scores.student_id\n"
            . "       AND exam_scores.exam_id = exams.id)\n"
            . "	INNER JOIN subjects ON exams.subject_id = subjects.id) WHERE  $terms $years $grades $batches $gender $category $subjects"
            . " ORDER BY students.id ASC, exam_groups.name ";
}




//echo $sql;
$result = $conn->query($sql);
$rownumber = 1;
if ($result->num_rows > 0) {
    echo "<thead><tr id =out class= w3-custom  ><th>SI No.</th><th>MOE</th>" .
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
        "</td><td>" . $row["marks"] . "</td></tr>";
    }
    echo "</tbody>";
} else
    echo "Data Not Found, try to import it to DB";

$conn->close();




