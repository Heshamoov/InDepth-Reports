<?php

include('../../../config/dbConfig.php');


$grade = $_REQUEST["Grade"];
$gender = $_REQUEST["Gender"];
$nationality = $_REQUEST["Nationality"];
$student = $_REQUEST["Student"];
$view = $_REQUEST["View"];
//echo $view;
//echo $nationality + '<br>' + $gender;

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

if ($_REQUEST["Term5"] == 'Final Mark') {

    if ($nationality == 'Citizens')
        $sql .= " AND countries.name = 'United Arab Emirates'";
    elseif ($nationality == 'Expats')
        $sql .= " AND countries.name != 'United Arab Emirates'";

    if ($gender == 'Boys')
        $sql .= " AND students.gender = 'm' ";
    elseif ($gender == 'Girls')
        $sql .= " AND students.gender = 'f' ";

    $sql = "
        SELECT TE1.year,TE1.term,TE1.subject,TE1.mark,CE1.year,CE1.term,CE1.subject,CE1.mark, TE1.studentsCount,
                ROUND(((IF(TE1.mark IS NULL,0, TE1.mark) * 0.3) / TE1.studentsCount) + ((IF(CE1.mark IS NULL, 0, CE1.mark) * 0.7) / CE1.studentsCount), 0) T1FinalMark,

                TE2.year,TE2.term,TE2.subject,TE2.mark,CE2.year,CE2.term,CE2.subject,CE2.mark, TE2.studentsCount,
                ROUND(((IF(TE2.mark IS NULL,0, TE2.mark) * 0.3) / TE2.studentsCount) + ((IF(CE2.mark IS NULL, 0, CE2.mark) * 0.7) / CE2.studentsCount), 0) T2FinalMark,

                TE3.year,TE3.term,TE3.subject,TE3.mark,CE3.year,CE3.term,CE3.subject,CE3.mark, TE3.studentsCount,
                ROUND(((IF(TE3.mark IS NULL,0, TE3.mark) * 0.3) / TE3.studentsCount) + ((IF(CE3.mark IS NULL, 0, CE3.mark) * 0.7) / CE3.studentsCount), 0) T3FinalMark
        FROM (
                (   
                    SELECT academic_years.name year, exam_groups.name term, subjects.code subject, SUM(marks) mark, count(distinct (student_id)) studentsCount
                     FROM academic_years
                        INNER JOIN batches ON academic_years.id = batches.academic_year_id
                        INNER JOIN courses ON batches.course_id = courses.id
                        INNER JOIN exam_groups ON batches.id = exam_groups.batch_id
                        INNER JOIN exams ON exam_groups.id = exams.exam_group_id
                        INNER JOIN subjects ON exams.subject_id = subjects.id
                        INNER JOIN exam_scores ON exams.id = exam_scores.exam_id
                     WHERE
                           (academic_years.name = '2016 - 2017' AND courses.course_name = '$GradesA[0]' AND exam_groups.name like '%Term 1')
                        OR (academic_years.name = '2017 - 2018' AND courses.course_name = '$GradesA[1]' AND exam_groups.name like '%Term 1')
                        OR (academic_years.name = '2018 - 2019' AND courses.course_name = '$GradesA[2]' AND exam_groups.name like '%Term 1')
                        OR (academic_years.name = '2019 - 2020' AND courses.course_name = '$GradesA[3]' AND exam_groups.name like '%Term 1')
                        OR (academic_years.name = '2020 - 2021' AND courses.course_name = '$GradesA[4]' AND exam_groups.name like '%Term 1')
                    GROUP BY year, term, subject
                    ORDER BY year, subject, term
                ) TE1

        LEFT JOIN
                (   
                    SELECT academic_years.name year, exam_groups.name term, subjects.code subject, SUM(marks) mark, count(distinct (student_id)) studentsCount
                     FROM academic_years
                        INNER JOIN batches ON academic_years.id = batches.academic_year_id
                        INNER JOIN courses ON batches.course_id = courses.id
                        INNER JOIN exam_groups ON batches.id = exam_groups.batch_id
                        INNER JOIN exams ON exam_groups.id = exams.exam_group_id
                        INNER JOIN subjects ON exams.subject_id = subjects.id
                        INNER JOIN exam_scores ON exams.id = exam_scores.exam_id
                     WHERE
                           (academic_years.name = '2016 - 2017' AND courses.course_name = '$GradesA[0]' AND exam_groups.name like '%Term 1 - Class Evaluation%')
                        OR (academic_years.name = '2017 - 2018' AND courses.course_name = '$GradesA[1]' AND exam_groups.name like '%Term 1 - Class Evaluation%')
                        OR (academic_years.name = '2018 - 2019' AND courses.course_name = '$GradesA[2]' AND exam_groups.name like '%Term 1 - Class Evaluation%')
                        OR (academic_years.name = '2019 - 2020' AND courses.course_name = '$GradesA[3]' AND exam_groups.name like '%Term 1 - Class Evaluation%')
                        OR (academic_years.name = '2020 - 2021' AND courses.course_name = '$GradesA[4]' AND exam_groups.name like '%Term 1 - Class Evaluation%')
                    GROUP BY year, term, subject
                    ORDER BY year, subject, term
                ) CE1 ON TE1.subject = CE1.subject AND TE1.year = CE1.year

        LEFT JOIN
                (
                    SELECT academic_years.name year, exam_groups.name term, subjects.code subject, SUM(marks) mark, count(distinct (student_id)) studentsCount
                     FROM academic_years
                        INNER JOIN batches ON academic_years.id = batches.academic_year_id
                        INNER JOIN courses ON batches.course_id = courses.id
                        INNER JOIN exam_groups ON batches.id = exam_groups.batch_id
                        INNER JOIN exams ON exam_groups.id = exams.exam_group_id
                        INNER JOIN subjects ON exams.subject_id = subjects.id
                        INNER JOIN exam_scores ON exams.id = exam_scores.exam_id
                     WHERE
                           (academic_years.name = '2016 - 2017' AND courses.course_name = '$GradesA[0]' AND exam_groups.name like '%Term 2')
                        OR (academic_years.name = '2017 - 2018' AND courses.course_name = '$GradesA[1]' AND exam_groups.name like '%Term 2')
                        OR (academic_years.name = '2018 - 2019' AND courses.course_name = '$GradesA[2]' AND exam_groups.name like '%Term 2')
                        OR (academic_years.name = '2019 - 2020' AND courses.course_name = '$GradesA[3]' AND exam_groups.name like '%Term 2')
                        OR (academic_years.name = '2020 - 2021' AND courses.course_name = '$GradesA[4]' AND exam_groups.name like '%Term 2')
                    GROUP BY year, term, subject
                    ORDER BY year, subject, term
                ) TE2 ON TE1.subject = TE2.subject AND TE1.year = TE2.year

    LEFT JOIN
                (
                    SELECT academic_years.name year, exam_groups.name term, subjects.code subject, SUM(marks) mark, count(distinct (student_id)) studentsCount
                     FROM academic_years
                        INNER JOIN batches ON academic_years.id = batches.academic_year_id
                        INNER JOIN courses ON batches.course_id = courses.id
                        INNER JOIN exam_groups ON batches.id = exam_groups.batch_id
                        INNER JOIN exams ON exam_groups.id = exams.exam_group_id
                        INNER JOIN subjects ON exams.subject_id = subjects.id
                        INNER JOIN exam_scores ON exams.id = exam_scores.exam_id
                     WHERE
                           (academic_years.name = '2016 - 2017' AND courses.course_name = '$GradesA[0]' AND exam_groups.name like '%Term 2 - Class Evaluation%')
                        OR (academic_years.name = '2017 - 2018' AND courses.course_name = '$GradesA[1]' AND exam_groups.name like '%Term 2 - Class Evaluation%')
                        OR (academic_years.name = '2018 - 2019' AND courses.course_name = '$GradesA[2]' AND exam_groups.name like '%Term 2 - Class Evaluation%')
                        OR (academic_years.name = '2019 - 2020' AND courses.course_name = '$GradesA[3]' AND exam_groups.name like '%Term 2 - Class Evaluation%')
                        OR (academic_years.name = '2020 - 2021' AND courses.course_name = '$GradesA[4]' AND exam_groups.name like '%Term 2 - Class Evaluation%')
                    GROUP BY year, term, subject
                    ORDER BY year, subject, term
                ) CE2 ON TE2.subject = CE2.subject AND TE2.year = CE2.year

    LEFT JOIN
                (
                    SELECT academic_years.name year, exam_groups.name term, subjects.code subject, SUM(marks) mark, count(distinct (student_id)) studentsCount
                     FROM academic_years
                        INNER JOIN batches ON academic_years.id = batches.academic_year_id
                        INNER JOIN courses ON batches.course_id = courses.id
                        INNER JOIN exam_groups ON batches.id = exam_groups.batch_id
                        INNER JOIN exams ON exam_groups.id = exams.exam_group_id
                        INNER JOIN subjects ON exams.subject_id = subjects.id
                        INNER JOIN exam_scores ON exams.id = exam_scores.exam_id
                     WHERE
                           (academic_years.name = '2016 - 2017' AND courses.course_name = '$GradesA[0]' AND exam_groups.name like '%Term 3')
                        OR (academic_years.name = '2017 - 2018' AND courses.course_name = '$GradesA[1]' AND exam_groups.name like '%Term 3')
                        OR (academic_years.name = '2018 - 2019' AND courses.course_name = '$GradesA[2]' AND exam_groups.name like '%Term 3')
                        OR (academic_years.name = '2019 - 2020' AND courses.course_name = '$GradesA[3]' AND exam_groups.name like '%Term 3')
                        OR (academic_years.name = '2020 - 2021' AND courses.course_name = '$GradesA[4]' AND exam_groups.name like '%Term 3')
                    GROUP BY year, term, subject
                    ORDER BY year, subject, term
                ) TE3 ON TE1.subject = TE3.subject AND TE1.year = TE3.year

    LEFT JOIN
                (
                    SELECT academic_years.name year, exam_groups.name term, subjects.code subject, SUM(marks) mark, count(distinct (student_id)) studentsCount
                     FROM academic_years
                        INNER JOIN batches ON academic_years.id = batches.academic_year_id
                        INNER JOIN courses ON batches.course_id = courses.id
                        INNER JOIN exam_groups ON batches.id = exam_groups.batch_id
                        INNER JOIN exams ON exam_groups.id = exams.exam_group_id
                        INNER JOIN subjects ON exams.subject_id = subjects.id
                        INNER JOIN exam_scores ON exams.id = exam_scores.exam_id
                     WHERE
                           (academic_years.name = '2016 - 2017' AND courses.course_name = '$GradesA[0]' AND exam_groups.name like '%Term 3 - Class Evaluation%')
                        OR (academic_years.name = '2017 - 2018' AND courses.course_name = '$GradesA[1]' AND exam_groups.name like '%Term 3 - Class Evaluation%')
                        OR (academic_years.name = '2018 - 2019' AND courses.course_name = '$GradesA[2]' AND exam_groups.name like '%Term 3 - Class Evaluation%')
                        OR (academic_years.name = '2019 - 2020' AND courses.course_name = '$GradesA[3]' AND exam_groups.name like '%Term 3 - Class Evaluation%')
                        OR (academic_years.name = '2020 - 2021' AND courses.course_name = '$GradesA[4]' AND exam_groups.name like '%Term 3 - Class Evaluation%')
                    GROUP BY year, term, subject
                    ORDER BY year, subject, term
                ) CE3 ON TE3.subject = CE3.subject AND TE3.year = CE3.year
    )";


    echo $sql;
} else {

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
         INNER JOIN countries ON students.nationality_id = countries.id
         LEFT JOIN student_categories ON students.student_category_id = student_categories.id
         WHERE ";

    for ($i = 0; $i < 5; $i++) {
        $sql .= "(academic_years.name = '$YearsA[$i]' 
         AND (REPLACE(exam_groups.name, ' ','') = REPLACE('$TermsA[$i]', ' ', '')) 
         AND courses.course_name = '$GradesA[$i]') ";
        if ($i < 4)
            $sql .= " OR ";
    }

    if ($nationality == 'Citizens')
        $sql .= " AND countries.name = 'United Arab Emirates'";
    elseif ($nationality == 'Expats')
        $sql .= " AND countries.name != 'United Arab Emirates'";

    if ($gender == 'Boys')
        $sql .= " AND students.gender = 'm' ";
    elseif ($gender == 'Girls')
        $sql .= " AND students.gender = 'f' ";

    $sql .= "
GROUP BY year, subject_code
ORDER BY subject_code, year;
";
}
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
                $row["SUM"] . '/' . $row["Total"] . ' = ' . $row["AVGR"] . "</div><br><div>With absents<br>" .
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