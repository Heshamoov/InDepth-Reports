<?php

include('../../../config/dbConfig.php');


$grade = $_REQUEST["Grade"];
$gender = $_REQUEST["Gender"];
$nationality = $_REQUEST["Nationality"];
$student = $_REQUEST["Student"];
$view = $_REQUEST["View"];


$TopColumns = "
SELECT
t0.subject 'Subject0', t0.exam 'Exam0', t0.year 'Year0', t0.grade 'Grade0', t0.Total 'Total0', t0.MoreOrEqual65P '>=65%0', t0.MoreOrEqual75P '>=75%0', t0.mark 'Mark0',
t1.subject 'Subject1', t1.exam 'Exam1', t1.year 'Year1', t1.grade 'Grade1', t1.Total 'Total1', t1.MoreOrEqual65P '>=65%1', t1.MoreOrEqual75P '>=75%1', t1.mark 'Mark1',
t2.subject 'Subject2', t2.exam 'Exam2', t2.year 'Year2', t2.grade 'Grade2', t2.Total 'Total2', t2.MoreOrEqual65P '>=65%2', t2.MoreOrEqual75P '>=75%2', t2.mark 'Mark2', 
t3.subject 'Subject3', t3.exam 'Exam3', t3.year 'Year3', t3.grade 'Grade3', t3.Total 'Total3', t3.MoreOrEqual65P '>=65%3', t3.MoreOrEqual75P '>=75%3', t3.mark 'Mark3' 
";

$InnerColumns = "
    SELECT 
       students.last_name,
       courses.course_name grade,
       batches.name section,
       exam_groups.name    exam,
       subjects.name       subject,
       exam_scores.marks mark,
       academic_years.name year,
       students.birth_place,
       COUNT(IF(exam_scores.marks IS NOT NULL AND exam_scores.marks > 0, 1, NULL))                    'Total',
       COUNT(IF(exam_scores.marks >= 65 AND exam_scores.marks IS NOT NULL, 1, NULL))               AS 'MoreOrEqual65',

       ROUND(COUNT(IF(exam_scores.marks >= 65 AND exam_scores.marks IS NOT NULL, 1, NULL)) /
             COUNT(IF(exam_scores.marks IS NOT NULL AND exam_scores.marks > 0, 1, NULL)) * 100, 0) AS 'MoreOrEqual65P',

       COUNT(IF(exam_scores.marks >= 75 AND exam_scores.marks IS NOT NULL, 1, NULL))               AS 'MoreOrEqual75',
       ROUND(COUNT(IF(exam_scores.marks >= 75 AND exam_scores.marks IS NOT NULL, 1, NULL)) /
             COUNT(IF(exam_scores.marks IS NOT NULL AND exam_scores.marks > 0, 1, NULL)) * 100, 0) AS 'MoreOrEqual75P'
    FROM academic_years
         INNER JOIN batches ON academic_years.id = batches.academic_year_id
         INNER JOIN courses ON batches.course_id = courses.id
         INNER JOIN exam_groups ON batches.id = exam_groups.batch_id
         INNER JOIN exams ON exam_groups.id = exams.exam_group_id
         INNER JOIN subjects ON exams.subject_id = subjects.id
         INNER JOIN exam_scores ON exams.id = exam_scores.exam_id
         INNER JOIN students ON exam_scores.student_id = students.id
         LEFT JOIN student_categories ON students.student_category_id = student_categories.id
";

$YearsA = array("2016 - 2017", "2017 - 2018", "2018 - 2019", "2019 - 2020");

$TermsA = array();
if ($_REQUEST["Term1"] != "") $TermsA[0] = $_REQUEST["Term1"];
if ($_REQUEST["Term2"] != "") $TermsA[1] = $_REQUEST["Term2"];
if ($_REQUEST["Term3"] != "") $TermsA[2] = $_REQUEST["Term3"];
if ($_REQUEST["Term4"] != "") $TermsA[3] = $_REQUEST["Term4"];

//print_r($TermsA);

$WhereA = array();
$GradesA = array("GR 1", "GR 2", "GR 3", "GR 4", "GR 5", "GR 6", "GR 7", "GR 8", "GR 9", "GR10", "GR11", "GR12");
$GradeIndex = array_search($grade, $GradesA);
$GradeIndexGUI = $GradeIndex;

for ($i = 0; $i < 4; $i++) {

    $WhereA[$i] = "WHERE academic_years.name = '$YearsA[$i]' AND (REPLACE(exam_groups.name, ' ','') = REPLACE('$TermsA[$i]', ' ', '')) ";

    if ($student != 'Student' and $student != '')
        $WhereA[$i] .= " AND students.last_name = '$student' ";
    else
        $WhereA[$i] .= " AND courses.course_name = '$GradesA[$GradeIndexGUI]' ";

    if ($gender == 'Boys')
        $WhereA[$i] .= " AND students.gender = 'Male' ";
    elseif ($gender == 'Girls')
        $WhereA[$i] .= " AND students.gender = 'Female' ";

    if ($nationality == 'Citizens')
        $WhereA[$i] .= " AND students.birth_place = 'U.A.E' ";
    elseif ($nationality == 'Expacts')
        $WhereA[$i] .= " AND students.birth_place != 'U.A.E' ";

    $WhereA[$i] .= " GROUP BY subjects.name ORDER BY subjects.name ";

    if ($GradeIndexGUI < 11)
        $GradeIndexGUI++;
}


$sql = $TopColumns . " FROM ( (" . $InnerColumns . $WhereA[0] . ") t0
                               LEFT JOIN ( " . $InnerColumns . "" . $WhereA[1] . ") t1    ON (t0.subject like REPLACE(SUBSTRING_INDEX(t1.subject, ' ', 2), ' ', '%'))
                               LEFT JOIN ( " . $InnerColumns . "" . $WhereA[2] . ") t2    ON (t0.subject like REPLACE(SUBSTRING_INDEX(t2.subject, ' ', 2), ' ', '%'))
                               LEFT JOIN ( " . $InnerColumns . "" . $WhereA[3] . ") t3    ON (t0.subject like REPLACE(SUBSTRING_INDEX(t3.subject, ' ', 2), ' ', '%'))
                               ) ";

$sql .= " UNION " .
    $TopColumns . " FROM ( (" . $InnerColumns . $WhereA[0] . ") t0
                               RIGHT JOIN ( " . $InnerColumns . "" . $WhereA[1] . ") t1   ON (t0.subject like REPLACE(SUBSTRING_INDEX(t1.subject, ' ', 2), ' ', '%'))
                               LEFT JOIN ( " . $InnerColumns . "" . $WhereA[2] . ") t2    ON (t1.subject like REPLACE(SUBSTRING_INDEX(t2.subject, ' ', 2), ' ', '%'))
                               LEFT JOIN ( " . $InnerColumns . "" . $WhereA[3] . ") t3    ON (t2.subject like REPLACE(SUBSTRING_INDEX(t3.subject, ' ', 2), ' ', '%'))
                               ) ";


$sql .= " UNION " .
    $TopColumns . " FROM ( (" . $InnerColumns . $WhereA[0] . ") t0
                               RIGHT JOIN ( " . $InnerColumns . "" . $WhereA[1] . ") t1   ON (t0.subject like REPLACE(SUBSTRING_INDEX(t1.subject, ' ', 2), ' ', '%'))
                               RIGHT JOIN ( " . $InnerColumns . "" . $WhereA[2] . ") t2   ON (t1.subject like REPLACE(SUBSTRING_INDEX(t2.subject, ' ', 2), ' ', '%'))
                               LEFT JOIN ( " . $InnerColumns . "" . $WhereA[3] . ") t3    ON (t2.subject like REPLACE(SUBSTRING_INDEX(t3.subject, ' ', 2), ' ', '%'))
                               ) ";

$sql .= " UNION" .
    $TopColumns . " FROM ( (" . $InnerColumns . $WhereA[0] . ") t0
                               RIGHT JOIN ( " . $InnerColumns . "" . $WhereA[1] . ") t1   ON (t0.subject like REPLACE(SUBSTRING_INDEX(t1.subject, ' ', 2), ' ', '%'))
                               RIGHT JOIN ( " . $InnerColumns . "" . $WhereA[2] . ") t2   ON (t1.subject like REPLACE(SUBSTRING_INDEX(t2.subject, ' ', 2), ' ', '%'))
                               RIGHT JOIN ( " . $InnerColumns . "" . $WhereA[3] . ") t3   ON (t2.subject like REPLACE(SUBSTRING_INDEX(t3.subject, ' ', 2), ' ', '%'))
                               ) ";


//echo $sql;

$GradeHeader = true;

$result = $conn->query($sql);
if ($result->num_rows > 0) {


    while ($row = $result->fetch_assoc()) {
        if ($GradeHeader) {
            echo "<tr><th>Grade Progress</th>";
            for ($i = 0; $i < 4; $i++) {
                if ($row["Grade$i"] != null) {
                    echo "<th>" . $row["Grade$i"] . "</th>";
                    $GradeIndex = array_search($row["Grade$i"], $GradesA);
                }
                else
                    if ($GradeIndex < 11)
                        echo "<th>" . $GradesA[$GradeIndex + 1] . "</th>";
                    else
                        echo "<th>" . $GradesA[$GradeIndex] . "</th>";
            }
            echo "</tr>";
            $GradeHeader = false;
        }

        $NewRow = false;
        if ($row["Subject0"] != null and $row["Subject0"] != "Total Mark") {
            echo "<tr><td>" . $row["Subject0"] . "</td>";
            $NewRow = true;
        } elseif ($row["Subject1"] != null and $row["Subject1"] != "Total Mark") {
            echo "<tr><td>" . $row["Subject1"] . "</td>";
            $NewRow = true;
        } elseif ($row["Subject2"] != null and $row["Subject2"] != "Total Mark") {
            echo "<tr><td>" . $row["Subject2"] . "</td>";
            $NewRow = true;
        }

        if ($NewRow) {
            for ($i = 0; $i < count($TermsA); $i++) {
                // $rowIndex++;
                if ($student != 'Student' AND $student != '') {
                    if ($view == 'Attainment')
                        if ($row["Mark$i"] >= 75)                                    // Outstanding
                            echo "<td class='w3-container w3-text-green w3-hover-green'>           Outstanding</td>";
                        elseif ($row["Mark$i"] >= 61 and $row["Mark$i"] < 75)       // Very Good
                            echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good</td>";
                        elseif ($row["Mark$i"] >= 50 and $row["Mark$i"] <= 61)       // Good
                            echo "<td class='w3-container w3-text-lime w3-hover-lime'>              Good</td>";
                        elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                            echo "<td class='w3-container w3-text-orange w3-hover-orange'>          Acceptable</td>";
                        elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0)
                            echo "<td class='w3-container w3-text-gray w3-hover-gray'>          - </td>";
                        else                                                          // Weak
                            echo "<td class='w3-container w3-text-red w3-hover-red'>                Weak</td>";

                    elseif ($view == 'Percentage')
                        if ($row["Mark$i"] >= 75)
                            echo "<td class='w3-container w3-text-green w3-hover-green'>" . $row["Mark$i"] . "%</td>";
                        elseif ($row["Mark$i"] >= 61 and $row["Mark$i"] < 75)       // Very Good
                            echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>" . $row["Mark$i"] . "%</td>";
                        elseif ($row["Mark$i"] >= 50 and $row["Mark$i"] <= 61)       // Good
                            echo "<td class='w3-container w3-text-lime w3-hover-lime'>" . $row["Mark$i"] . "%</td>";
                        elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                            echo "<td class='w3-container w3-text-orange w3-hover-orange'>" . $row[">=65%$i"] . "%</td>";
                        elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0)
                            echo "<td class='w3-container w3-text-gray w3-hover-gray'>          -</td>";
                        else                                                          // Weak
                            echo "<td class='w3-container w3-text-red w3-hover-red'>" . $row["Mark$i"] . "%</td>";

                    elseif ($view == 'Attainment - Percentage')
                        if ($row["Mark$i"] >= 75)
                            echo "<td class='w3-container w3-text-green w3-hover-green'>           Outstanding - " . $row["Mark$i"] . "%</td>";
                        elseif ($row["Mark$i"] >= 61 and $row["Mark$i"] < 75)       // Very Good
                            echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good - " . $row["Mark$i"] . "%</td>";
                        elseif ($row["Mark$i"] >= 50 and $row["Mark$i"] <= 61)       // Good
                            echo "<td class='w3-container w3-text-lime w3-hover-lime'>              Good - " . $row["Mark$i"] . "%</td>";
                        elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                            echo "<td class='w3-container w3-text-orange w3-hover-orange'>          Acceptable - " . $row[">=65%$i"] . "%</td>";
                        elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0)
                            echo "<td class='w3-container w3-text-gray w3-hover-gray'>          -</td>";
                        else                                                          // Weak
                            echo "<td class='w3-container w3-text-red w3-hover-red'>                Weak - " . $row["Mark$i"] . "%</td>";
                } //No Student Selected
                else {
                    if ($view == 'Attainment')
                        if ($row[">=75%$i"] >= 75)                                    // Outstanding
                            echo "<td class='w3-container w3-text-green w3-hover-green'>           Outstanding</td>";
                        elseif ($row[">=75%$i"] >= 61 and $row[">=75%$i"] < 75)       // Very Good
                            echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good</td>";
                        elseif ($row[">=75%$i"] >= 50 and $row[">=75%$i"] <= 61)       // Good
                            echo "<td class='w3-container w3-text-lime w3-hover-lime'>              Good</td>";
                        elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                            echo "<td class='w3-container w3-text-orange w3-hover-orange'>          Acceptable</td>";
                        elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0)
                            echo "<td class='w3-container w3-text-gray w3-hover-gray'>          -</td>";
                        else                                                          // Weak
                            echo "<td class='w3-container w3-text-red w3-hover-red'>                Weak</td>";

                    elseif ($view == 'Percentage')
                        if ($row[">=75%$i"] >= 75)                                    // Outstanding
                            echo "<td class='w3-container w3-text-green w3-hover-green'>" . $row[">=75%$i"] . "%</td>";
                        elseif ($row[">=75%$i"] >= 61 and $row[">=75%$i"] < 75)       // Very Good
                            echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>" . $row[">=75%$i"] . "%</td>";
                        elseif ($row[">=75%$i"] >= 50 and $row[">=75%$i"] <= 61)       // Good
                            echo "<td class='w3-container w3-text-lime w3-hover-lime'>" . $row[">=75%$i"] . "%</td>";
                        elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                            echo "<td class='w3-container w3-text-orange w3-hover-orange'>" . $row[">=65%$i"] . "%</td>";
                        elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0)
                            echo "<td class='w3-container w3-text-gray w3-hover-gray'>          -</td>";
                        else                                                          // Weak
                            echo "<td class='w3-container w3-text-red w3-hover-red'>" . $row[">=75%$i"] . "%</td>";

                    elseif ($view == 'Attainment - Percentage')
                        if ($row[">=75%$i"] >= 75)                                    // Outstanding
                            echo "<td class='w3-container w3-text-green w3-hover-green'>           Outstanding - " . $row[">=75%$i"] . "%</td>";
                        elseif ($row[">=75%$i"] >= 61 and $row[">=75%$i"] < 75)       // Very Good
                            echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good - " . $row[">=75%$i"] . "%</td>";
                        elseif ($row[">=75%$i"] >= 50 and $row[">=75%$i"] <= 61)       // Good
                            echo "<td class='w3-container w3-text-lime w3-hover-lime'>              Good - " . $row[">=75%$i"] . "%</td>";
                        elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                            echo "<td class='w3-container w3-text-orange w3-hover-orange'>          Acceptable - " . $row[">=65%$i"] . "%</td>";
                        elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0)
                            echo "<td class='w3-container w3-text-gray w3-hover-gray'>          -</td>";
                        else                                                          // Weak
                            echo "<td class='w3-container w3-text-red w3-hover-red'>                Weak - " . $row[">=75%$i"] . "%</td>";
                } // Student Selected
            } // For
            echo "</tr>";
        }// NewRow
    } // While
}//Result>0
else
    echo "Select Grade!";
$conn->close();;