<?php

include ('../config/dbConfig.php');


$grade = $_REQUEST["Grade"];
$gender = $_REQUEST["Gender"];
$nationality = $_REQUEST["Nationality"];
$student = $_REQUEST["Student"];
$view   = $_REQUEST["View"];

$YearsA = array("2016 / 2017", "2017 / 2018", "2018 / 2019");

$TermsA = array();
if ($_REQUEST["Term1"] != "") $TermsA[0] = $_REQUEST["Term1"];
if ($_REQUEST["Term2"] != "") $TermsA[1] = $_REQUEST["Term2"];
if ($_REQUEST["Term3"] != "") $TermsA[2] = $_REQUEST["Term3"];


// echo $GInedx;

// echo "Years <br>";
// for($i = 0; $i < count($YArray); $i++)
//    echo $YArray[$i] . "<br>";

// echo "Count: " . count($YArray) . "<br>";
// echo "****************<br>";



// echo "Terms <br>";
// for($i = 0; $i < count($TArray); $i++)
//    echo $TArray[$i] . "<br>";

// echo "Count: " . count($TArray) . "<br>";
// echo "****************<br>";
// echo $student;
// echo $grade . "\n" . $gender . "\n" . $nationality . "\n" . $student . "\n" . $view . "\n" . $TermsA[0] . "\n" . $TermsA[1] . "\n" . $TermsA[2];


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

for($i = 0; $i<3; $i++) {
    
    $WhereA[$i] = "WHERE acd_code = '$YearsA[$i]' AND (REPLACE(exam_name, ' ','') = REPLACE('$TermsA[$i]', ' ', '')) ";
    
    if ($student != 'Student' and $student != '')    
        $WhereA[$i] .= " AND student_name = '$student' ";
    else
        $WhereA[$i] .= " AND grade = '$GradesA[$GradeIndex]' ";
    
    if ($gender == 'Boys')
        $WhereA[$i] .= " AND gender = 'Male' ";
    elseif ($gender == 'Girls')
        $WhereA[$i] .= " AND gender = 'Female' ";

    if ($nationality = 'Citizens')
        $WhereA[$i] .= " AND nationality = 'U.A.E' ";
    elseif ($nationality = 'Expacts')
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

    $GradeHeader = true;

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {


        while ($row = $result->fetch_assoc()) {
            if ($GradeHeader) {
                echo "<tr><th>Grade Progress</th>";
                for($i=0; $i<3; $i++)
                    echo "<th>" . $row["Grade$i"]. "</th>";
                echo "</tr>";
                $GradeHeader = false;
            }

                $NewRow = false;
                if ($row["Subject0"] != null and $row["Subject0"] != "Total Mark") {
                    echo "<tr><td>" . $row["Subject0"] . "</td>";
                    $NewRow = true;
                }
                elseif ($row["Subject1"] != null and $row["Subject1"] != "Total Mark") {
                    echo "<tr><td>" . $row["Subject1"] . "</td>";
                    $NewRow = true;
                }
                elseif ($row["Subject2"] != null and $row["Subject2"] != "Total Mark") {
                    echo "<tr><td>" . $row["Subject2"] . "</td>";
                    $NewRow = true;
                }

            if ($NewRow) {
                for ($i=0; $i < count($TermsA); $i++) {
                    // $rowIndex++;
                    if ($student != 'Student' AND $student != '') {
                        if ($view == 'Attainment')
                            if ($row["Mark$i"] >= 75)                                    // Outstanding
                                echo "<td class='w3-container w3-text-green w3-hover-green'>           Outstanding</td>";
                            elseif ($row["Mark$i"] >= 61 and $row["Mark$i"] < 75)       // Very Good
                                echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good</td>";
                            elseif ($row["Mark$i"] >= 50  and $row["Mark$i"] <= 61)       // Good
                                echo "<td class='w3-container w3-text-lime w3-hover-lime'>              Good</td>";
                            elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                                echo "<td class='w3-container w3-text-orange w3-hover-orange'>          Acceptable</td>";
                            elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0)
                                echo "<td class='w3-container w3-text-gray w3-hover-gray'>          - </td>";   
                            else                                                          // Weak
                                echo "<td class='w3-container w3-text-red w3-hover-red'>                Weak</td>";
                
                        elseif ($view == 'Percentage') 
                            if ($row["Mark$i"] >= 75)
                                echo "<td class='w3-container w3-text-green w3-hover-green'>".$row["Mark$i"]. "%</td>";
                            elseif ($row["Mark$i"] >= 61 and $row["Mark$i"] < 75)       // Very Good
                                echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>".$row["Mark$i"]. "%</td>";
                            elseif ($row["Mark$i"] >= 50  and $row["Mark$i"] <= 61)       // Good
                                echo "<td class='w3-container w3-text-lime w3-hover-lime'>".$row["Mark$i"]. "%</td>";
                            elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                                echo "<td class='w3-container w3-text-orange w3-hover-orange'>".$row[">=65%$i"]. "%</td>";
                            elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0)
                                echo "<td class='w3-container w3-text-gray w3-hover-gray'>          -</td>";    
                            else                                                          // Weak
                                echo "<td class='w3-container w3-text-red w3-hover-red'>".$row["Mark$i"]. "%</td>";

                        elseif ($view == 'Attainment - Percentage')
                            if ($row["Mark$i"] >= 75)
                                echo "<td class='w3-container w3-text-green w3-hover-green'>           Outstanding - ".$row["Mark$i"]. "%</td>";
                            elseif ($row["Mark$i"] >= 61 and $row["Mark$i"] < 75)       // Very Good
                                echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good - ".$row["Mark$i"]. "%</td>";
                            elseif ($row["Mark$i"] >= 50  and $row["Mark$i"] <= 61)       // Good
                                echo "<td class='w3-container w3-text-lime w3-hover-lime'>              Good - ".$row["Mark$i"]. "%</td>";
                            elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                                echo "<td class='w3-container w3-text-orange w3-hover-orange'>          Acceptable - ".$row[">=65%$i"]. "%</td>";
                            elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0)
                                echo "<td class='w3-container w3-text-gray w3-hover-gray'>          -</td>";    
                            else                                                          // Weak
                                echo "<td class='w3-container w3-text-red w3-hover-red'>                Weak - ".$row["Mark$i"]. "%</td>";
                    } //No Student Selected
                    else {
                        if ($view == 'Attainment')
                            if ($row[">=75%$i"] >= 75)                                    // Outstanding
                                echo "<td class='w3-container w3-text-green w3-hover-green'>           Outstanding</td>";
                            elseif ($row[">=75%$i"] >= 61 and $row[">=75%$i"] < 75)       // Very Good
                                echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good</td>";
                            elseif ($row[">=75%$i"] >= 50  and $row[">=75%$i"] <= 61)       // Good
                                echo "<td class='w3-container w3-text-lime w3-hover-lime'>              Good</td>";
                            elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                                echo "<td class='w3-container w3-text-orange w3-hover-orange'>          Acceptable</td>";
                            elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0)
                                echo "<td class='w3-container w3-text-gray w3-hover-gray'>          -</td>";
                            else                                                          // Weak
                                echo "<td class='w3-container w3-text-red w3-hover-red'>                Weak</td>";
                
                        elseif ($view == 'Percentage') 
                            if ($row[">=75%$i"] >= 75)                                    // Outstanding
                                echo "<td class='w3-container w3-text-green w3-hover-green'>".$row[">=75%$i"]. "%</td>";
                            elseif ($row[">=75%$i"] >= 61 and $row[">=75%$i"] < 75)       // Very Good
                                echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>".$row[">=75%$i"]. "%</td>";
                            elseif ($row[">=75%$i"] >= 50  and $row[">=75%$i"] <= 61)       // Good
                                echo "<td class='w3-container w3-text-lime w3-hover-lime'>".$row[">=75%$i"]. "%</td>";
                            elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                                echo "<td class='w3-container w3-text-orange w3-hover-orange'>".$row[">=65%$i"]. "%</td>";
                            elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0)
                                echo "<td class='w3-container w3-text-gray w3-hover-gray'>          -</td>";
                            else                                                          // Weak
                                echo "<td class='w3-container w3-text-red w3-hover-red'>".$row[">=75%$i"]. "%</td>";

                        elseif ($view == 'Attainment - Percentage')
                            if ($row[">=75%$i"] >= 75)                                    // Outstanding
                                echo "<td class='w3-container w3-text-green w3-hover-green'>           Outstanding - ".$row[">=75%$i"]. "%</td>";
                            elseif ($row[">=75%$i"] >= 61 and $row[">=75%$i"] < 75)       // Very Good
                                echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good - ".$row[">=75%$i"]. "%</td>";
                            elseif ($row[">=75%$i"] >= 50  and $row[">=75%$i"] <= 61)       // Good
                                echo "<td class='w3-container w3-text-lime w3-hover-lime'>              Good - ".$row[">=75%$i"]. "%</td>";
                            elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                                echo "<td class='w3-container w3-text-orange w3-hover-orange'>          Acceptable - ".$row[">=65%$i"]. "%</td>";
                            elseif ($row[">=65%$i"] == null or $row[">=65%$i"] == 0)
                                echo "<td class='w3-container w3-text-gray w3-hover-gray'>          -</td>";    
                            else                                                          // Weak
                                echo "<td class='w3-container w3-text-red w3-hover-red'>                Weak - ".$row[">=75%$i"]. "%</td>";                
                    } // Student Selected
                } // For
            echo "</tr>";
        }// NewRow
        } // While
    }//Result>0
else
    echo "Select Grade!";
$conn->close();;