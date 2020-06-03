
<?php

include('../../../config/dbConfig.php');


$year = "2019 - 2020";
$grade = $_REQUEST["Grade"];
$gender = $_REQUEST["Gender"];
$nationality = $_REQUEST["Nationality"];
$student = $_REQUEST["Student"];
$view = $_REQUEST["View"];


$sql = "SELECT students.id, students.last_name name, concat(courses.course_name, ' - ', batches.name) as Grade,
       exam_groups.name term, round(exam_scores.marks) marks, subjects.name subject
FROM ((((((((
    academic_years
    INNER JOIN batches ON academic_years.id = batches.academic_year_id)
    INNER JOIN courses ON batches.course_id = courses.id)
    INNER JOIN exam_groups ON batches.id = exam_groups.batch_id)
    INNER JOIN exams ON exam_groups.id = exams.exam_group_id)
    INNER JOIN subjects ON exams.subject_id = subjects.id)
    INNER JOIN exam_scores ON exams.id = exam_scores.exam_id)
    INNER JOIN students ON exam_scores.student_id = students.id)
    LEFT JOIN student_categories ON students.student_category_id = student_categories.id)

WHERE academic_years.name = '2019 - 2020'
  AND courses.course_name = 'GR 1'

ORDER BY name, subject, term DESC;";

$subjects_query = "SELECT distinct (subjects.name)
FROM ((((((((
    academic_years
    INNER JOIN batches ON academic_years.id = batches.academic_year_id)
    INNER JOIN courses ON batches.course_id = courses.id)
    INNER JOIN exam_groups ON batches.id = exam_groups.batch_id)
    INNER JOIN exams ON exam_groups.id = exams.exam_group_id)
    INNER JOIN subjects ON exams.subject_id = subjects.id)
    INNER JOIN exam_scores ON exams.id = exam_scores.exam_id)
    INNER JOIN students ON exam_scores.student_id = students.id)
    LEFT JOIN student_categories ON students.student_category_id = student_categories.id)

WHERE academic_years.name = '2019 - 2020'
  AND courses.course_name = 'GR 1'

ORDER BY students.last_name, subjects.name, exam_groups.name DESC";

$count_subjects = "SELECT DISTINCT (subjects.name)
FROM ((((((((
    academic_years
    INNER JOIN batches ON academic_years.id = batches.academic_year_id)
    INNER JOIN courses ON batches.course_id = courses.id)
    INNER JOIN exam_groups ON batches.id = exam_groups.batch_id)
    INNER JOIN exams ON exam_groups.id = exams.exam_group_id)
    INNER JOIN subjects ON exams.subject_id = subjects.id)
    INNER JOIN exam_scores ON exams.id = exam_scores.exam_id)
    INNER JOIN students ON exam_scores.student_id = students.id)
    LEFT JOIN student_categories ON students.student_category_id = student_categories.id)

WHERE academic_years.name = '2019 - 2020'
  AND courses.course_name = 'GR 1'";


$number_of_subjects = $conn->query($count_subjects);
$subjects_count = mysqli_num_rows($number_of_subjects);

//echo $subjects_query;
$subjects = $conn->query($subjects_query);

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<thead>
            <tr><th>Grade: " . $grade . "</th><th colspan='". strval($subjects_count  * 2 + 1) ."'>Academic Year: 2019 - 2020</th></tr>" .
            "<tr><th rowspan='2'></th><th rowspan='2'>Courses</th>";
    for ($i = 1; $i <= $subjects_count; $i++) {
        echo "<td colspan='2'>" . $i . "</td>";
    }
    echo "</tr>";
    while ($subject = $subjects->fetch_assoc()) {
        echo "<td colspan='2'>" . $subject['name'] . "</td>";
    }
            echo "</tr></thead><tbody>";
            echo "<tr><th>Student Name</th><th>Exams</th>";
            for ($i = 1; $i <= $subjects_count; $i++) {
                echo "<td>C.E.1</td><td>T.E.1</td>";
            }
    echo "</tr></thead><tbody>";

    $prev_id = 0;  $first_line = true; $new_line = false;
    echo "<tr>";
    while ($row = $result->fetch_assoc()) {
        if ($row['id'] != $prev_id) { // New Student
            if ($first_line)
                { $first_line = false; $prev_id = $row['id'];}
            else
                {if ($row['id'] != $prev_id) echo "</tr><tr>"; $prev_id = $row['id'];}

            echo "<td colspan='2'>" . $row['name'] . "</td>";
            echo "<td>" . $row['marks'] . "</td>";
        } else
            {echo "<td>" . $row['marks'] . "</td>";}
    }
    echo "</tbody>";
} else {
    echo "No Data Found! Try another search.";
}

$conn->close();