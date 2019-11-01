<?php

include ('../config/dbConfig.php');


$years = $_REQUEST["year"];
$terms = $_REQUEST["term"];
$grades = $_REQUEST["grade"]; 


$sql =   "SELECT acd_code 'Year', exam_name 'Exam', grade, section, "
        ."COUNT(IF (exam_mark IS NOT NULL, 1, NULL)) 'Total', "

        ."COUNT(IF (exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) as '>=65', "
        ."ROUND( COUNT(IF (exam_mark >= 65 AND exam_mark IS NOT NULL, 1, NULL)) / COUNT(IF (exam_mark IS NOT NULL, 1, NULL)) * 100, 0)  as '>=65%', "

        ."COUNT(IF (exam_mark >= 75 AND exam_mark IS NOT NULL, 1, NULL)) as '>=75', "
        ."ROUND( COUNT(IF (exam_mark >= 75 AND exam_mark IS NOT NULL, 1, NULL)) / COUNT(IF (exam_mark IS NOT NULL, 1, NULL)) * 100, 0)  as '>=75%', 
        subject_name

        FROM new_marks ";

    if ($years != "" and $terms != "" and $grades != "")
        $sql = $sql ."WHERE acd_code = '$years' and grade = '$grades' and exam_name = '$terms' "
                    ."GROUP BY subject_name ORDER BY `>=75%` DESC, `>=65%` DESC";

// echo $sql;
$result = $conn->query($sql);
$rownumber = 1;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Outstanding
        if ($row[">=75%"] >= 75)
        {
            echo "<tr  class='w3-hover-green w3-border-0'>
                    <td>" . $row["subject_name"] . "</td>
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

$conn->close();