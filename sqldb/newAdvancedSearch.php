<?php

include ('../config/dbConfig.php');


$grades = $_REQUEST["grades"];

$years1 = $_REQUEST["years1"];
$years2 = $_REQUEST["years2"];

$terms1 = $_REQUEST["terms1"];
$terms2 = $_REQUEST["terms2"];

$columns = "SELECT subject_name, exam_name, acd_code, grade, section,
                COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) 'Total',
                COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65',
                ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100, 0) AS 'MoreOrEqual65P',
                COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
                ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100,0) AS 'MoreOrEqual75P'
                
                FROM new_marks ";

    if ($years1 != "" and $terms1 != "" and $grades != "")
    {
        $t1Columns = "SELECT t1.subject_name 'Subject1', t1.exam_name 'Exam1', t1.acd_code 'Year1', t1.grade 'Grade1', t1.MoreOrEqual65P '>=65%1', t1.MoreOrEqual75P '>=75%1'";

        $t1  = "WHERE
                    $grades AND $years1 AND $terms1 

                GROUP BY subject_name

                ORDER BY
                    subject_name";

        if ($years2 != "" and $terms2 != "")
        {
            $t2Columns = ", t2.subject_name 'Subject2', t2.exam_name 'Exam2', t2.acd_code 'Year2', t2.grade 'Grade2', t2.MoreOrEqual65P '>=65%2', t2.MoreOrEqual75P '>=75%2'";
            
            $t2  = "WHERE
                        $grades AND $years2 AND $terms2

                    GROUP BY subject_name

                    ORDER BY
                        subject_name";
        
        $sql = $t1Columns . $t2Columns .  'FROM (' . $columns . $t1 . ') t1 INNER JOIN (' . $columns . $t2 . ') t2 ON (t1.subject_name = t2.subject_name)';
        }

    }



// echo $sql;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        echo "<tr><td>" . $row["Subject1"] . "</td>";
        
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

        echo "</tr>";
    }
}
$conn->close();