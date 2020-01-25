<?php

include('../config/dbConfig.php');

$grade = $_REQUEST["Grade"];
$gender = $_REQUEST["Gender"];
$nationality = $_REQUEST["Nationality"];
$student = $_REQUEST["Student"];
$suggested = $_REQUEST["SuggestedName"];
$view = $_REQUEST["View"];

//echo $student;
//echo $suggested;

$OldName = $student;
$nameMatch = "SELECT student_name from new_marks where student_name = '$OldName';";
$result = $conn->query($nameMatch);
if ($result->num_rows == 0)
    $OldName = $suggested;

$NewName = $student;
$nameMatch = "SELECT last_name from students where last_name = '$NewName';";
$result = $conn->query($nameMatch);
if ($result->num_rows == 0)
    $NewName = $suggested;


$YearsA = array("2016 / 2017", "2017 / 2018", "2018 / 2019");

$TermsA = array();
if ($_REQUEST["Term1"] != "") $TermsA[0] = $_REQUEST["Term1"];
if ($_REQUEST["Term2"] != "") $TermsA[1] = $_REQUEST["Term2"];
if ($_REQUEST["Term3"] != "") $TermsA[2] = $_REQUEST["Term3"];

$TopColumns = "
SELECT
t0.subject_name 'Subject0', t0.exam_name 'Exam0', t0.acd_code 'Year0', t0.grade 'Grade0', t0.Total 'Total0', t0.MoreOrEqual65P '>=65%0', t0.MoreOrEqual75P '>=75%0', t0.exam_mark 'Mark0',
t1.subject_name 'Subject1', t1.exam_name 'Exam1', t1.acd_code 'Year1', t1.grade 'Grade1', t1.Total 'Total1', t1.MoreOrEqual65P '>=65%1', t1.MoreOrEqual75P '>=75%1', t1.exam_mark 'Mark1',
t2.subject_name 'Subject2', t2.exam_name 'Exam2', t2.acd_code 'Year2', t2.grade 'Grade2', t2.Total 'Total2', t2.MoreOrEqual65P '>=65%2', t2.MoreOrEqual75P '>=75%2', t2.exam_mark 'Mark2' 
";

$InnerColumns = "
    SELECT subject_name, exam_name, acd_code, grade, section,
        COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total',
        COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65',

        ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) / 
        COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100, 0) AS 'MoreOrEqual65P',

        COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
        ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / 
        COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P', exam_mark         
    FROM new_marks ";

$WhereA = array();
$GradesA = array("GR01", "GR02", "GR03", "GR04", "GR05", "GR06", "GR07", "GR08", "GR09", "GR10", "GR11", "GR12");
$GradeIndex = array_search($grade, $GradesA);

for ($i = 0; $i < 3; $i++) {

    $WhereA[$i] = "WHERE acd_code = '$YearsA[$i]' AND (REPLACE(exam_name, ' ','') = REPLACE('$TermsA[$i]', ' ', '')) ";

    if ($student != 'Student' and $student != '')
        $WhereA[$i] .= " AND REPLACE(student_name, ' ', '') = replace('$OldName', ' ', '') ";
    else
        $WhereA[$i] .= " AND grade = '$GradesA[$GradeIndex]' ";

    if ($gender == 'Boys')
        $WhereA[$i] .= " AND gender = 'Male' ";
    elseif ($gender == 'Girls')
        $WhereA[$i] .= " AND gender = 'Female' ";

    if ($nationality == 'Citizens')
        $WhereA[$i] .= " AND nationality = 'U.A.E' ";
    elseif ($nationality == 'Expacts')
        $WhereA[$i] .= " AND nationality != 'U.A.E' ";

    $WhereA[$i] .= " GROUP BY subject_name ORDER BY subject_name ";

    if ($GradeIndex < 11)
        $GradeIndex++;
}


$sql = $TopColumns . " FROM ( (" . $InnerColumns . $WhereA[0] . ") t0
                               LEFT JOIN ( " . $InnerColumns . "" . $WhereA[1] . ") t1 
                               ON (t0.subject_name = t1.subject_name)
                               LEFT JOIN ( " . $InnerColumns . "" . $WhereA[2] . ") t2 
                               ON (t0.subject_name = t2.subject_name)
                               )
                               UNION " .

    $TopColumns . " FROM ( (" . $InnerColumns . $WhereA[0] . ") t0
                               RIGHT JOIN ( " . $InnerColumns . "" . $WhereA[1] . ") t1 
                               ON (t0.subject_name = t1.subject_name)
                               LEFT JOIN ( " . $InnerColumns . "" . $WhereA[2] . ") t2 
                               ON (t1.subject_name = t2.subject_name)
                               )
                               UNION " .

    $TopColumns . " FROM ( (" . $InnerColumns . $WhereA[0] . ") t0
                               RIGHT JOIN ( " . $InnerColumns . "" . $WhereA[2] . ") t2 
                               ON (t0.subject_name = t2.subject_name)
                               LEFT JOIN ( " . $InnerColumns . "" . $WhereA[1] . ") t1 
                               ON (t2.subject_name = t1.subject_name)

)
";


// echo $sql;
class Subject
{
    public function __construct($year, $grade, $subject, $rank)
    {
        $this->year = $year;
        $this->grade = $grade;
        $this->subject = $subject;
        $this->rank = $rank;
    }
}

$OldSubjectsArray = [];
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $subjects = array();
    while ($row = $result->fetch_assoc()) {
        $NewRow = false;
        $subject_name = "";
        if ($row["Subject0"] != null and $row["Subject0"] != "Total Mark") {
//            echo "<tr><td>" . $row["Subject0"] . "</td>";
            $subject_name = $row["Subject0"];
            $NewRow = true;
        } elseif ($row["Subject1"] != null and $row["Subject1"] != "Total Mark") {
//            echo "<tr><td>" . $row["Subject1"] . "</td>";
            $subject_name = $row["Subject1"];
            $NewRow = true;
        } elseif ($row["Subject2"] != null and $row["Subject2"] != "Total Mark") {
//            echo "<tr><td>" . $row["Subject2"] . "</td>";
            $subject_name = $row["Subject2"];
            $NewRow = true;
        }

        if ($NewRow) {
            for ($i = 0; $i < count($TermsA); $i++) {
                // $rowIndex++;
                if ($student != 'Student' AND $student != '') {
                    if ($view == 'Attainment')
                        if ($row["Mark$i"] >= 75) {                                   // Outstanding
                            //echo "<td class='w3-container w3-text-green w3-hover-green'>Outstanding</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-green w3-hover-green'>Outstanding</td>");
                        } elseif ($row["Mark$i"] >= 61 and $row["Mark$i"] < 75) {      // Very Good
                            //echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good</td>");
                        } elseif ($row["Mark$i"] >= 50 and $row["Mark$i"] <= 61) {    // Good
                            //echo "<td class='w3-container w3-text-lime w3-hover-lime'>Good</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-lime w3-hover-lime'>Good</td>");
                        } elseif ($row[">=65%$i"] >= 65) {                                 // Acceptable
                            //echo "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable</td>");
                        } elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0) {
                            //echo "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>");
                        } else {                                                          // Weak
                            //echo "<td class='w3-container w3-text-red w3-hover-red'>Weak</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-red w3-hover-red'>Weak</td>");
                        }
                    elseif ($view == 'Percentage')
                        if ($row["Mark$i"] >= 75) {
                            //echo "<td class='w3-container w3-text-green w3-hover-green'>" . $row["Mark$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-green w3-hover-green'>" . $row["Mark$i"] . "%</td>");
                        } elseif ($row["Mark$i"] >= 61 and $row["Mark$i"] < 75) {       // Very Good
                            //echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>" . $row["Mark$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-light-green w3-hover-light-green'>" . $row["Mark$i"] . "%</td>");
                        } elseif ($row["Mark$i"] >= 50 and $row["Mark$i"] <= 61) {       // Good
                            //echo "<td class='w3-container w3-text-lime w3-hover-lime'>" . $row["Mark$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-lime w3-hover-lime'>" . $row["Mark$i"] . "%</td>");
                        } elseif ($row[">=65%$i"] >= 65) {                                 // Acceptable
                            //echo "<td class='w3-container w3-text-orange w3-hover-orange'>" . $row[">=65%$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-orange w3-hover-orange'>" . $row[">=65%$i"] . "%</td>");
                        } elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0) {
                            //echo "<td class='w3-container w3-text-gray w3-hover-gray'>          -</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>");
                        } else {                                                         // Weak
                            //echo "<td class='w3-container w3-text-red w3-hover-red'>" . $row["Mark$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-red w3-hover-red'>" . $row["Mark$i"] . "%</td>");
                        }
                    elseif ($view == 'Attainment - Percentage')
                        if ($row["Mark$i"] >= 75) {
                            //echo "<td class='w3-container w3-text-green w3-hover-green'>Outstanding - " . $row["Mark$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-green w3-hover-green'>Outstanding - " . $row["Mark$i"] . "%</td>");
                        } elseif ($row["Mark$i"] >= 61 and $row["Mark$i"] < 75) {       // Very Good
                            //echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good - " . $row["Mark$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good - " . $row["Mark$i"] . "%</td>");
                        } elseif ($row["Mark$i"] >= 50 and $row["Mark$i"] <= 61) {       // Good
                            //echo "<td class='w3-container w3-text-lime w3-hover-lime'>Good - " . $row["Mark$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-lime w3-hover-lime'>Good - " . $row["Mark$i"] . "%</td>");
                        } elseif ($row[">=65%$i"] >= 65) {                                 // Acceptable
                            //echo "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable - " . $row[">=65%$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable - " . $row[">=65%$i"] . "%</td>");
                        } elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0) {
                            //echo "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>");
                        } else {                                                          // Weak
                            //echo "<td class='w3-container w3-text-red w3-hover-red'>Weak - " . $row["Mark$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-red w3-hover-red'>Weak - " . $row["Mark$i"] . "%</td>");
                        }
                } //No Student Selected
                else {
                    if ($view == 'Attainment')
                        if ($row[">=75%$i"] >= 75) {                                    // Outstanding
                            //echo "<td class='w3-container w3-text-green w3-hover-green'>Outstanding</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-green w3-hover-green'>Outstanding</td>");
//                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "Outstanding");
                        } elseif ($row[">=75%$i"] >= 61 and $row[">=75%$i"] < 75) {     // Very Good
                            //echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good</td>");
//                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "Very Good");
                        } elseif ($row[">=75%$i"] >= 50 and $row[">=75%$i"] <= 61) {       // Good
                            //echo "<td class='w3-container w3-text-lime w3-hover-lime'>Good</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-lime w3-hover-lime'>Good</td>");
//                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "Good");
                        } elseif ($row[">=65%$i"] >= 65) {                                 // Acceptable
                            //echo "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable</td>");
//                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "Acceptable");
                        } elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0) {
                            //echo "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>");
//                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "-");
                        } else {                                                          // Weak
                            //echo "<td class='w3-container w3-text-red w3-hover-red'>Weak</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-red w3-hover-red'>Weak</td>");
//                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "Weak");
                        }

                    elseif ($view == 'Percentage')
                        if ($row[">=75%$i"] >= 75) {                                    // Outstanding
                            //echo "<td class='w3-container w3-text-green w3-hover-green'>" . $row[">=75%$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-green w3-hover-green'>" . $row[">=75%$i"] . "%</td>");
                        } elseif ($row[">=75%$i"] >= 61 and $row[">=75%$i"] < 75) {       // Very Good
                            //echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>" . $row[">=75%$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-light-green w3-hover-light-green'>" . $row[">=75%$i"] . "%</td>");
                        } elseif ($row[">=75%$i"] >= 50 and $row[">=75%$i"] <= 61) {       // Good
                            //echo "<td class='w3-container w3-text-lime w3-hover-lime'>" . $row[">=75%$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-lime w3-hover-lime'>" . $row[">=75%$i"] . "%</td>");
                        } elseif ($row[">=65%$i"] >= 65) {                                 // Acceptable
                            //echo "<td class='w3-container w3-text-orange w3-hover-orange'>" . $row[">=65%$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-orange w3-hover-orange'>" . $row[">=65%$i"] . "%</td>");
                        } elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0) {
                            //echo "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>");
                        } else {                                                          // Weak
                            //echo "<td class='w3-container w3-text-red w3-hover-red'>" . $row[">=75%$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-red w3-hover-red'>" . $row[">=75%$i"] . "%</td>");
                        }

                    elseif ($view == 'Attainment - Percentage')
                        if ($row[">=75%$i"] >= 75) {                                    // Outstanding
                            //echo "<td class='w3-container w3-text-green w3-hover-green'>Outstanding - " . $row[">=75%$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-green w3-hover-green'>Outstanding - " . $row[">=75%$i"] . "%</td>");
                        } elseif ($row[">=75%$i"] >= 61 and $row[">=75%$i"] < 75) {      // Very Good
                            //echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good - " . $row[">=75%$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good - " . $row[">=75%$i"] . "%</td>");
                        } elseif ($row[">=75%$i"] >= 50 and $row[">=75%$i"] <= 61) {      // Good
                            //echo "<td class='w3-container w3-text-lime w3-hover-lime'>Good - " . $row[">=75%$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-lime w3-hover-lime'>Good - " . $row[">=75%$i"] . "%</td>");
                        } elseif ($row[">=65%$i"] >= 65) {                                // Acceptable
                            //echo "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable - " . $row[">=65%$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable - " . $row[">=65%$i"] . "%</td>");
                        } elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0) {
                            //echo "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>");
                        } else {                                                          // Weak
                            //echo "<td class='w3-container w3-text-red w3-hover-red'>Weak - " . $row[">=75%$i"] . "%</td>";
                            $subject = new Subject($row["Year$i"], $row["Grade$i"], $subject_name, "<td class='w3-container w3-text-red w3-hover-red'>Weak - " . $row[">=75%$i"] . "%</td>");
                        }
                } // Student Selected
                if ($subject->subject == "Arabic")
                    $subject->subject = "Arabic Language";
                if ($subject->subject == "English")
                    $subject->subject = "English Language";
                if ($subject->subject == "Math")
                    $subject->subject = "Mathematics";
                if ($subject->subject == "PE")
                    $subject->subject = "Physical Education";
                if ($subject->subject == "SSA" or str_replace(" ", "", strtolower($subject->subject)) == "s.studies")
                    $subject->subject = "Social Studies";
                $OldSubjectsArray[] = $subject;
            } // For
            //echo "</tr>";
        }// NewRow
    } // While
    //echo "</table>";


//2020
    $NewGradesA = array("GR 1", "GR 2", "GR 3", "GR 4", "GR 5", "GR 6", "GR 7", "GR 8", "GR 9", "GR10", "GR11", "GR12");
    if ($GradeIndex < 11)
        $NewGrade = $NewGradesA[$GradeIndex++];
    else
        $NewGrade = $NewGradesA[11];

    if ($_REQUEST["Term4"] != "") $term = $_REQUEST["Term4"];
    $RealData = "
SELECT 
       students.last_name,
       courses.course_name Grade,
       batches.name section,
       exam_groups.name    Exam,
       subjects.name       Subject,
       exam_scores.marks Mark,
       academic_years.name Year,
       students.birth_place,
       COUNT(IF(exam_scores.marks IS NOT NULL AND exam_scores.marks > 0, 1, NULL))                    'Total',
       COUNT(IF(exam_scores.marks >= 65 AND exam_scores.marks IS NOT NULL, 1, NULL))               AS 'MoreOrEqual65',

       ROUND(COUNT(IF(exam_scores.marks >= 65 AND exam_scores.marks IS NOT NULL, 1, NULL)) /
             COUNT(IF(exam_scores.marks IS NOT NULL AND exam_scores.marks > 0, 1, NULL)) * 100, 0) AS '>=65%',

       COUNT(IF(exam_scores.marks >= 75 AND exam_scores.marks IS NOT NULL, 1, NULL))               AS 'MoreOrEqual75',
       ROUND(COUNT(IF(exam_scores.marks >= 75 AND exam_scores.marks IS NOT NULL, 1, NULL)) /
             COUNT(IF(exam_scores.marks IS NOT NULL AND exam_scores.marks > 0, 1, NULL)) * 100, 0) AS '>=75%'
    FROM academic_years
         INNER JOIN batches ON academic_years.id = batches.academic_year_id
         INNER JOIN courses ON batches.course_id = courses.id
         INNER JOIN exam_groups ON batches.id = exam_groups.batch_id
         INNER JOIN exams ON exam_groups.id = exams.exam_group_id
         INNER JOIN subjects ON exams.subject_id = subjects.id
         INNER JOIN exam_scores ON exams.id = exam_scores.exam_id
         INNER JOIN students ON exam_scores.student_id = students.id
         LEFT JOIN student_categories ON students.student_category_id = student_categories.id
    
    WHERE academic_years.name = '2019 - 2020' AND (REPLACE(exam_groups.name, ' ','') = REPLACE('$term', ' ', ''))
";
    if ($student != 'Student' and $student != '')
        $RealData .= " AND REPLACE(students.last_name, ' ', '') = REPLACE('$NewName', ' ', '') ";
    else
        $RealData .= " AND courses.course_name = '$NewGrade' ";

    if ($gender == 'Boys')
        $RealData .= " AND students.gender = 'Male' ";
    elseif ($gender == 'Girls')
        $RealData .= " AND students.gender = 'Female' ";

    if ($nationality == 'Citizens')
        $RealData .= " AND students.birth_place = 'U.A.E' ";
    elseif ($nationality == 'Expacts')
        $RealData .= " AND students.birth_place != 'U.A.E' ";

    $RealData .= " GROUP BY subjects.name ORDER BY subjects.name ";


//    echo $RealData;
    $NewSubjectsArray = [];
    $result = $conn->query($RealData);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($student != 'Student' AND $student != '') {
                if ($view == 'Attainment')
                    if ($row["Mark"] >= 75) {                                   // Outstanding
                        //echo "<td class='w3-container w3-text-green w3-hover-green'>Outstanding</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-green w3-hover-green'>Outstanding</td>");
                    } elseif ($row["Mark"] >= 61 and $row["Mark"] < 75) {      // Very Good
                        //echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good</td>");
                    } elseif ($row["Mark"] >= 50 and $row["Mark"] <= 61) {    // Good
                        //echo "<td class='w3-container w3-text-lime w3-hover-lime'>Good</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-lime w3-hover-lime'>Good</td>");
                    } elseif ($row[">=65%"] >= 65) {                                 // Acceptable
                        //echo "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable</td>");
                    } elseif ($row[">=65%"] == null or $row[">=65%"] == 0) {
                        //echo "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>");
                    } else {                                                          // Weak
                        //echo "<td class='w3-container w3-text-red w3-hover-red'>Weak</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-red w3-hover-red'>Weak</td>");
                    }
                elseif ($view == 'Percentage')
                    if ($row["Mark"] >= 75) {
                        //echo "<td class='w3-container w3-text-green w3-hover-green'>" . $row["Mark"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-green w3-hover-green'>" . $row["Mark"] . "%</td>");
                    } elseif ($row["Mark"] >= 61 and $row["Mark"] < 75) {       // Very Good
                        //echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>" . $row["Mark"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-light-green w3-hover-light-green'>" . $row["Mark"] . "%</td>");
                    } elseif ($row["Mark"] >= 50 and $row["Mark"] <= 61) {       // Good
                        //echo "<td class='w3-container w3-text-lime w3-hover-lime'>" . $row["Mark"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-lime w3-hover-lime'>" . $row["Mark"] . "%</td>");
                    } elseif ($row[">=65%"] >= 65) {                                 // Acceptable
                        //echo "<td class='w3-container w3-text-orange w3-hover-orange'>" . $row[">=65%"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-orange w3-hover-orange'>" . $row[">=65%"] . "%</td>");
                    } elseif ($row[">=65%"] == null or $row[">=65%"] == 0) {
                        //echo "<td class='w3-container w3-text-gray w3-hover-gray'>          -</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>");
                    } else {                                                         // Weak
                        //echo "<td class='w3-container w3-text-red w3-hover-red'>" . $row["Mark"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-red w3-hover-red'>" . $row["Mark"] . "%</td>");
                    }
                elseif ($view == 'Attainment - Percentage')
                    if ($row["Mark"] >= 75) {
                        //echo "<td class='w3-container w3-text-green w3-hover-green'>Outstanding - " . $row["Mark"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-green w3-hover-green'>Outstanding - " . $row["Mark"] . "%</td>");
                    } elseif ($row["Mark"] >= 61 and $row["Mark"] < 75) {       // Very Good
                        //echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good - " . $row["Mark"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good - " . $row["Mark"] . "%</td>");
                    } elseif ($row["Mark"] >= 50 and $row["Mark"] <= 61) {       // Good
                        //echo "<td class='w3-container w3-text-lime w3-hover-lime'>Good - " . $row["Mark"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-lime w3-hover-lime'>Good - " . $row["Mark"] . "%</td>");
                    } elseif ($row[">=65%"] >= 65) {                                 // Acceptable
                        //echo "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable - " . $row[">=65%"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable - " . $row[">=65%"] . "%</td>");
                    } elseif ($row[">=65%"] == null or $row[">=65%"] == 0) {
                        //echo "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>");
                    } else {                                                          // Weak
                        //echo "<td class='w3-container w3-text-red w3-hover-red'>Weak - " . $row["Mark"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-red w3-hover-red'>Weak - " . $row["Mark"] . "%</td>");
                    }
            } //No Student Selected
            else {
                if ($view == 'Attainment')
                    if ($row[">=75%"] >= 75) {                                    // Outstanding
                        //echo "<td class='w3-container w3-text-green w3-hover-green'>Outstanding</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-green w3-hover-green'>Outstanding</td>");
//                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "Outstanding");
                    } elseif ($row[">=75%"] >= 61 and $row[">=75%"] < 75) {     // Very Good
                        //echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good</td>");
//                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "Very Good");
                    } elseif ($row[">=75%"] >= 50 and $row[">=75%"] <= 61) {       // Good
                        //echo "<td class='w3-container w3-text-lime w3-hover-lime'>Good</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-lime w3-hover-lime'>Good</td>");
//                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "Good");
                    } elseif ($row[">=65%"] >= 65) {                                 // Acceptable
                        //echo "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable</td>");
//                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "Acceptable");
                    } elseif ($row[">=65%"] == null or $row[">=65%"] == 0) {
                        //echo "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>");
//                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "-");
                    } else {                                                          // Weak
                        //echo "<td class='w3-container w3-text-red w3-hover-red'>Weak</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-red w3-hover-red'>Weak</td>");
//                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "Weak");
                    }

                elseif ($view == 'Percentage')
                    if ($row[">=75%"] >= 75) {                                    // Outstanding
                        //echo "<td class='w3-container w3-text-green w3-hover-green'>" . $row[">=75%"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-green w3-hover-green'>" . $row[">=75%"] . "%</td>");
                    } elseif ($row[">=75%"] >= 61 and $row[">=75%"] < 75) {       // Very Good
                        //echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>" . $row[">=75%"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-light-green w3-hover-light-green'>" . $row[">=75%"] . "%</td>");
                    } elseif ($row[">=75%"] >= 50 and $row[">=75%"] <= 61) {       // Good
                        //echo "<td class='w3-container w3-text-lime w3-hover-lime'>" . $row[">=75%"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-lime w3-hover-lime'>" . $row[">=75%"] . "%</td>");
                    } elseif ($row[">=65%"] >= 65) {                                 // Acceptable
                        //echo "<td class='w3-container w3-text-orange w3-hover-orange'>" . $row[">=65%"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-orange w3-hover-orange'>" . $row[">=65%"] . "%</td>");
                    } elseif ($row[">=65%"] == null or $row[">=65%"] == 0) {
                        //echo "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>");
                    } else {                                                          // Weak
                        //echo "<td class='w3-container w3-text-red w3-hover-red'>" . $row[">=75%"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-red w3-hover-red'>" . $row[">=75%"] . "%</td>");
                    }

                elseif ($view == 'Attainment - Percentage')
                    if ($row[">=75%"] >= 75) {                                    // Outstanding
                        //echo "<td class='w3-container w3-text-green w3-hover-green'>Outstanding - " . $row[">=75%"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-green w3-hover-green'>Outstanding - " . $row[">=75%"] . "%</td>");
                    } elseif ($row[">=75%"] >= 61 and $row[">=75%"] < 75) {      // Very Good
                        //echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good - " . $row[">=75%"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good - " . $row[">=75%"] . "%</td>");
                    } elseif ($row[">=75%"] >= 50 and $row[">=75%"] <= 61) {      // Good
                        //echo "<td class='w3-container w3-text-lime w3-hover-lime'>Good - " . $row[">=75%"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-lime w3-hover-lime'>Good - " . $row[">=75%"] . "%</td>");
                    } elseif ($row[">=65%"] >= 65) {                                // Acceptable
                        //echo "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable - " . $row[">=65%"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-orange w3-hover-orange'>Acceptable - " . $row[">=65%"] . "%</td>");
                    } elseif ($row[">=65%"] == null or $row[">=65%"] == 0) {
                        //echo "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-gray w3-hover-gray'>-</td>");
                    } else {                                                          // Weak
                        //echo "<td class='w3-container w3-text-red w3-hover-red'>Weak - " . $row[">=75%"] . "%</td>";
                        $subject = new Subject($row["Year"], $row["Grade"], $row["Subject"], "<td class='w3-container w3-text-red w3-hover-red'>Weak - " . $row[">=75%"] . "%</td>");
                    }
            } // Student Selected
            if ($subject->subject == "Arabic")
                $subject->subject = "Arabic Language";
            if ($subject->subject == "English")
                $subject->subject = "English Language";
            if ($subject->subject == "Math")
                $subject->subject = "Mathematics";
            if ($subject->subject == "PE")
                $subject->subject = "Physical Education";
            if ($subject->subject == "SSA" or str_replace(" ", "", strtolower($subject->subject)) == "s.studies")
                $subject->subject = "Social Studies";

            $NewSubjectsArray[] = $subject;
        } // WHILE
        //echo "</tr>";
    }// IF $result -> num_rows
    else
        echo "Select Grade!";

//    Sorting Subjects Arrays into one array
    sort($OldSubjectsArray);
    sort($NewSubjectsArray);
    $result = array_merge($OldSubjectsArray, $NewSubjectsArray);

    $subjects = array();
    foreach ($result as $o => $cur)
        if (!array_search(str_replace(" ", "", strtolower($cur->subject)), str_replace(" ", "", array_map('strtolower', $subjects))))
            $subjects[] = $cur->subject;
    sort($subjects);
    $subjects = array_unique(array_map('strtolower',$subjects));
    $subjects = array_unique(array_map('ucwords',$subjects));

    $s1617 = []; $s1718 = []; $s1819 = []; $s1920 = [];
    foreach ($result as $s => $cur) {
        if ($cur->year === '2016 / 2017')
            $s1617[] = $cur;
        elseif ($cur->year === '2017 / 2018')
            $s1718[] = $cur;
        elseif ($cur->year === '2018 / 2019')
            $s1819[] = $cur;
        elseif ($cur->year === '2019 - 2020')
            $s1920[] = $cur;
    }

    echo "<tr><th>Grade Progress</th>";
    if (count($s1617) > 0)
        echo "<th>" . $s1617[0]->grade . "</th>";
    else
        echo "<th>-</th>";
    if (count($s1718) > 0)
        echo "<th>" . $s1718[0]->grade . "</th>";
    else
        echo "<th>-</th>";
    if (count($s1819) > 0)
        echo "<th>" . $s1819[0]->grade . "</th>";
    else
        echo "<th>-</th>";
    if (count($s1920) > 0)
        echo "<th>" . $s1920[0]->grade . "</th>";
    else
        echo "<th>-</th>";
    echo "</tr>";

    foreach ($subjects as $s) {
        echo "<tr><th>" . $s . "</th>";
        $hit = false;
        foreach ($s1617 as $o67 => $cur)
            if (strcmp(str_replace(" ", "", strtolower($cur->subject)), str_replace(" ", "", strtolower($s))) == 0) {
                $hit = true;
                break;
            }
        if ($hit)
//            echo "<td>" . $cur->year . " - " . $cur->grade . " - " . $cur->subject . " - " . $cur->rank . "</td>";
            echo $cur->rank;
        else
            echo "<td>-</td>";

        $hit = false;
        foreach ($s1718 as $o78 => $cur)
            if (strcmp(str_replace(" ", "", strtolower($cur->subject)), str_replace(" ", "", strtolower($s))) == 0) {
                $hit = true;
                break;
            }
        if ($hit)
//            echo "<td>" . $cur->year . " - " . $cur->grade . " - " . $cur->subject . " - " . $cur->rank . "</td>";
            echo $cur->rank;
        else
            echo "<td>-</td>";

        $hit = false;
        foreach ($s1819 as $o89 => $cur)
            if (strcmp(str_replace(" ", "", strtolower($cur->subject)), str_replace(" ", "", strtolower($s))) == 0) {
                $hit = true;
                break;
            }
        if ($hit)
//            echo "<td>" . $cur->year . " - " . $cur->grade . " - " . $cur->subject . " - " . $cur->rank . "</td>";
            echo $cur->rank;
        else
            echo "<td>-</td>";

        $hit = false;
        foreach ($s1920 as $o90 => $cur)
            if (strcmp(str_replace(" ", "", strtolower($cur->subject)), str_replace(" ", "", strtolower($s))) == 0) {
                $hit = true;
                break;
            }
        if ($hit)
//            echo "<td>" . $cur->year . " - " . $cur->grade . " - " . $cur->subject . " - " . $cur->rank . "</td>";
            echo $cur->rank;
        else
            echo "<td>-</td>";

        echo "</tr>";
    }

}//Result>0
else
    echo "Select Grade!";
$conn->close();;