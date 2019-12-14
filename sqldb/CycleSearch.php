<?php

include ('../config/dbConfig.php');

$view   = $_REQUEST["view"];
$year1 = $_REQUEST["year1"];
$year2 = $_REQUEST["year2"];
$year3 = $_REQUEST["year3"];
$cycle1 = $_REQUEST["cycle1"];
$cycle2 = $_REQUEST["cycle2"];
$cycle3 = $_REQUEST["cycle3"];
$trendD   = $_REQUEST["trend"];

// echo $view . "<br>" . $year1 . "<br>" . $year2 . "<br>" . $year3 . "<br>" . $cycle1 . "<br>" . $cycle2 . "<br>" . $cycle3;

$sql = "
SELECT
    t0.subject_name 'Subject0',t0.exam_name 'Exam0',t0.acd_code 'Year0',t0.grade 'Grade0',t0.MoreOrEqual65P '>=65%0',t0.MoreOrEqual75P '>=75%0',t0.exam_mark 'Mark0', t0.AVG 't0AVG',
    t1.subject_name 'Subject1',t1.exam_name 'Exam1',t1.acd_code 'Year1',t1.grade 'Grade1',t1.MoreOrEqual65P '>=65%1',t1.MoreOrEqual75P '>=75%1',t1.exam_mark 'Mark1', t1.AVG 't1AVG',
    t2.subject_name 'Subject2',t2.exam_name 'Exam2',t2.acd_code 'Year2',t2.grade 'Grade2',t2.MoreOrEqual65P '>=65%2',t2.MoreOrEqual75P '>=75%2',t2.exam_mark 'Mark2', t2.AVG 't2AVG',
    ROUND((((((t1.AVG - t0.AVG) / t0.AVG) * 100) + (((t2.AVG - t1.AVG) / t1.AVG) * 100)) /2), 1) AS 'Trend'
FROM
(
    (
    SELECT subject_name,exam_name,acd_code,grade,section,
        COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) 'Total',
        COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
        ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',
        COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
        ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
        exam_mark, TRUNCATE(AVG(exam_mark),0) AS 'AVG'
    FROM new_marks
    WHERE
    
(acd_code = '$year1') AND (exam_name = 'Final Certificate Mark') ";

if ($cycle1 == 'Cycle 1')
    $sql .= " AND (grade = 'GR01' OR grade = 'GR02' OR grade = 'GR03' OR grade = 'GR04' OR grade = 'GR05') ";
elseif ($cycle1 == 'Cycle 2')
    $sql .= " AND (grade = 'GR06' OR grade = 'GR07' OR grade = 'GR08' OR grade = 'GR09') ";
elseif ($cycle1 == 'Cycle 3')
    $sql .= " AND (grade = 'GR10' OR grade = 'GR11' OR grade = 'GR12') ";
        
    $sql .= " GROUP BY subject_name
        ORDER BY subject_name
        ) t0

    LEFT JOIN

        (
        SELECT subject_name,exam_name,acd_code,grade,section,
            COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) 'Total',

COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',
            COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
            ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
            exam_mark, TRUNCATE(AVG(exam_mark),0) AS 'AVG'
        FROM new_marks
        WHERE
            (acd_code = '$year2') AND (exam_name = 'Final Certificate Mark') ";

 if ($cycle2 == 'Cycle 1')
    $sql .= " AND (grade = 'GR01' OR grade = 'GR02' OR grade = 'GR03' OR grade = 'GR04' OR grade = 'GR05') ";
elseif ($cycle2 == 'Cycle 2')
    $sql .= " AND (grade = 'GR06' OR grade = 'GR07' OR grade = 'GR08' OR grade = 'GR09') ";
elseif ($cycle2 == 'Cycle 3')
    $sql .= " AND (grade = 'GR10' OR grade = 'GR11' OR grade = 'GR12') ";           
        
    $sql .=" GROUP BY subject_name
             ORDER BY subject_name    
        ) t1
    
    ON
    (t0.subject_name = t1.subject_name)

    LEFT JOIN

        (
        SELECT subject_name,exam_name,acd_code,grade,section,
            COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) 'Total',
            COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
            ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',
            COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
            ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
            exam_mark, TRUNCATE(AVG(exam_mark),0) AS 'AVG'
        FROM new_marks
        WHERE
            (acd_code = '$year3') AND (exam_name = 'Final Certificate Mark') ";

 if ($cycle3 == 'Cycle 1')
    $sql .= " AND (grade = 'GR01' OR grade = 'GR02' OR grade = 'GR03' OR grade = 'GR04' OR grade = 'GR05') ";
elseif ($cycle3 == 'Cycle 2')
    $sql .= " AND (grade = 'GR06' OR grade = 'GR07' OR grade = 'GR08' OR grade = 'GR09') ";
elseif ($cycle3 == 'Cycle 3')
    $sql .= " AND (grade = 'GR10' OR grade = 'GR11' OR grade = 'GR12') ";           
        
    $sql .=" 
        GROUP BY subject_name
        ORDER BY subject_name    
        ) t2
    
    ON
    (t1.subject_name = t2.subject_name)

)
UNION

SELECT
    t0.subject_name 'Subject0',t0.exam_name 'Exam0',t0.acd_code 'Year0',t0.grade 'Grade0',t0.MoreOrEqual65P '>=65%0',t0.MoreOrEqual75P '>=75%0',t0.exam_mark 'Mark0', t0.AVG 't0AVG',
    t1.subject_name 'Subject1',t1.exam_name 'Exam1',t1.acd_code 'Year1',t1.grade 'Grade1',t1.MoreOrEqual65P '>=65%1',t1.MoreOrEqual75P '>=75%1',t1.exam_mark 'Mark1', t1.AVG 't1AVG',
    t2.subject_name 'Subject2',t2.exam_name 'Exam2',t2.acd_code 'Year2',t2.grade 'Grade2',t2.MoreOrEqual65P '>=65%2',t2.MoreOrEqual75P '>=75%2',t2.exam_mark 'Mark2', t2.AVG 't2AVG',
    ROUND((((((t1.AVG - t0.AVG) / t0.AVG) * 100) + (((t2.AVG - t1.AVG) / t1.AVG) * 100)) /2), 1) AS 'Trend'
FROM
(
        (
        SELECT subject_name,exam_name,acd_code,grade,section,
            COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) 'Total',
            COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
            ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',
            COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
            ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
            exam_mark, TRUNCATE(AVG(exam_mark),0) AS 'AVG'
        FROM new_marks
        WHERE
            (acd_code = '$year1') AND (exam_name = 'Final Certificate Mark') ";
 
 if ($cycle1 == 'Cycle 1')
    $sql .= " AND (grade = 'GR01' OR grade = 'GR02' OR grade = 'GR03' OR grade = 'GR04' OR grade = 'GR05') ";
elseif ($cycle1 == 'Cycle 2')
    $sql .= " AND (grade = 'GR06' OR grade = 'GR07' OR grade = 'GR08' OR grade = 'GR09') ";
elseif ($cycle1 == 'Cycle 3')
    $sql .= " AND (grade = 'GR10' OR grade = 'GR11' OR grade = 'GR12') ";           
        
    $sql .="         
        GROUP BY subject_name
        ORDER BY subject_name
        ) t0

    RIGHT JOIN

        (
        SELECT subject_name,exam_name,acd_code,grade,section,
            COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) 'Total',
            COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
            ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',
            COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
            ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
            exam_mark, TRUNCATE(AVG(exam_mark),0) AS 'AVG'
        FROM new_marks
        WHERE
            (acd_code = '$year2') AND (exam_name = 'Final Certificate Mark') ";
 if ($cycle2 == 'Cycle 1')
    $sql .= " AND (grade = 'GR01' OR grade = 'GR02' OR grade = 'GR03' OR grade = 'GR04' OR grade = 'GR05') ";
elseif ($cycle2 == 'Cycle 2')
    $sql .= " AND (grade = 'GR06' OR grade = 'GR07' OR grade = 'GR08' OR grade = 'GR09') ";
elseif ($cycle2 == 'Cycle 3')
    $sql .= " AND (grade = 'GR10' OR grade = 'GR11' OR grade = 'GR12') ";           
        
    $sql .="
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
            ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',
            COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
            ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
            exam_mark, TRUNCATE(AVG(exam_mark),0) AS 'AVG'
        FROM new_marks
        WHERE
            (acd_code = '$year3') AND (exam_name = 'Final Certificate Mark')  ";
 if ($cycle3 == 'Cycle 1')
    $sql .= " AND (grade = 'GR01' OR grade = 'GR02' OR grade = 'GR03' OR grade = 'GR04' OR grade = 'GR05') ";
elseif ($cycle3 == 'Cycle 2')
    $sql .= " AND (grade = 'GR06' OR grade = 'GR07' OR grade = 'GR08' OR grade = 'GR09') ";
elseif ($cycle3 == 'Cycle 3')
    $sql .= " AND (grade = 'GR10' OR grade = 'GR11' OR grade = 'GR12') ";           
        
    $sql .="     
        GROUP BY subject_name
        ORDER BY subject_name    
        ) t2
    
    ON
    (t1.subject_name = t2.subject_name)    

)

ORDER BY ISNULL(Subject0), Subject0, ISNULL(Subject1), Subject1, ISNULL(Subject2), Subject2;
";


    // echo "SQL STATEMENT <br> " . $sql;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // // echo "<tr>
        // //         <th style='text-align: center' colspan=5>$year</th>
        // //       </tr>";
        // echo "<tr><th class='w3-container w3-hover-green w3-center'>1.1 Attainment</th>
        //           <th class='w3-container w3-hover-green w3-center'>KG</th>
        //           <th class='w3-container w3-hover-green w3-center'>Cycle 1 / Primary</th>
        //           <th class='w3-container w3-hover-green w3-center'>Cycle 2 / Middle</th>
        //           <th class='w3-container w3-hover-green w3-center'>Cycle 3 / High</th></tr>";
while ($row = $result->fetch_assoc()) {

    if ($row["Subject0"] != 'Total Mark' AND $row["Subject1"] != 'Total Mark' AND $row["Subject2"] != 'Total Mark') {    	
        if ($row["Subject0"] != null)
            	echo "<tr><td>" . $row["Subject0"] . "</td>";
            elseif ($row["Subject1"] != null)
            	echo "<tr><td>" . $row["Subject1"] . "</td>";
            elseif ($row["Subject2"] != null)
            	echo "<tr><td>" . $row["Subject2"] . "</td>";

        	for ($i=0; $i < 3; $i++) {
                if ($view == 'Attainment')
                    if($row[">=75%$i"] == null or $row[">=65%$i"] == null)
                    	echo "<td>-</td>";
                    elseif ($row[">=75%$i"] >= 75)                                    // Outstanding
                        echo "<td class='w3-container w3-text-green w3-hover-green rc-green'>
                        Outstanding</td>";
                    elseif ($row[">=75%$i"] >= 61 and $row[">=75%$i"] < 75)       // Very Good
                        echo "<td class='w3-container w3-text-light-green w3-hover-light-green rc-l-green'>
                                    Very Good</td>";
                    elseif ($row[">=75%$i"] >= 50  and $row[">=75%$i"] <= 61)       // Good
                        echo "<td class='w3-container w3-text-lime w3-hover-lime rc-lime'>Good</td>";
                    elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                        echo "<td class='w3-container w3-text-orange w3-hover-orange rc-orange'>Acceptable</td>";
                    else                                                          // Weak
                        echo "<td class='w3-container w3-text-red w3-hover-red rc-red'> 
                                       Weak</td>";
                
                elseif ($view == 'Percentage')
                	if($row[">=75%$i"] == null or $row[">=65%$i"] == null)
                    	echo "<td>-</td>";
                    elseif ($row[">=75%$i"] >= 75)                                    // Outstanding
                        echo "<td class='w3-container w3-text-green w3-hover-green rc-green'>".$row[">=75%$i"]. "%</td>";
                    elseif ($row[">=75%$i"] >= 61 and $row[">=75%$i"] < 75)       // Very Good
                        echo "<td class='w3-container w3-text-light-green w3-hover-light-green rc-l-green'>".$row[">=75%$i"]. "%</td>";
                    elseif ($row[">=75%$i"] >= 50  and $row[">=75%$i"] <= 61)       // Good
                        echo "<td class='w3-container w3-text-lime w3-hover-lime rc-lime'>".$row[">=75%$i"]. "%</td>";
                    elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                        echo "<td class='w3-container w3-text-orange w3-hover-orange rc-orange'>".$row[">=65%$i"]. "%</td>";
                    else                                                          // Weak
                        echo "<td class='w3-container w3-text-red w3-hover-red rc-red'>".$row[">=75%$i"]. "%</td>";

                elseif ($view == 'Attainment - Percentage')
                	if($row[">=75%$i"] == null or $row[">=65%$i"] == null)
                    	echo "<td>-</td>";
                    elseif ($row[">=75%$i"] >= 75)                                    // Outstanding
                        echo "<td class='w3-container w3-text-green w3-hover-green rc-green'>           Outstanding - ".$row[">=75%$i"]. "%</td>";
                    elseif ($row[">=75%$i"] >= 61 and $row[">=75%$i"] < 75)       // Very Good
                        echo "<td class='w3-container w3-text-light-green w3-hover-light-green rc-l-green'>Very Good - ".$row[">=75%$i"]. "%</td>";
                    elseif ($row[">=75%$i"] >= 50  and $row[">=75%$i"] <= 61)       // Good
                        echo "<td class='w3-container w3-text-lime w3-hover-lime rc-lime'>              Good - ".$row[">=75%$i"]. "%</td>";
                    elseif ($row[">=65%$i"] >= 65)                                 // Acceptable
                        echo "<td class='w3-container w3-text-orange w3-hover-orange rc-orange'>          Acceptable - ".$row[">=65%$i"]. "%</td>";
                    else                                                          // Weak
                        echo "<td class='w3-container w3-text-red w3-hover-red rc-red'>                Weak - ".$row[">=75%$i"]. "%</td>";
            }
        if ($trendD == 'Details')
                echo "<td>" . $row['Trend'] . "</td>";  
        echo "</tr>";
    }
}
}
$conn->close();