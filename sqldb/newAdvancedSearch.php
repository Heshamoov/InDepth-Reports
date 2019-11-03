<?php

include ('../config/dbConfig.php');


$grades = $_REQUEST["grades"];

$years1 = $_REQUEST["years1"];
$years2 = $_REQUEST["years2"];
$years3 = $_REQUEST["years3"];
$years4 = $_REQUEST["years4"];
$years5 = $_REQUEST["years5"];

$YArray = array($years1, $years2, $years3, $years4, $years5);

// for($i = 0; $i < count($YArray); $i++)
//     echo $YArray[$i] . "<br>";
// echo "****************<br>";

$terms1 = $_REQUEST["terms1"];
$terms2 = $_REQUEST["terms2"];
$terms3 = $_REQUEST["terms3"];
$terms4 = $_REQUEST["terms4"];
$terms5 = $_REQUEST["terms5"];

$TArray = array($terms1, $terms2, $terms3, $terms4, $terms5);

// for($i = 0; $i < count($TArray); $i++)
//     echo $TArray[$i] . "<br>";
// echo "****************<br>";


$columns = "SELECT subject_name, exam_name, acd_code, grade, section,
                COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) 'Total',
                COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65',
                ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100, 0) AS 'MoreOrEqual65P',
                COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
                ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100,0) AS 'MoreOrEqual75P'
                
                FROM new_marks ";


$QArray = array();

if ($grades != "")
{
    
    for($i = 0; $i < count($TArray); $i++)        
        $QArray[$i] = " t$i.subject_name 'Subject$i', t$i.exam_name 'Exam$i', t$i.acd_code 'Year$i', t$i.grade 'Grade$i', t$i.MoreOrEqual65P '>=65%$i', t$i.MoreOrEqual75P '>=75%$i'";

    
    $TopColumns = "SELECT " . $QArray[0];
    for($i = 1; $i < count($QArray); $i++)
        $TopColumns .= ", " . $QArray[$i];

    $WhereArray = array();
    for($i = 0; $i < count($YArray); $i++) {
        $WhereArray[$i] = "
        WHERE
            $grades AND $YArray[$i] AND $TArray[$i] 

        GROUP BY subject_name

        ORDER BY subject_name ";
    }


    $sql = $TopColumns .  " FROM (
                                
                                    (
                                        (
                                            (
                                                ($columns $WhereArray[0]) t0
                                            INNER JOIN 
                                                ($columns $WhereArray[1]) t1 ON (t0.subject_name = t1.subject_name)
                                            )

                                        INNER JOIN
                                            ($columns $WhereArray[2]) t2 ON (t0.subject_name = t2.subject_name)
                                        )
                                    INNER JOIN 
                                        ($columns $WhereArray[3]) t3 ON (t0.subject_name = t3.subject_name)
                                    )
                                INNER JOIN 
                                    ($columns $WhereArray[4]) t4 ON (t0.subject_name = t4.subject_name)
                                  );";
                                    


    // echo "SQL STATEMENT <br> " . $sql;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            echo "<tr><td>" . $row["Subject0"] . "</td>";
            
            if ($row[">=75%0"] >= 75)                                    // Outstanding
                echo "<td class='w3-container w3-green'>Outstanding</td>";
            elseif ($row[">=75%0"] >= 61 and $row[">=75%0"] < 75)       // Very Good
                echo "<td class='w3-container w3-hover-light-green'>Very Good</td>";
            elseif ($row[">=75%0"] >= 50  and $row[">=75%0"] <= 61)       // Good
                echo "<td class='w3-container w3-lime'>Good</td>";
            elseif ($row[">=65%0"] >= 75)                                 // Acceptable
                echo "<td class='w3-container w3-orange'>Acceptable</td>";
            else                                                        // Not Applicable
                echo "<td class='w3-container w3-red'>Not Applicable</td>";


            if ($row[">=75%1"] >= 75)                                    // Outstanding
                echo "<td class='w3-container w3-green'>Outstanding</td>";
            elseif ($row[">=75%1"] >= 61 and $row[">=75%1"] < 75)       // Very Good
                echo "<td class='w3-container w3-hover-light-green'>Very Good</td>";
            elseif ($row[">=75%1"] >= 50  and $row[">=75%1"] <= 61)       // Good
                echo "<td class='w3-container w3-lime'>Good</td>";
            elseif ($row[">=65%1"] >= 75)                                 // Acceptable
                echo "<td class='w3-container w3-orange'>Acceptable</td>";
            else                                                        // Not Applicable
                echo "<td class='w3-container w3-red'>Not Applicable</td>";


            if ($row[">=75%2"] >= 75)                                    // Outstanding
                echo "<td class='w3-container w3-green'>Outstanding</td>";
            elseif ($row[">=75%2"] >= 61 and $row[">=75%2"] < 75)       // Very Good
                echo "<td class='w3-container w3-hover-light-green'>Very Good</td>";
            elseif ($row[">=75%2"] >= 50  and $row[">=75%2"] <= 61)       // Good
                echo "<td class='w3-container w3-lime'>Good</td>";
            elseif ($row[">=65%2"] >= 75)                                 // Acceptable
                echo "<td class='w3-container w3-orange'>Acceptable</td>";
            else                                                        // Not Applicable
                echo "<td class='w3-container w3-red'>Not Applicable</td>";    

            if ($row[">=75%3"] >= 75)                                    // Outstanding
                echo "<td class='w3-container w3-green'>Outstanding</td>";
            elseif ($row[">=75%3"] >= 61 and $row[">=75%3"] < 75)       // Very Good
                echo "<td class='w3-container w3-hover-light-green'>Very Good</td>";
            elseif ($row[">=75%3"] >= 50  and $row[">=75%3"] <= 61)       // Good
                echo "<td class='w3-container w3-lime'>Good</td>";
            elseif ($row[">=65%3"] >= 75)                                 // Acceptable
                echo "<td class='w3-containe w3-orrange'>Acceptable</td>";
            else                                                        // Not Applicable
                echo "<td class='w3-container w3-red'>Not Applicable</td>";    

            if ($row[">=75%4"] >= 75)                                    // Outstanding
                echo "<td class='w3-container w3-green'>Outstanding</td>";
            elseif ($row[">=75%4"] >= 61 and $row[">=75%4"] < 75)       // Very Good
                echo "<td class='w3-container w3-hover-light-green'>Very Good</td>";
            elseif ($row[">=75%4"] >= 50  and $row[">=75%4"] <= 61)       // Good
                echo "<td class='w3-container w3-lime'>Good</td>";
            elseif ($row[">=65%4"] >= 75)                                 // Acceptable
                echo "<td class='w3-container w3-orange'>Acceptable</td>";
            else                                                        // Not Applicable
                echo "<td class='w3-container w3-red'>Not Applicable</td>";  

            echo "</tr>";
        }
    }
}
else
    echo "Select Grade";
$conn->close();