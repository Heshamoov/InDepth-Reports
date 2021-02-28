<?php

include('../../../config/dbConfig.php');


$grade = $_REQUEST["Grade"];
$gender = $_REQUEST["Gender"];
$nationality = $_REQUEST["Nationality"];
$student = $_REQUEST["Student"];
$view = $_REQUEST["View"];
echo $view;

$YearsA = array("2016 - 2017", "2017 - 2018", "2018 - 2019", "2019 - 2020", "2020 - 2021");
$TermsA = array();
if ($_REQUEST["Term1"] != "") $TermsA[0] = $_REQUEST["Term1"];
if ($_REQUEST["Term2"] != "") $TermsA[1] = $_REQUEST["Term2"];
if ($_REQUEST["Term3"] != "") $TermsA[2] = $_REQUEST["Term3"];
if ($_REQUEST["Term4"] != "") $TermsA[3] = $_REQUEST["Term4"];
if ($_REQUEST["Term5"] != "") $TermsA[4] = $_REQUEST["Term5"];

$GradesA = array();
if ($_REQUEST["Grade1"] != "") $GradesA[0] = $_REQUEST["Grade1"];
if ($_REQUEST["Grade2"] != "") $GradesA[1] = $_REQUEST["Grade2"];
if ($_REQUEST["Grade3"] != "") $GradesA[2] = $_REQUEST["Grade3"];
if ($_REQUEST["Grade4"] != "") $GradesA[3] = $_REQUEST["Grade4"];
if ($_REQUEST["Grade5"] != "") $GradesA[4] = $_REQUEST["Grade5"];

$sql = "
SELECT academic_years.name                                                                year,
       subjects.name                                                                      subject,
       subjects.code                                                                      subject_code,
       students.last_name,
       courses.course_name                                                                grade,
       batches.name                                                                       section,
       exam_groups.name                                                                   exam,
       exam_scores.marks                                                                  mark,
       students.birth_place,
       COUNT(IF(exam_scores.marks IS NOT NULL AND exam_scores.marks > 0, 1, NULL))        'Total',
       COUNT(students.id)        'TotalWithAbsent',
       COUNT(IF(exam_scores.marks >= 65 AND exam_scores.marks IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65',
       ROUND(COUNT(IF(exam_scores.marks >= 65 AND exam_scores.marks IS NOT NULL, 1, NULL)) /
             COUNT(IF(exam_scores.marks IS NOT NULL AND exam_scores.marks > 0, 1, NULL)) * 100,
             0)                                                                        AS 'MoreOrEqual65P',
       COUNT(IF(exam_scores.marks >= 75 AND exam_scores.marks IS NOT NULL, 1, NULL)) AS 'MoreOrEqual75',
       ROUND(COUNT(IF(exam_scores.marks >= 75 AND exam_scores.marks IS NOT NULL, 1, NULL)) /
             COUNT(IF(exam_scores.marks IS NOT NULL AND exam_scores.marks > 0, 1, NULL)) * 100,
             0)                                                                        AS 'MoreOrEqual75P',
       SUM(exam_scores.marks) AS 'SUM',
       ROUND(AVG(exam_scores.marks),2) AS 'AVGR',
       ROUND(SUM(exam_scores.marks)/COUNT(students.id),2) AS 'AVGRA'
FROM academic_years
         INNER JOIN batches ON academic_years.id = batches.academic_year_id
         INNER JOIN courses ON batches.course_id = courses.id
         INNER JOIN exam_groups ON batches.id = exam_groups.batch_id
         INNER JOIN exams ON exam_groups.id = exams.exam_group_id
         INNER JOIN subjects ON exams.subject_id = subjects.id
         INNER JOIN exam_scores ON exams.id = exam_scores.exam_id
         INNER JOIN students ON exam_scores.student_id = students.id
         LEFT JOIN student_categories ON students.student_category_id = student_categories.id 
         WHERE ";

for ($i = 0; $i < 5; $i++) {
    $sql .= "(academic_years.name = '$YearsA[$i]' AND (REPLACE(exam_groups.name, ' ','') = REPLACE('$TermsA[$i]', ' ', '')) AND courses.course_name = '$GradesA[$i]') ";
    if ($i < 4)
        $sql .= " OR ";
}

$sql .= "
GROUP BY year, subject_code
ORDER BY subject_code, year;
";

//echo $sql;

$GradeHeader = true;

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $year_index = 0;
    $subject_printed = false;
    while ($row = $result->fetch_assoc()) {

        if ($year_index == 0) {
            echo "<tr><td>" . $row['subject'] . "</td>";
            $subject_printed = true;
        }

        if (!$subject_printed) {
            echo "<td>" . $row['subject'] . "</td>";
            $subject_printed = true;
        }

        while ($YearsA[$year_index] != $row['year']) {
            echo "<td>-</td>";
            $year_index++;
            if ($year_index == 5) {
                echo "</tr>";
                $year_index = 0;
                echo "<td>" . $row['subject'] . "</td>";
                $subject_printed = true;
            }
        }

        if ($view == 'Attainment')
            if ($row["MoreOrEqual75P"] >= 75)                                    // Outstanding
                echo "<td class='w3-container w3-text-green w3-hover-green'>Outstanding</td>";
            elseif ($row["MoreOrEqual75P"] >= 61 and $row["MoreOrEqual75P"] < 75)       // Very Good
                echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good</td>";
            elseif ($row["MoreOrEqual75P"] >= 50 and $row["MoreOrEqual75P"] < 61)       // Good
                echo "<td class='w3-container w3-text-lime w3-hover-lime'>Good</td>";
            elseif ($row["MoreOrEqual65P"] >= 65)                                 // Acceptable
                echo "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable</td>";
            elseif ($row["MoreOrEqual65P"] == null or $row["MoreOrEqual65P"] == 0)
                echo "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>";
            else                                                          // Weak
                echo "<td class='w3-container w3-text-red w3-hover-red'>Weak</td>";
        elseif ($view == 'Percentage')
            if ($row["MoreOrEqual75P"] >= 75)                                    // Outstanding
                echo "<td class='w3-container w3-text-green w3-hover-green'>" . $row["MoreOrEqual75P"] . "%</td>";
            elseif ($row["MoreOrEqual75P"] >= 61 and $row["MoreOrEqual75P"] < 75)       // Very Good
                echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>" . $row["MoreOrEqual75P"] . "%</td>";
            elseif ($row["MoreOrEqual75P"] >= 50 and $row["MoreOrEqual75P"] < 61)       // Good
                echo "<td class='w3-container w3-text-lime w3-hover-lime'>" . $row["MoreOrEqual75P"] . "%</td>";
            elseif ($row["MoreOrEqual65P"] >= 65)                                 // Acceptable
                echo "<td class='w3-container w3-text-orange w3-hover-orange'>" . $row["MoreOrEqual65P"] . "%</td>";
            elseif ($row["MoreOrEqual65P"] == null or $row["MoreOrEqual65P"] == 0)
                echo "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>";
            else                                                          // Weak
                echo "<td class='w3-container w3-text-red w3-hover-red'>" . $row["MoreOrEqual65P"] . "%</td>";
        elseif ($view == 'Average')
            echo "<td class='w3-container'><div>Without absents<br>" .
                    $row["SUM"] . '/' . $row["Total"] . ' = ' . $row["AVGR"] . "</div><br><div>With absents<br>".
                    $row["SUM"] . '/' . $row["TotalWithAbsent"] . ' = ' . $row["AVGRA"] .
                "</div></td>";
        elseif ($view == 'Attainment - Percentage')
            if ($row["MoreOrEqual75P"] >= 75)                                    // Outstanding
                echo "<td class='w3-container w3-text-green w3-hover-green'>Outstanding - (75%) " . $row["MoreOrEqual75P"] . "%</td>";
            elseif ($row["MoreOrEqual75P"] >= 61 and $row["MoreOrEqual75P"] < 75)       // Very Good
                echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good -  (75%) " . $row["MoreOrEqual75P"] . "%</td>";
            elseif ($row["MoreOrEqual75P"] >= 50 and $row["MoreOrEqual75P"] < 61)       // Good
                echo "<td class='w3-container w3-text-lime w3-hover-lime'>Good -  (75%) " . $row["MoreOrEqual75P"] . "%</td>";
            elseif ($row["MoreOrEqual65P"] >= 65)                                 // Acceptable
                echo "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable - (65%) " . $row["MoreOrEqual65P"] . "%</td>";
            elseif ($row["MoreOrEqual65P"] == null or $row["MoreOrEqual65P"] == 0)
                echo "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>";
            else                                                          // Weak
                echo "<td class='w3-container w3-text-red w3-hover-red'>Weak - (65%) " . $row["MoreOrEqual65P"] . "%</td>";

        $year_index++;


        if ($year_index == 5) {
            echo "</tr>";
            $year_index = 0;
            $subject_printed = false;
        }

    }// NewRow
}//Result>0
$conn->close();