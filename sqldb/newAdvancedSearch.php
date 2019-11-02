<?php

include ('../config/dbConfig.php');


$grades = $_REQUEST["grades"];

$years1 = $_REQUEST["years1"];
$years2 = $_REQUEST["years2"];

$terms1 = $_REQUEST["terms1"];
$terms2 = $_REQUEST["terms2"];



// echo $grades . "<br>";
// echo $years1 . "<br>";
// echo $terms1 . "<br>";

// echo $years2 . "<br>";
// echo $terms2 . "<br>";

$sql =   "SELECT acd_code 'Year', exam_name 'Exam', grade, section, "
        ."COUNT(IF (exam_mark IS NOT NULL, 1, NULL)) 'Total', "

        ."COUNT(IF (exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) as '>=65', "
        ."ROUND( COUNT(IF (exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) / COUNT(IF (exam_mark IS NOT NULL, 1, NULL)) * 100, 0)  as '>=65%', "

        ."COUNT(IF (exam_mark >= 75 AND exam_mark IS NOT NULL, 1, NULL)) as '>=75', "
        ."ROUND( COUNT(IF (exam_mark >= 75 AND exam_mark IS NOT NULL, 1, NULL)) / COUNT(IF (exam_mark IS NOT NULL, 1, NULL)) * 100, 0)  as '>=75%', 
        subject_name

        FROM new_marks
        GROUP BY acd_code, grade, exam_name, subject_name ";

    if ($years1 != "" and $terms1 != "" and $grades != "")
    {
        $results1 = $sql ."HAVING $grades AND $years1 AND $terms1 
                            ORDER BY acd_code, grade, exam_name, subject_name";

// echo $results1;
$result = $conn->query($results1);
$rownumber = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rownumber ++;
        // Outstanding
        if ($row[">=75%"] >= 75)
        {
            echo "<tr  class='w3-hover-green w3-border-0'>
                    <td>" . $row["subject_name"] . "</td>
                    <td class='w3-container w3-green'>Outstanding</td>
                    <td id= 'c2r$rownumber'>-</td>
                    <td id= 'c3r$rownumber'>-</td>
                    <td id= 'c4r$rownumber'>-</td>
                    <td id= 'c5r$rownumber'>-</td>
                  </tr>";
        }

        // Very Good
        elseif ($row[">=75%"] >= 61 and $row[">=75%"] < 75)
        {
 
            echo "<tr  class='w3-hover-light-green w3-border-0'>
                    <td>" . $row["subject_name"] . "</td>
                    <td class='w3-container w3-hover-light-green'>Very Good</td>
                    <td id= 'c2r$rownumber'>-</td>
                    <td id= 'c3r$rownumber'>-</td>
                    <td id= 'c4r$rownumber'>-</td>
                    <td id= 'c5r$rownumber'>-</td>
                </tr>";
        }

        // Good
        elseif ($row[">=75%"] >= 50  and $row[">=75%"] <= 61)
        {
/*Year*/    echo "<tr  class='w3-hover-lime w3-border-0'>
                    <td>" . $row["subject_name"] . "</td>
                    <td class='w3-container w3-lime'>Good</td>
                    <td id= 'c2r$rownumber'>-</td>
                    <td id= 'c3r$rownumber'>-</td>
                    <td id= 'c4r$rownumber'>-</td>
                    <td id= 'c5r$rownumber'>-</td>
                </tr>";
            
        }

        // Acceptable
        elseif ($row[">=65%"] >= 75) 
        {
            echo "<tr  class='w3-hover-orange w3-border-0'>
                    <td>" . $row["subject_name"] . "</td>
                    <td class='w3-container w3-orange'>Acceptable</td>
                    <td id= 'c2r$rownumber'>-</td>
                    <td id= 'c3r$rownumber'>-</td>
                    <td id= 'c4r$rownumber'>-</td>
                    <td id= 'c5r$rownumber'>-</td>
                </tr>";
        }

        // Not Applicable
        else 
        {
            echo "<tr  class='w3-hover-red w3-border-0'>
                    <td>" . $row["subject_name"] . "</td>
                    <td class='w3-container w3-red'>Not Applicable</td>
                    <td id= 'c2r$rownumber'>-</td>
                    <td id= 'c3r$rownumber'>-</td>
                    <td id= 'c4r$rownumber'>-</td>
                    <td id= 'c5r$rownumber'>-</td>
                </tr>";
        }


    }
}
}


// Results 2
    if ($years2 != "" and $terms2 != "" and $grades != "")
    {
        $results2 = $sql ."WHERE $grades AND $years2 AND $terms2 "
                            ."GROUP BY subject_name ORDER BY `>=75%` DESC, `>=65%` DESC";

        // echo $results2;
        $result = $conn->query($results2);
        $rownumber = 1;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Outstanding
                if ($row[">=75%"] >= 75)
                {
                    echo "document.getElementById('c1r$rownumber').innerHTML =                              <td>" . $row["subject_name"] . "</td>
                            <td class='w3-container w3-green'>Outstanding</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                          </tr>";
                }

                // Very Good
                elseif ($row[">=75%"] >= 61 and $row[">=75%"] < 75)
                {
         
                    echo "<tr  class='w3-hover-light-green w3-border-0'>
                            <td>" . $row["subject_name"] . "</td>
                            <td class='w3-container w3-hover-light-green'>Very Good</td>
                            <td></td><td></td><td></td>
                        </tr>";
                }

                // Good
                elseif ($row[">=75%"] >= 50  and $row[">=75%"] <= 61)
                {
        /*Year*/    echo "<tr  class='w3-hover-lime w3-border-0'>
                            <td>" . $row["subject_name"] . "</td>
                            <td class='w3-container w3-lime'>Good</td>
                            <td></td><td></td><td></td>
                        </tr>";
                    
                }

                // Acceptable
                elseif ($row[">=65%"] >= 75) 
                {
                    echo "<tr  class='w3-hover-orange w3-border-0'>
                            <td>" . $row["subject_name"] . "</td>
                            <td class='w3-container w3-orange'>Acceptable</td>
                            <td></td><td></td><td></td>
                        </tr>";
                }

                // Not Applicable
                else 
                {
                    echo "<tr  class='w3-hover-red w3-border-0'>
                            <td>" . $row["subject_name"] . "</td>
                            <td class='w3-container w3-rd'>Not Applicable</td>
                            <td></td><td></td><td></td>
                        </tr>";
                }


            }
        }
    }

$conn->close();