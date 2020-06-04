
<?php

include('../../../config/dbConfig.php');


$year = "2019 - 2020";
$grade = $_REQUEST["Grade"];
$term = $_REQUEST["Term"];

$sql = "SELECT students.id, students.last_name name, concat(courses.course_name, ' - ', batches.name) as grade,
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
  AND courses.course_name = '$grade' ";

if ($term == 'Term 1' OR $term == 'Term 1 - Class Evaluation') $condition = " AND exam_groups.name in ('Term 1', 'Term 1 - Class Evaluation') ";
if ($term == 'Term 2' OR $term == 'Term 2 - Class Evaluation') $condition = " AND exam_groups.name in ('Term 2', 'Term 2 - Class Evaluation') ";
if ($term == 'Term 3' OR $term == 'Term 3 - Class Evaluation') $condition = " AND exam_groups.name in ('Term 3', 'Term 3 - Class Evaluation') ";
$order = " ORDER BY batches.name, name, subject, term DESC;";

$sql .= $condition . $order;
//echo $sql;


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
  AND courses.course_name = '$grade'" . $condition . "ORDER BY batches.name, students.last_name, subjects.name, exam_groups.name";

//echo $subjects_query;

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
  AND courses.course_name = '$grade'" . $condition;


$number_of_subjects = $conn->query($count_subjects);
$subjects_count = mysqli_num_rows($number_of_subjects);

//echo $subjects_query;
$subjects = $conn->query($subjects_query);

//echo $sql;
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
    }  echo "<tr><th>Student Name</th><th>Grade</th>";


    if ($term == 'Term 1') {
        for ($i = 1; $i <= $subjects_count; $i++)
            echo '<td style="text-align: center">C.E.1</td><td style="text-align: center">T.E.1</td>';
    } elseif ($term == 'Term 2') {
        for ($i = 1; $i <= $subjects_count; $i++)
            echo '<td style="text-align: center">C.E.2</td><td style="text-align: center">T.E.2</td>';
    } elseif ($term == 'Term 3') {
        for ($i = 1; $i <= $subjects_count; $i++)
            $html2 .= '<td style="text-align: center">C.E.3</td><td style="text-align: center">T.E.3</td>';
    }
    echo "</tr></thead><tbody>";

    $prev_id = 0;  $first_line = true; $new_line = false;
    echo "</tr></thead><tbody>";
    while ($row = $result->fetch_assoc()) {
        if ($row['id'] != $prev_id) { // New Student
            if ($first_line)
                { $first_line = false; $prev_id = $row['id'];}
            else
                {if ($row['id'] != $prev_id) echo "</tr><tr>"; $prev_id = $row['id'];}

            echo "<td colspan='1'>" . $row['name'] . "</td><td>" . $row['grade'] . "</td>";
            echo "<td>" . $row['marks'] . "</td>";
        } else
            {echo "<td>" . $row['marks'] . "</td>";}
    }
    echo "</tbody>";
} else {
    echo "No Data Found! Try another search.";
}

$conn->close();