<?php

include ('../config/dbConfig.php');


$year = $_REQUEST["yearValue"];

echo $year;


// $YArray = array();
// if ($_REQUEST["years1"] != "") $YArray[0] = $_REQUEST["years1"];
// if ($_REQUEST["years2"] != "") $YArray[1] = $_REQUEST["years2"];
// if ($_REQUEST["years3"] != "") $YArray[2] = $_REQUEST["years3"];
// if ($_REQUEST["years4"] != "") $YArray[3] = $_REQUEST["years4"];
// if ($_REQUEST["years5"] != "") $YArray[4] = $_REQUEST["years5"];


// $TArray = array();
// if ($_REQUEST["terms1"] != "") $TArray[0] = $_REQUEST["terms1"];
// if ($_REQUEST["terms2"] != "") $TArray[1] = $_REQUEST["terms2"];
// if ($_REQUEST["terms3"] != "") $TArray[2] = $_REQUEST["terms3"];
// if ($_REQUEST["terms4"] != "") $TArray[3] = $_REQUEST["terms4"];
// if ($_REQUEST["terms5"] != "") $TArray[4] = $_REQUEST["terms5"];

// //for($i = 0; $i < count($YArray); $i++)
// //    echo $YArray[$i] . "<br>";
// //echo "****************<br>";
//echo count($YArray);
//
//
//for($i = 0; $i < count($TArray); $i++)
//    echo $TArray[$i] . "<br>";
//echo "****************<br>";
//echo count($TArray);


// $columns = "SELECT subject_name, exam_name, acd_code, grade, section,
//                 COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) 'Total',
//                 COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) AS 'MoreOrEqual65',
//                 ROUND(COUNT(IF(exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100, 0) AS 'MoreOrEqual65P',
//                 COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) AS 'MoreOrEqual75',
//                 ROUND(COUNT(IF(exam_mark >= 75 AND exam_mark IS NOT NULL,1,NULL)) / COUNT(IF(exam_mark IS NOT NULL, 1, NULL)) * 100,0) AS 'MoreOrEqual75P'
                
//                 FROM new_marks ";


// $QArray = array();

// if ($grades != "")
// {
    
//     for($i = 0; $i < count($YArray); $i++)        
//         $QArray[$i] = " t$i.subject_name 'Subject$i', t$i.exam_name 'Exam$i', t$i.acd_code 'Year$i', t$i.grade 'Grade$i', t$i.MoreOrEqual65P '>=65%$i', t$i.MoreOrEqual75P '>=75%$i'";

    
//     $TopColumns = "SELECT " . $QArray[0];
//     for($i = 1; $i < count($QArray); $i++)
//         $TopColumns .= ", " . $QArray[$i];

//     $WhereArray = array();
//     for($i = 0; $i < count($YArray); $i++) {
//         $WhereArray[$i] = "
//         WHERE
//             $grades AND $YArray[$i] AND $TArray[$i] 

//         GROUP BY subject_name

//         ORDER BY subject_name ";
//     }

//     $From  = " FROM ";
//     $Table = "($columns $WhereArray[0]) t0";

//     for ($i=1; $i < count($WhereArray); $i++) { 
//         $From .= "(";
//         $Table .= " INNER JOIN ($columns $WhereArray[$i]) t$i ON (t0.subject_name = t$i.subject_name) )";
//     }

//     $sql = "$TopColumns $From $Table";

//     // $sql = $TopColumns .  " FROM (
                                
//     //                                 (
//     //                                     (
//     //                                         (
//     //                                             ($columns $WhereArray[0]) t0
//     //                                         INNER JOIN 
//     //                                             ($columns $WhereArray[1]) t1 ON (t0.subject_name = t1.subject_name)
//     //                                         )

//     //                                     INNER JOIN
//     //                                         ($columns $WhereArray[2]) t2 ON (t0.subject_name = t2.subject_name)
//     //                                     )
//     //                                 INNER JOIN 
//     //                                     ($columns $WhereArray[3]) t3 ON (t0.subject_name = t3.subject_name)
//     //                                 )
//     //                             INNER JOIN 
//     //                                 ($columns $WhereArray[4]) t4 ON (t0.subject_name = t4.subject_name)
//     //                               );";
                                    


//     // echo "SQL STATEMENT <br> " . $sql;
//     $result = $conn->query($sql);
//     if ($result->num_rows > 0) {
//         while ($row = $result->fetch_assoc()) {

//             echo "<tr><td>" . $row["Subject0"] . "</td>";

//             for ($i=0; $i < count($YArray); $i++) {
            
//                 if ($view == 'Attainment')
//                     if ($row[">=75%$i"] >= 75)                                    // Outstanding
//                         echo "<td class='w3-container w3-text-green w3-hover-green'>           Outstanding</td>";
//                     elseif ($row[">=75%$i"] >= 61 and $row[">=75%$i"] < 75)       // Very Good
//                         echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good</td>";
//                     elseif ($row[">=75%$i"] >= 50  and $row[">=75%$i"] <= 61)       // Good
//                         echo "<td class='w3-container w3-text-lime w3-hover-lime'>              Good</td>";
//                     elseif ($row[">=65%$i"] >= 75)                                 // Acceptable
//                         echo "<td class='w3-container w3-text-orange w3-hover-orange'>          Acceptable</td>";
//                     else                                                          // Not Applicable
//                         echo "<td class='w3-container w3-text-red w3-hover-red'>                Not Applicable</td>";
                
//                 elseif ($view == 'Percentage') 
//                     if ($row[">=75%$i"] >= 75)                                    // Outstanding
//                         echo "<td class='w3-container w3-text-green w3-hover-green'>".$row[">=75%$i"]. "%</td>";
//                     elseif ($row[">=75%$i"] >= 61 and $row[">=75%$i"] < 75)       // Very Good
//                         echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>".$row[">=75%$i"]. "%</td>";
//                     elseif ($row[">=75%$i"] >= 50  and $row[">=75%$i"] <= 61)       // Good
//                         echo "<td class='w3-container w3-text-lime w3-hover-lime'>".$row[">=75%$i"]. "%</td>";
//                     elseif ($row[">=65%$i"] >= 75)                                 // Acceptable
//                         echo "<td class='w3-container w3-text-orange w3-hover-orange'>".$row[">=65%$i"]. "%</td>";
//                     else                                                          // Not Applicable
//                         echo "<td class='w3-container w3-text-red w3-hover-red'>".$row[">=75%$i"]. "%</td>";

//                 elseif ($view == 'Attainment - Percentage')
//                     if ($row[">=75%$i"] >= 75)                                    // Outstanding
//                         echo "<td class='w3-container w3-text-green w3-hover-green'>           Outstanding - ".$row[">=75%$i"]. "%</td>";
//                     elseif ($row[">=75%$i"] >= 61 and $row[">=75%$i"] < 75)       // Very Good
//                         echo "<td class='w3-container w3-text-light-green w3-hover-light-green'>Very Good - ".$row[">=75%$i"]. "%</td>";
//                     elseif ($row[">=75%$i"] >= 50  and $row[">=75%$i"] <= 61)       // Good
//                         echo "<td class='w3-container w3-text-lime w3-hover-lime'>              Good - ".$row[">=75%$i"]. "%</td>";
//                     elseif ($row[">=65%$i"] >= 75)                                 // Acceptable
//                         echo "<td class='w3-container w3-text-orange w3-hover-orange'>          Acceptable - ".$row[">=65%$i"]. "%</td>";
//                     else                                                          // Not Applicable
//                         echo "<td class='w3-container w3-text-red w3-hover-red'>                Not Applicable - ".$row[">=75%$i"]. "%</td>";
//             }

//             // echo "<td>-</td><td>-</td><td>-</td><td>-</td>";
//             echo "</tr>";
//         }
//     }
// }
// else
//     echo "Select Grade!";
// $conn->close();