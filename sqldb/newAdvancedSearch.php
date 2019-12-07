<?php

include ('../config/dbConfig.php');


$grade = $_REQUEST["grades"];
$view   = $_REQUEST["view"];
$student = $_REQUEST["student"];

$YArray = array("2016 / 2017", "2017 / 2018", "2018 / 2019");

$TArray = array();
if ($_REQUEST["terms1"] != "") $TArray[0] = $_REQUEST["terms1"];
if ($_REQUEST["terms2"] != "") $TArray[1] = $_REQUEST["terms2"];
if ($_REQUEST["terms3"] != "") $TArray[2] = $_REQUEST["terms3"];

$GArray = array("KG01", "KG02", "GR01", "GR02", "GR03", "GR04", "GR05", "GR06", "GR07", "GR08", "GR09", "GR10", "GR11", "GR12");
$GInedx = array_search($grade, $GArray);
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


$columns = "
SELECT subject_name, exam_name, acd_code, grade, section,
        COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total',
        COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65',

ROUND(  COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) / 
        COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100, 0) AS 'MoreOrEqual65P',

        COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
ROUND(  COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / 
        COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P', exam_mark 
                
FROM new_marks ";


$QArray = array();
if ($grade != "Grade")
{
    for($i = 0; $i < count($TArray); $i++)        
        $QArray[$i] = " t$i.subject_name 'Subject$i', t$i.exam_name 'Exam$i', t$i.acd_code 'Year$i', t$i.grade 'Grade$i', t$i.MoreOrEqual65P '>=65%$i', t$i.MoreOrEqual75P '>=75%$i', t$i.exam_mark 'Mark$i'";

    
        $TopColumns = "SELECT " . $QArray[0];
        
        for($i = 1; $i < count($QArray); $i++)
            $TopColumns .= ", " . $QArray[$i];

        $WhereArray = array();
        for($i = 0; $i < count($TArray); $i++) {
            $GradeIndex = $GInedx + $i;
            $WhereArray[$i] = " WHERE acd_code = '$YArray[$i]' AND $TArray[$i] AND grade = '$GArray[$GradeIndex]' ";

            if ($student != '' AND $student != 'None')
                $WhereArray[$i] .= " AND student_name = '$student' ";

        $WhereArray[$i] .= " GROUP BY subject_name ORDER BY subject_name ";
    }

    $From  = " FROM ";
    $Table = "($columns $WhereArray[0]) t0";

    for ($i=1; $i < count($WhereArray); $i++) { 
        $From .= "(";
        $Table .= " Left JOIN ($columns $WhereArray[$i]) t$i ON (t0.subject_name = t$i.subject_name) )";
    }

    $sql = "$TopColumns $From $Table";

    // $sql = $TopColumns .  " FROM (
                                
    //                                 (
    //                                     (
    //                                         (
    //                                             ($columns $WhereArray[0]) t0
    //                                         INNER JOIN 
    //                                             ($columns $WhereArray[1]) t1 ON (t0.subject_name = t1.subject_name)
    //                                         )

    //                                     INNER JOIN
    //                                         ($columns $WhereArray[2]) t2 ON (t0.subject_name = t2.subject_name)
    //                                     )
    //                                 INNER JOIN 
    //                                     ($columns $WhereArray[3]) t3 ON (t0.subject_name = t3.subject_name)
    //                                 )
    //                             INNER JOIN 
    //                                 ($columns $WhereArray[4]) t4 ON (t0.subject_name = t4.subject_name)
    //                               );";
                                    


    // echo "SQL STATEMENT <br> " . $sql;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row["Subject0"] != 'Total Mark') {
                echo "<tr><td>" . $row["Subject0"] . "</td>";
                $rowIndex = 0;
                for ($i=0; $i < count($TArray); $i++) {
                    $rowIndex++;
                    if ($student != 'None') {
                        if ($view == 'Attainment')
                            if ($row["Mark$i"] >= 75)                                    // Outstanding
                                echo "<td class='w3-container w3-text-green w3-hover-green'>           Outstanding</td>";
                            elseif ($row["Mark$i"] >= 61 and $row["Mark$i"] < 75)       // Very Good
                                echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good</td>";
                            elseif ($row["Mark$i"] >= 50  and $row["Mark$i"] <= 61)       // Good
                                echo "<td class='w3-container w3-text-lime w3-hover-lime'>              Good</td>";
                            elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                                echo "<td class='w3-container w3-text-orange w3-hover-orange'>          Acceptable</td>";
                            elseif ($row[">=65%$i"] == null)
                                echo "<td class='w3-container w3-text-gray w3-hover-gray'>          No Marks</td>";    
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
                            elseif ($row[">=65%$i"] == null)
                                echo "<td class='w3-container w3-text-gray w3-hover-gray'>          No Marks</td>";    
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
                            elseif ($row[">=65%$i"] == null)
                                echo "<td class='w3-container w3-text-gray w3-hover-gray'>          No Marks</td>";    
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
                            elseif ($row[">=65%$i"] == null)
                                echo "<td class='w3-container w3-text-gray w3-hover-gray'>          No Marks</td>";
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
                            elseif ($row[">=65%$i"] == null)
                                echo "<td class='w3-container w3-text-gray w3-hover-gray'>          No Marks</td>";
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
                            elseif ($row[">=65%$i"] == null)
                                echo "<td class='w3-container w3-text-gray w3-hover-gray'>          No Marks</td>";    
                            else                                                          // Weak
                                echo "<td class='w3-container w3-text-red w3-hover-red'>                Weak - ".$row[">=75%$i"]. "%</td>";                
                    } // Student Selected
                } // For
            } // End Total Mark check
            echo "</tr>";
        } // While
    }//Result>0
}//Grade Not Empty
else
    echo "Select Grade!";
$conn->close();;