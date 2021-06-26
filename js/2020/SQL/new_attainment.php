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

if ($_REQUEST["Term5"] == 'Final Mark' OR $_REQUEST["Term4"] == 'Final Mark' OR $_REQUEST["Term3"] == 'Final Mark') {
        $conditions = '';
    if ($nationality == 'Citizens')
        $conditions .= " AND countries.name = 'United Arab Emirates'";
    elseif ($nationality == 'Expats')
        $conditions .= " AND countries.name != 'United Arab Emirates'";

    if ($gender == 'Boys')
        $conditions .= " AND s.gender = 'm' ";
    elseif ($gender == 'Girls')
        $conditions .= " AND s.gender = 'f' ";

    $sql = "
    SELECT TE1.year year, TE1.term,TE1.grade grade,TE1.subject subject,TE1.sCount,TE1.mark,
       CE1.term,CE1.grade,CE1.subject,CE1.sCount,CE1.mark,

       TE2.term,TE2.grade,TE2.subject,TE2.sCount,TE2.mark,
       CE2.term,CE2.grade,CE2.subject,CE2.sCount,CE2.mark,

       TE3.term,TE3.grade,TE3.subject,TE3.sCount,TE3.mark,
       CE3.term,CE3.grade,CE3.subject,CE3.sCount,CE3.mark,
       TE1.sCount Total, TE1.sCount 'TotalWithAbsent',
       
       ROUND(((IF(TE1.mark IS NULL, 0, TE1.mark * 0.3) + IF(CE1.mark IS NULL, 0, CE1.mark * 0.7)) / (IF(TE1.sCount IS NULL, TE2.sCount, TE1.sCount))) * 0.33 +
             ((IF(TE2.mark IS NULL, 0, TE2.mark * 0.3) + IF(CE2.mark IS NULL, 0, CE2.mark * 0.7)) / (IF(TE2.sCount IS NULL, TE1.sCount, TE2.sCount))) * 0.33 +
             ((IF(TE3.mark IS NULL, 0, TE3.mark * 0.3) + IF(CE3.mark IS NULL, 0, CE3.mark * 0.7)) / (IF(TE3.sCount IS NULL, TE1.sCount, TE3.sCount))) * 0.34,
             0)                           FinalMark,
       
       ROUND(
                   (
                           IF(TE1.MoreOrEqual75P IS NULL, 0, TE1.MoreOrEqual75P) +
                           IF(CE1.MoreOrEqual75P IS NULL, 0, CE1.MoreOrEqual75P) +
                           IF(TE2.MoreOrEqual75P IS NULL, 0, TE2.MoreOrEqual75P) +
                           IF(CE2.MoreOrEqual75P IS NULL, 0, CE2.MoreOrEqual75P) +
                           IF(TE3.MoreOrEqual75P IS NULL, 0, TE3.MoreOrEqual75P) +
                           IF(CE3.MoreOrEqual75P IS NULL, 0, CE3.MoreOrEqual75P)
                       ) / 6
           , 0)    MoreOrEqual75P,

       ROUND(
                   (
                           IF(TE1.MoreOrEqual65P IS NULL, 0, TE1.MoreOrEqual65P) +
                           IF(CE1.MoreOrEqual65P IS NULL, 0, CE1.MoreOrEqual65P) +
                           IF(TE2.MoreOrEqual65P IS NULL, 0, TE2.MoreOrEqual65P) +
                           IF(CE2.MoreOrEqual65P IS NULL, 0, CE2.MoreOrEqual65P) +
                           IF(TE3.MoreOrEqual65P IS NULL, 0, TE3.MoreOrEqual65P) +
                           IF(CE3.MoreOrEqual65P IS NULL, 0, CE3.MoreOrEqual65P)
                       ) / 6
           , 0)    MoreOrEqual65P,
      
    TE1.MoreOrEqual65,TE1.MoreOrEqual75,
    CE1.MoreOrEqual65,CE1.MoreOrEqual75,
    TE2.MoreOrEqual65,TE2.MoreOrEqual75,
    CE2.MoreOrEqual65,CE2.MoreOrEqual75,
    TE3.MoreOrEqual65,TE3.MoreOrEqual75,
    CE3.MoreOrEqual65,CE3.MoreOrEqual75

FROM (
      (
          SELECT ay.name                            year,
                 eg.name                            term,
                 s.id                               sid,
                 s.admission_no,
                 concat(c.course_name, '-', b.name) grade,
                 sub.code                           subject,
                 sub.id                             subid,
                 es.id                              mark_id,
                 SUM(marks)                         mark,
                 COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL))                        sCount,

COUNT(IF(es.marks >= 65 AND es.marks IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65',
ROUND(COUNT(IF(es.marks >= 65 AND es.marks IS NOT NULL, 1, NULL)) / COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

COUNT(IF(es.marks >= 75 AND es.marks IS NOT NULL, 1, NULL)) AS 'MoreOrEqual75',
ROUND(COUNT(IF(es.marks >= 75 AND es.marks IS NOT NULL, 1, NULL)) / COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P'

          FROM academic_years ay
                   INNER JOIN batches b ON ay.id = b.academic_year_id
                   INNER JOIN courses c ON b.course_id = c.id
                   INNER JOIN exam_groups eg ON b.id = eg.batch_id
                   INNER JOIN exams e ON eg.id = e.exam_group_id
                   INNER JOIN subjects sub ON e.subject_id = sub.id
                   INNER JOIN exam_scores es ON e.id = es.exam_id
                   INNER JOIN students s ON es.student_id = s.id
                   INNER JOIN countries ON s.nationality_id = countries.id
                   LEFT JOIN student_categories sc ON s.student_category_id = sc.id
          WHERE
          (ay.name = '2018 - 2019' AND c.course_name = '$GradesA[2]' AND 
            (LOWER(REPLACE(eg.name, ' ','')) = REPLACE('Term1-2019',' ','')) $conditions) OR
          (ay.name = '2019 - 2020' AND c.course_name = '$GradesA[3]' AND eg.name like '%Term 1' $conditions) OR
          (ay.name = '2020 - 2021' AND c.course_name = '$GradesA[4]' AND eg.name like '%Term 1' $conditions)
          GROUP BY year, subject
      ) TE1

         LEFT JOIN
     (
         SELECT ay.name                            year,
                eg.name                            term,
                s.id                               sid,
                s.admission_no,
                concat(c.course_name, '-', b.name) grade,
                sub.code                           subject,
                sub.id                             subid,
                SUM(marks)                         mark,
                COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL))                        sCount,

                COUNT(IF(es.marks >= 65 AND es.marks IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65',
                ROUND(COUNT(IF(es.marks >= 65 AND es.marks IS NOT NULL, 1, NULL)) / COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

                COUNT(IF(es.marks >= 75 AND es.marks IS NOT NULL, 1, NULL)) AS 'MoreOrEqual75',
                ROUND(COUNT(IF(es.marks >= 75 AND es.marks IS NOT NULL, 1, NULL)) / COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P'

         FROM academic_years ay
                  INNER JOIN batches b ON ay.id = b.academic_year_id
                  INNER JOIN courses c ON b.course_id = c.id
                  INNER JOIN exam_groups eg ON b.id = eg.batch_id
                  INNER JOIN exams e ON eg.id = e.exam_group_id
                  INNER JOIN subjects sub ON e.subject_id = sub.id
                  INNER JOIN exam_scores es ON e.id = es.exam_id
                  INNER JOIN students s ON es.student_id = s.id
                  INNER JOIN countries ON s.nationality_id = countries.id
                  LEFT JOIN student_categories sc ON s.student_category_id = sc.id
         WHERE
         (ay.name = '2018 - 2019' AND c.course_name = '$GradesA[2]' AND 
            (LOWER(REPLACE(eg.name, ' ','')) = REPLACE('Assessment 1-T1-2019',' ','')) $conditions) OR
         (ay.name = '2019 - 2020' AND c.course_name = '$GradesA[3]' AND eg.name like '%Term 1 - Class Evaluation' $conditions) OR
         (ay.name = '2020 - 2021' AND c.course_name = '$GradesA[4]' AND eg.name like '%Term 1 - Class Evaluation' $conditions)
         GROUP BY year, subject
     ) CE1 ON TE1.subject = CE1.subject AND TE1.year = CE1.year

         LEFT JOIN (
    SELECT ay.name                            year,
           eg.name                            term,
           s.id                               sid,
           s.admission_no,
           concat(c.course_name, '-', b.name) grade,
           sub.code                           subject,
           sub.id                             subid,
           es.id                              mark_id,
           SUM(marks)                         mark,
           COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL))                        sCount,

           COUNT(IF(es.marks >= 65 AND es.marks IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65',
           ROUND(COUNT(IF(es.marks >= 65 AND es.marks IS NOT NULL, 1, NULL)) / COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

           COUNT(IF(es.marks >= 75 AND es.marks IS NOT NULL, 1, NULL)) AS 'MoreOrEqual75',
           ROUND(COUNT(IF(es.marks >= 75 AND es.marks IS NOT NULL, 1, NULL)) / COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P'

    FROM academic_years ay
             INNER JOIN batches b ON ay.id = b.academic_year_id
             INNER JOIN courses c ON b.course_id = c.id
             INNER JOIN exam_groups eg ON b.id = eg.batch_id
             INNER JOIN exams e ON eg.id = e.exam_group_id
             INNER JOIN subjects sub ON e.subject_id = sub.id
             INNER JOIN exam_scores es ON e.id = es.exam_id
             INNER JOIN students s ON es.student_id = s.id
             INNER JOIN countries ON s.nationality_id = countries.id
             LEFT JOIN student_categories sc ON s.student_category_id = sc.id
    WHERE 
    (ay.name = '2018 - 2019' AND c.course_name = '$GradesA[2]' AND 
        (LOWER(REPLACE(eg.name, ' ','')) = REPLACE('Term 2 exam 2019',' ','')) $conditions) OR
    (ay.name = '2019 - 2020' AND c.course_name = '$GradesA[3]' AND eg.name like '%Term 2' $conditions) OR
    (ay.name = '2020 - 2021' AND c.course_name = '$GradesA[4]' AND eg.name like '%Term 2' $conditions)
    GROUP BY year, subject
) TE2 ON TE1.subject = TE2.subject AND TE1.year = TE2.year

         LEFT JOIN
     (
         SELECT ay.name                            year,
                eg.name                            term,
                s.id                               sid,
                s.admission_no,
                concat(c.course_name, '-', b.name) grade,
                sub.code                           subject,
                sub.id                             subid,
                SUM(marks)                         mark,
                COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL))                        sCount,

                COUNT(IF(es.marks >= 65 AND es.marks IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65',
                ROUND(COUNT(IF(es.marks >= 65 AND es.marks IS NOT NULL, 1, NULL)) / COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

                COUNT(IF(es.marks >= 75 AND es.marks IS NOT NULL, 1, NULL)) AS 'MoreOrEqual75',
                ROUND(COUNT(IF(es.marks >= 75 AND es.marks IS NOT NULL, 1, NULL)) / COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P'


         FROM academic_years ay
                  INNER JOIN batches b ON ay.id = b.academic_year_id
                  INNER JOIN courses c ON b.course_id = c.id
                  INNER JOIN exam_groups eg ON b.id = eg.batch_id
                  INNER JOIN exams e ON eg.id = e.exam_group_id
                  INNER JOIN subjects sub ON e.subject_id = sub.id
                  INNER JOIN exam_scores es ON e.id = es.exam_id
                  INNER JOIN students s ON es.student_id = s.id
                  INNER JOIN countries ON s.nationality_id = countries.id
                  LEFT JOIN student_categories sc ON s.student_category_id = sc.id
         WHERE 
         (ay.name = '2018 - 2019' AND c.course_name = '$GradesA[2]' AND 
            (LOWER(REPLACE(eg.name, ' ','')) = LOWER(REPLACE('CLASS EVALUATION 2-2019',' ',''))) $conditions) OR
         (ay.name = '2019 - 2020' AND c.course_name = '$GradesA[3]' AND eg.name like '%Term 2 - Class Evaluation' $conditions) OR
         (ay.name = '2020 - 2021' AND c.course_name = '$GradesA[4]' AND eg.name like '%Term 2 - Class Evaluation' $conditions)
         GROUP BY year, subject
     ) CE2 ON TE1.subject = CE2.subject AND TE1.year = CE2.year

    LEFT JOIN (
    SELECT ay.name                            year,
           eg.name                            term,
           s.id                               sid,
           s.admission_no,
           concat(c.course_name, '-', b.name) grade,
           sub.code                           subject,
           sub.id                             subid,
           es.id                              mark_id,
           SUM(marks)                         mark,
           COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL))                        sCount,

           COUNT(IF(es.marks >= 65 AND es.marks IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65',
           ROUND(COUNT(IF(es.marks >= 65 AND es.marks IS NOT NULL, 1, NULL)) / COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

           COUNT(IF(es.marks >= 75 AND es.marks IS NOT NULL, 1, NULL)) AS 'MoreOrEqual75',
           ROUND(COUNT(IF(es.marks >= 75 AND es.marks IS NOT NULL, 1, NULL)) / COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P'

    FROM academic_years ay
             INNER JOIN batches b ON ay.id = b.academic_year_id
             INNER JOIN courses c ON b.course_id = c.id
             INNER JOIN exam_groups eg ON b.id = eg.batch_id
             INNER JOIN exams e ON eg.id = e.exam_group_id
             INNER JOIN subjects sub ON e.subject_id = sub.id
             INNER JOIN exam_scores es ON e.id = es.exam_id
             INNER JOIN students s ON es.student_id = s.id
             INNER JOIN countries ON s.nationality_id = countries.id
             LEFT JOIN student_categories sc ON s.student_category_id = sc.id
    WHERE 
    (ay.name = '2018 - 2019' AND c.course_name = '$GradesA[2]' AND 
        (LOWER(REPLACE(eg.name, ' ','')) = LOWER(REPLACE('Term 3 Exam 2019',' ',''))) $conditions) OR
    (ay.name = '2019 - 2020' AND c.course_name = '$GradesA[3]' AND eg.name like '%Term 3' $conditions) OR
    (ay.name = '2020 - 2021' AND c.course_name = '$GradesA[4]' AND eg.name like '%Term 3' $conditions)
    GROUP BY year, subject
) TE3 ON TE1.subject = TE3.subject AND TE1.year = TE3.year

         LEFT JOIN
     (
         SELECT ay.name                            year,
                eg.name                            term,
                s.id                               sid,
                s.admission_no,
                concat(c.course_name, '-', b.name) grade,
                sub.code                           subject,
                sub.id                             subid,
                SUM(marks)                         mark,
                COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL))                        sCount,

                COUNT(IF(es.marks >= 65 AND es.marks IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65',
                ROUND(COUNT(IF(es.marks >= 65 AND es.marks IS NOT NULL, 1, NULL)) / COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

                COUNT(IF(es.marks >= 75 AND es.marks IS NOT NULL, 1, NULL)) AS 'MoreOrEqual75',
                ROUND(COUNT(IF(es.marks >= 75 AND es.marks IS NOT NULL, 1, NULL)) / COUNT(IF(es.marks IS NOT NULL AND es.marks > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P'


         FROM academic_years ay
                  INNER JOIN batches b ON ay.id = b.academic_year_id
                  INNER JOIN courses c ON b.course_id = c.id
                  INNER JOIN exam_groups eg ON b.id = eg.batch_id
                  INNER JOIN exams e ON eg.id = e.exam_group_id
                  INNER JOIN subjects sub ON e.subject_id = sub.id
                  INNER JOIN exam_scores es ON e.id = es.exam_id
                  INNER JOIN students s ON es.student_id = s.id
                  INNER JOIN countries ON s.nationality_id = countries.id
                  LEFT JOIN student_categories sc ON s.student_category_id = sc.id
         WHERE 
         (ay.name = '2018 - 2019' AND c.course_name = '$GradesA[2]' AND 
            (LOWER(REPLACE(eg.name, ' ','')) = LOWER(REPLACE('C.E 3',' ',''))) $conditions) OR
         (ay.name = '2019 - 2020' AND c.course_name = '$GradesA[3]' AND eg.name like '%Term 3 - Class Evaluation' $conditions) OR
         (ay.name = '2020 - 2021' AND c.course_name = '$GradesA[4]' AND eg.name like '%Term 3 - Class Evaluation' $conditions)
         GROUP BY year, subject
     ) CE3 ON TE1.subject = CE3.subject AND TE1.year = CE3.year
         )
         ORDER BY TE1.subject, TE1.year
       ";


//    echo $sql;
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