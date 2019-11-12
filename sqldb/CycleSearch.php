<?php

include ('../config/dbConfig.php');

$year = $_REQUEST["year"];
$view   = $_REQUEST["view"];
// echo $year;

$sql = "SELECT
    t0.subject_name 'Subject0',t0.exam_name 'Exam0',t0.acd_code 'Year0',t0.grade 'Grade0',t0.MoreOrEqual65P '>=65%0',t0.MoreOrEqual75P '>=75%0',t0.exam_mark 'Mark0',
    t1.subject_name 'Subject1',t1.exam_name 'Exam1',t1.acd_code 'Year1',t1.grade 'Grade1',t1.MoreOrEqual65P '>=65%1',t1.MoreOrEqual75P '>=75%1',t1.exam_mark 'Mark1',
    t2.subject_name 'Subject2',t2.exam_name 'Exam2',t2.acd_code 'Year2',t2.grade 'Grade2',t2.MoreOrEqual65P '>=65%2',t2.MoreOrEqual75P '>=75%2',t2.exam_mark 'Mark2'
FROM
(
        (
        SELECT subject_name,exam_name,acd_code,grade,section,
            COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) 'Total',
            COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
            ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',
            COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
            ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
            exam_mark
        FROM new_marks
        WHERE
            (acd_code = '2016 / 2017') 
            AND
            (grade = 'GR01' OR grade = 'GR02' OR grade = 'GR03' OR grade = 'GR04' OR grade = 'GR05')
        
        GROUP BY subject_name
        ORDER BY subject_name
        ) t0

    LEFT JOIN

        (
        SELECT subject_name,exam_name,acd_code,grade,section,
            COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) 'Total',
            COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
            ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',
            COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
            ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
            exam_mark
        FROM new_marks
        WHERE
            (acd_code = '2016 / 2017') 
            AND
            (grade = 'GR06' OR grade = 'GR07' OR grade = 'GR08' OR grade = 'GR09')
        
        GROUP BY subject_name
        ORDER BY subject_name    
        ) t1
    
    ON
    (t0.subject_name = t1.subject_name)

    LEFT JOIN

        (
        SELECT subject_name,exam_name,acd_code,grade,section,
            COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) 'Total',
            COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
            ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',
            COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
            ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
            exam_mark
        FROM new_marks
        WHERE
            (acd_code = '2016 / 2017') 
            AND
            (grade = 'GR10' OR grade = 'GR11' OR grade = 'GR12')
        
        GROUP BY subject_name
        ORDER BY subject_name    
        ) t2
    
    ON
    (t1.subject_name = t2.subject_name)

)
UNION

SELECT
    t0.subject_name 'Subject0',t0.exam_name 'Exam0',t0.acd_code 'Year0',t0.grade 'Grade0',t0.MoreOrEqual65P '>=65%0',t0.MoreOrEqual75P '>=75%0',t0.exam_mark 'Mark0',
    t1.subject_name 'Subject1',t1.exam_name 'Exam1',t1.acd_code 'Year1',t1.grade 'Grade1',t1.MoreOrEqual65P '>=65%1',t1.MoreOrEqual75P '>=75%1',t1.exam_mark 'Mark1',
    t2.subject_name 'Subject2',t2.exam_name 'Exam2',t2.acd_code 'Year2',t2.grade 'Grade2',t2.MoreOrEqual65P '>=65%2',t2.MoreOrEqual75P '>=75%2',t2.exam_mark 'Mark2'    
FROM
(
        (
        SELECT subject_name,exam_name,acd_code,grade,section,
            COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) 'Total',
            COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
            ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',
            COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
            ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
            exam_mark
        FROM new_marks
        WHERE
            (acd_code = '2016 / 2017') 
            AND
            (grade = 'GR01' OR grade = 'GR02' OR grade = 'GR03' OR grade = 'GR04' OR grade = 'GR05')
        
        GROUP BY subject_name
        ORDER BY subject_name
        ) t0

    RIGHT JOIN

        (
        SELECT subject_name,exam_name,acd_code,grade,section,
            COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) 'Total',
            COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
            ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',
            COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
            ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
            exam_mark
        FROM new_marks
        WHERE
            (acd_code = '2016 / 2017') 
            AND
            (grade = 'GR06' OR grade = 'GR07' OR grade = 'GR08' OR grade = 'GR09')
        
        GROUP BY subject_name
        ORDER BY subject_name    
        ) t1
    
    ON
    (t0.subject_name = t1.subject_name)

    RIGHT JOIN

        (
        SELECT subject_name,exam_name,acd_code,grade,section,
            COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) 'Total',
            COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
            ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',
            COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
            ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
            exam_mark
        FROM new_marks
        WHERE
            (acd_code = '2016 / 2017') 
            AND
            (grade = 'GR10' OR grade = 'GR11' OR grade = 'GR12')
        
        GROUP BY subject_name
        ORDER BY subject_name    
        ) t2
    
    ON
    (t1.subject_name = t2.subject_name)    

)";


    // echo "SQL STATEMENT <br> " . $sql;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

        	if ($row["Subject0"] != null)
            	echo "<tr><td>" . $row["Subject0"] . "</td>";
            elseif ($row["Subject1"] != null)
            	echo "<tr><td>" . $row["Subject1"] . "</td>";
            elseif ($row["Subject2"] != null)
            	echo "<tr><td>" . $row["Subject2"] . "</td>";

            echo "<td></td>";
        	for ($i=0; $i < 3; $i++) {

                if ($view == 'Attainment')
                    if($row[">=75%$i"] == null)
                    	echo "<td></td>";
                    elseif ($row[">=75%$i"] >= 75)                                    // Outstanding
                        echo "<td class='w3-container w3-text-green w3-hover-green'>           Outstanding</td>";
                    elseif ($row[">=75%$i"] >= 61 and $row[">=75%$i"] < 75)       // Very Good
                        echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good</td>";
                    elseif ($row[">=75%$i"] >= 50  and $row[">=75%$i"] <= 61)       // Good
                        echo "<td class='w3-container w3-text-lime w3-hover-lime'>              Good</td>";
                    elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                        echo "<td class='w3-container w3-text-orange w3-hover-orange'>          Acceptable</td>";
                    else                                                          // Not Applicable
                        echo "<td class='w3-container w3-text-red w3-hover-red'>                Not Applicable</td>";
                
                elseif ($view == 'Percentage') 
                    if ($row[">=75%$i"] >= 75)                                    // Outstanding
                        echo "<td class='w3-container w3-text-green w3-hover-green'>".$row[">=75%$i"]. "%</td>";
                    elseif ($row[">=75%$i"] >= 61 and $row[">=75%$i"] < 75)       // Very Good
                        echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>".$row[">=75%$i"]. "%</td>";
                    elseif ($row[">=75%$i"] >= 50  and $row[">=75%$i"] <= 61)       // Good
                        echo "<td class='w3-container w3-text-lime w3-hover-lime'>".$row[">=75%$i"]. "%</td>";
                    elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                        echo "<td class='w3-container w3-text-orange w3-hover-orange'>".$row[">=65%$i"]. "%</td>";
                    else                                                          // Not Applicable
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
                    else                                                          // Not Applicable
                        echo "<td class='w3-container w3-text-red w3-hover-red'>                Not Applicable - ".$row[">=75%$i"]. "%</td>";                
            }
            // echo "<td>-</td><td>-</td><td>-</td><td>-</td>";
            echo "</tr>";
        }
    }	
$conn->close();