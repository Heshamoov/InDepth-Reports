<?php

include ('../config/dbConfig.php');

$subject = $_REQUEST["subject"];
$exam   = $_REQUEST["exam"];


$sql = "
SELECT t1.grade '2017Grade', t1.subject_name '2017Subject', t1.Total '2017Total', t1.MoreOrEqual75P '2017Above', t1.MoreOrEqual65P '2017Minimum', t1.Below65P '2017Below',
       t2.grade '2018Grade', t2.subject_name '2018Subject', t2.Total '2018Total', t2.MoreOrEqual75P '2018Above', t2.MoreOrEqual65P '2018Minimum', t2.Below65P '2018Below',
       t3.grade '2019Grade', t3.subject_name '2019Subject', t3.Total '2019Total', t3.MoreOrEqual75P '2019Above', t3.MoreOrEqual65P '2019Minimum', t3.Below65P '2019Below'
FROM ( ( 
        (
            SELECT subject_name,exam_name,acd_code,grade,
            COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) 'Total',

            COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL,1,NULL)) AS 'Below65',
            ROUND(COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'Below65P',

            COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
            ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

            COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
            ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P'
        
        FROM new_marks
        
        WHERE
            (acd_code = '2016 / 2017')
            AND
            (exam_name = 'Final result') 
            AND
            (subject_name like 'English%')
        
        GROUP BY grade
        ORDER BY grade) t1

INNER JOIN

        (
            SELECT subject_name,exam_name,acd_code,grade,
            COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) 'Total',

            COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL,1,NULL)) AS 'Below65',
            ROUND(COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'Below65P',

            COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
            ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

            COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
            ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P'
        
        FROM new_marks
        
        WHERE
            (acd_code = '2017 / 2018')
            AND
            (exam_name = 'Final result') 
            AND
            (subject_name like 'English%')
        
        GROUP BY grade
        ORDER BY grade) t2
        
        ON (t1.subject_name = t2.subject_name AND t1.grade = t2.grade)

INNER JOIN

        (
            SELECT subject_name,exam_name,acd_code,grade,
            COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) 'Total',

            COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL,1,NULL)) AS 'Below65',
            ROUND(COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'Below65P',

            COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
            ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

            COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
            ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P'
        
        FROM new_marks
        
        WHERE
            (acd_code = '2018 / 2019')
            AND
            (exam_name = 'Final result') 
            AND
            (subject_name like 'English%')
        
        GROUP BY grade
        ORDER BY grade) t3
        
        ON (t2.subject_name = t3.subject_name AND t2.grade = t3.grade)
)     

);
";

// echo "SQL STATEMENT <br> " . $sql;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
            echo   "<tr>";

            echo   "<td>" . $row['2017Grade']   . "</td>
                    <td>" . $row['2017Total']   . "</td>
                    <td>" . $row['2017Above']   . "</td>
                    <td>" . $row['2017Minimum'] . "</td>
                    <td>" . $row['2017Below']   . "</td>";
            
             echo   "<td>" . $row['2018Total']   . "</td>
                    <td>" . $row['2018Above']   . "</td>
                    <td>" . $row['2018Minimum'] . "</td>
                    <td>" . $row['2018Below']   . "</td>";

             echo   "<td>" . $row['2019Total']   . "</td>
                    <td>" . $row['2019Above']   . "</td>
                    <td>" . $row['2019Minimum'] . "</td>
                    <td>" . $row['2019Below']   . "</td>
                    <td></td><td></td>";                    

            echo "</tr>";
    }
    echo "<tr><td class='w3-yellow' colspan=13>Overall judjment</td>
              <td></td><td></td></tr>";

}	
$conn->close();