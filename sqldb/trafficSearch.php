<?php

include ('../config/dbConfig.php');

$subject = $_REQUEST["subject"];
$exam   = $_REQUEST["exam"];


// $sql = "
// SELECT 
// t1.grade '2017Grade', t1.subject_name '2017Subject', t1.Total '2017Total', t1.MoreOrEqual75 '#2017Above', t1.MoreOrEqual75P '2017Above', t1.MoreOrEqual65 '#2017Minimum', t1.MoreOrEqual65P '2017Minimum', t1.Below65 '#2017Below', t1.Below65P '2017Below',
// t1.AVG '2017AVG', 
// t2.grade '2018Grade', t2.subject_name '2018Subject', t2.Total '2018Total', t2.MoreOrEqual75 '#2018Above', t2.MoreOrEqual75P '2018Above', t2.MoreOrEqual65 '#2018Minimum', t2.MoreOrEqual65P '2018Minimum', t2.Below65 '#2018Below', t2.Below65P '2018Below',
// t2.AVG '2018AVG',
// t3.grade '2019Grade', t3.subject_name '2019Subject', t3.Total '2019Total', t3.MoreOrEqual75 '#2019Above', t3.MoreOrEqual75P '2019Above', t3.MoreOrEqual65 '#2019Minimum', t3.MoreOrEqual65P '2019Minimum', t3.Below65 '#2019Below', t3.Below65P '2019Below',
// t3.AVG '2019AVG',

//     ROUND((((((t2.AVG - t1.AVG) / t1.AVG) * 100) + (((t3.AVG - t2.AVG) / t2.AVG) * 100)) /2), 1) AS 'Trend'
       
// FROM ( 
//         (
//             SELECT subject_name,exam_name,acd_code,grade,
//             COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total',

//             COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) AS 'Below65',
//             ROUND(COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'Below65P',

//             COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
//             ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

//             COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
//             ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
//             AVG(exam_mark) AS 'AVG'

        
//         FROM new_marks
        
//         WHERE
//             (acd_code = '2016 / 2017')
//             AND
//             (REPLACE(exam_name, ' ', '') = REPLACE('$exam', ' ', ''))  
//             AND
//             (subject_name like '$subject')
        
//         GROUP BY grade
//         ORDER BY grade) t1

// LEFT JOIN

//         (
//             SELECT subject_name,exam_name,acd_code,grade,
//             COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total',

//                   COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) AS 'Below65',
//             ROUND(COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'Below65P',

//             COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
//             ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

//             COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
//             ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
//             TRUNCATE(AVG(exam_mark),0) AS 'AVG'
        
//         FROM new_marks
        
//         WHERE
//             (acd_code = '2017 / 2018')
//             AND
//             (REPLACE(exam_name, ' ', '') = REPLACE('$exam', ' ', '')) 
//             AND
//             (subject_name like '$subject')
        
//         GROUP BY grade
//         ORDER BY grade) t2
        
//         ON (t1.subject_name = t2.subject_name AND t1.grade = t2.grade)

// LEFT JOIN

//         (
//             SELECT subject_name,exam_name,acd_code,grade,
//             COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total',

//             COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0,1,NULL)) AS 'Below65',
//             ROUND(COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'Below65P',

//             COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
//             ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

//             COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
//             ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
//             TRUNCATE(AVG(exam_mark),0) AS 'AVG'
        
//         FROM new_marks
        
//         WHERE
//             (acd_code = '2018 / 2019')
//             AND
//             (REPLACE(exam_name, ' ', '') = REPLACE('$exam', ' ', '')) 
//             AND
//             (subject_name like '$subject')
        
//         GROUP BY grade
//         ORDER BY grade) t3
        
//         ON (t2.subject_name = t3.subject_name AND t2.grade = t3.grade)
// )

// UNION

// SELECT 
// t1.grade '2017Grade', t1.subject_name '2017Subject', t1.Total '2017Total', t1.MoreOrEqual75 '#2017Above', t1.MoreOrEqual75P '2017Above', t1.MoreOrEqual65 '#2017Minimum', t1.MoreOrEqual65P '2017Minimum', t1.Below65 '#2017Below', t1.Below65P '2017Below', 
// t1.AVG '2017AVG',
// t2.grade '2018Grade', t2.subject_name '2018Subject', t2.Total '2018Total', t2.MoreOrEqual75 '#2018Above', t2.MoreOrEqual75P '2018Above', t2.MoreOrEqual65 '#2018Minimum', t2.MoreOrEqual65P '2018Minimum', t2.Below65 '#2018Below', t2.Below65P '2018Below', 
// t2.AVG '2018AVG',
// t3.grade '2019Grade', t3.subject_name '2019Subject', t3.Total '2019Total', t3.MoreOrEqual75 '#2019Above', t3.MoreOrEqual75P '2019Above', t3.MoreOrEqual65 '#2019Minimum', t3.MoreOrEqual65P '2019Minimum', t3.Below65 '#2019Below', t3.Below65P '2019Below', 
// t3.AVG '2019AVG',

//     ROUND((((((t2.AVG - t1.AVG) / t1.AVG) * 100) + (((t3.AVG - t2.AVG) / t2.AVG) * 100)) /2), 1) AS 'Trend'

// FROM ( 
//         (
//             SELECT subject_name,exam_name,acd_code,grade,
//             COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total',

//             COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0,1,NULL)) AS 'Below65',
//             ROUND(COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'Below65P',

//             COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
//             ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

//             COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
//             ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
//             TRUNCATE(AVG(exam_mark),0) AS 'AVG'
        
//         FROM new_marks
        
//         WHERE
//             (acd_code = '2016 / 2017')
//             AND
//             (REPLACE(exam_name, ' ', '') = REPLACE('$exam', ' ', ''))  
//             AND
//             (subject_name like '$subject')
        
//         GROUP BY grade
//         ORDER BY grade) t1

// RIGHT JOIN

//         (
//             SELECT subject_name,exam_name,acd_code,grade,
//             COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total',

//             COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0,1,NULL)) AS 'Below65',
//             ROUND(COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'Below65P',

//             COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
//             ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

//             COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
//             ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
//             TRUNCATE(AVG(exam_mark),0) AS 'AVG'
        
//         FROM new_marks
        
//         WHERE
//             (acd_code = '2017 / 2018')
//             AND
//             (REPLACE(exam_name, ' ', '') = REPLACE('$exam', ' ', '')) 
//             AND
//             (subject_name like '$subject')
        
//         GROUP BY grade
//         ORDER BY grade) t2
        
//         ON (t1.subject_name = t2.subject_name AND t1.grade = t2.grade)

// RIGHT JOIN

//         (
//             SELECT subject_name,exam_name,acd_code,grade,
//             COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total',

//             COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0,1,NULL)) AS 'Below65',
//             ROUND(COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'Below65P',

//             COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
//             ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

//             COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
//             ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
//             TRUNCATE(AVG(exam_mark),0) AS 'AVG'
        
//         FROM new_marks
        
//         WHERE
//             (acd_code = '2018 / 2019')
//             AND
//             (REPLACE(exam_name, ' ', '') = REPLACE('$exam', ' ', '')) 
//             AND
//             (subject_name like '$subject')
        
//         GROUP BY grade
//         ORDER BY grade) t3
        
//         ON (t2.subject_name = t3.subject_name AND t2.grade = t3.grade)
// )


// ";


$sql = "
SELECT 
t1.grade '2017Grade', t1.subject_name '2017Subject', t1.Total '2017Total', t1.MoreOrEqual75 '#2017Above', t1.MoreOrEqual75P '2017Above', t1.MoreOrEqual65 '#2017Minimum', t1.MoreOrEqual65P '2017Minimum', t1.Below65 '#2017Below', t1.Below65P '2017Below', t1.AVG '2017AVG', 
t2.grade '2018Grade', t2.subject_name '2018Subject', t2.Total '2018Total', t2.MoreOrEqual75 '#2018Above', t2.MoreOrEqual75P '2018Above', t2.MoreOrEqual65 '#2018Minimum', t2.MoreOrEqual65P '2018Minimum', t2.Below65 '#2018Below', t2.Below65P '2018Below',t2.AVG '2018AVG',
t3.grade '2019Grade', t3.subject_name '2019Subject', t3.Total '2019Total', t3.MoreOrEqual75 '#2019Above', t3.MoreOrEqual75P '2019Above', t3.MoreOrEqual65 '#2019Minimum', t3.MoreOrEqual65P '2019Minimum', t3.Below65 '#2019Below', t3.Below65P '2019Below',t3.AVG '2019AVG',
ROUND((((((t2.AVG - t1.AVG) / t1.AVG) * 100) + (((t3.AVG - t2.AVG) / t2.AVG) * 100)) /2), 1) AS 'Trend'
       
FROM ( 
        (
            SELECT subject_name,exam_name,acd_code,grade,
            COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total',

            COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) AS 'Below65',
            ROUND(COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'Below65P',

            COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
            ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

            COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
            ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
            AVG(exam_mark) AS 'AVG'

        
        FROM new_marks
        
        WHERE
            (acd_code = '2016 / 2017')
            AND
            (REPLACE(exam_name, ' ', '') = REPLACE('$exam', ' ', ''))  
            AND
            (subject_name like '$subject')
        
        GROUP BY grade
        ORDER BY grade) t1

LEFT JOIN

        (
            SELECT subject_name,exam_name,acd_code,grade,
            COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total',

                  COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) AS 'Below65',
            ROUND(COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'Below65P',

            COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
            ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

            COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
            ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
            TRUNCATE(AVG(exam_mark),0) AS 'AVG'
        
        FROM new_marks
        
        WHERE
            (acd_code = '2017 / 2018')
            AND
            (REPLACE(exam_name, ' ', '') = REPLACE('$exam', ' ', '')) 
            AND
            (subject_name like '$subject')
        
        GROUP BY grade
        ORDER BY grade) t2
        
        ON (t1.subject_name = t2.subject_name AND t1.grade = t2.grade)

LEFT JOIN

        (
            SELECT subject_name,exam_name,acd_code,grade,
            COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) 'Total',

            COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0,1,NULL)) AS 'Below65',
            ROUND(COUNT(IF(exam_mark < 65 AND exam_mark IS NOT NULL AND exam_mark > 0,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'Below65P',

            COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual65',
            ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark < 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual65P',

            COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
            ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL AND exam_mark > 0, 1, NULL)) * 100,0) AS 'MoreOrEqual75P',
            TRUNCATE(AVG(exam_mark),0) AS 'AVG'
        
        FROM new_marks
        
        WHERE
            (acd_code = '2018 / 2019')
            AND
            (REPLACE(exam_name, ' ', '') = REPLACE('$exam', ' ', '')) 
            AND
            (subject_name like '$subject')
        
        GROUP BY grade
        ORDER BY grade) t3
        
        ON (t2.subject_name = t3.subject_name AND t2.grade = t3.grade)
)
";

// echo "SQL STATEMENT <br> " . $sql;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
            echo   "<tr>";

            echo   "<th class='VGrade'>" . $row['2017Grade']   . "</th>
                    <td>" . $row['2017Total']                                  . "</td>
                    <td>(" . $row['#2017Above']   . ") " . $row['2017Above']   . "%</td>
                    <td>(" . $row['#2017Minimum'] . ") " . $row['2017Minimum'] . "%</td>
                    <td>(" . $row['#2017Below']   . ") " . $row['2017Below']   . "%</td>";
            
             echo   "<td>". $row['2018Total']  . "</td>
                    <td>(" . $row['#2018Above']   . ") " . $row['2018Above']   . "%</td>
                    <td>(" . $row['#2018Minimum'] . ") " . $row['2018Minimum'] . "%</td>
                    <td>(" . $row['#2018Below']   . ") " . $row['2018Below']   . "%</td>";

             echo   "<td>" . $row['2019Total']  . "</td>
                    <td>(" . $row['#2019Above']   . ") " . $row['2019Above']   . "%</td>
                    <td>(" . $row['#2019Minimum'] . ") " . $row['2019Minimum'] . "%</td>
                    <td>(" . $row['#2019Below']   . ") " . $row['2019Below']   . "%</td>";
                    
            
            if ($row['2019Above'] >= 75 )
                echo "<td class='w3-text-green'>Outstanding</td>";
            elseif ($row['2019Above'] >= 61 AND $row['2019Above'] < 75) 
                echo "<td class='w3-container w3-text-light-green'>Very Good</td>";
            elseif ($row['2019Above'] >= 51 AND $row['2019Above'] < 61)
                echo "<td class='w3-container w3-text-blue'>Good</td>";
            elseif ($row['2019Minimum'] >= 75)
                echo "<td class='w3-container w3-text-orange'>Acceptable</td>";
            else
                echo "<td class='w3-container w3-text-gray'>Weak</td>";
                
            $trend = round(($row['#2017Above'] + $row['#2018Above'] + $row['#2019Above']) / ($row['2017Total'] + $row['2018Total'] + $row['2019Total']) * 100);
            if ($trend >= 75 ) {
                echo "<td class='w3-text-green'>Outstanding";
                echo "<div class='tooltip'>Hover over me
                        <span class='tooltiptext'>Tooltip text</span>
                     </div></td>";
                 }

            elseif ($trend >= 61 AND $row['2019Above'] < 75) 
                echo "<td class='w3-container w3-text-light-green'>Very Good</td>";
            elseif ($trend >= 51 AND $row['2019Above'] < 61)
                echo "<td class='w3-container w3-text-blue'>Good</td>";
            elseif ($trend >= 75)
                echo "<td class='w3-container w3-text-orange'>Acceptable</td>";
            else
                echo "<td class='w3-container w3-text-gray'>Weak</td>";
            echo "</tr>";
    }
    echo "<tr><th class='w3-yellow' colspan=13>Overall judjment</th>
              <td></td><td></td></tr>";
}	
$conn->close();