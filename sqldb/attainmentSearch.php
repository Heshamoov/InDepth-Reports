<?php

include ('../config/dbConfig.php');

$years = $_REQUEST["years"];
$terms = $_REQUEST["terms"];
$grades = $_REQUEST["grades"]; 
$batches = $_REQUEST["batches"];
$subjects = $_REQUEST["subjects"];
$gender = $_REQUEST["gender"];
$category = $_REQUEST["category"];


$sql =   "SELECT academic_years.name 'Year', exam_groups.name 'Exam', CONCAT(courses.course_name, ' - ', batches.name) as Grade, COUNT(subjects.name) 'Total', "

        ."COUNT(IF (exam_scores.marks = 50, 1, NULL)) as '=50', "
        ."ROUND( COUNT(IF (exam_scores.marks = 50, 1, NULL)) / COUNT(subjects.name) * 100, 2)  as '=50%', "

        ."COUNT(IF (exam_scores.marks > 50, 1, NULL)) as '>50', "
        ."ROUND( COUNT(IF (exam_scores.marks > 50, 1, NULL)) / COUNT(subjects.name) * 100, 2)  as '>50%', "
        
        ."COUNT(IF (exam_scores.marks = 60, 1, NULL))as '=60', "
        ."ROUND( COUNT(IF (exam_scores.marks = 60, 1, NULL)) / COUNT(subjects.name) * 100, 2)  as '=60%', "

        ."COUNT(IF (exam_scores.marks = 65, 1, NULL)) as '=65', "
        ."ROUND( COUNT(IF (exam_scores.marks = 65, 1, NULL)) / COUNT(subjects.name) * 100, 2)  as '=65%', "

        ."COUNT(IF (exam_scores.marks > 65, 1, NULL)) as '>65', "
        ."ROUND( COUNT(IF (exam_scores.marks > 65, 1, NULL)) / COUNT(subjects.name) * 100, 2)  as '>65%', "
        
        ."COUNT(IF (exam_scores.marks > 70, 1, NULL)) as '>70', "
        ."ROUND( COUNT(IF (exam_scores.marks > 70, 1, NULL)) / COUNT(subjects.name) * 100, 2)  as '>70%', "

        ."COUNT(IF (exam_scores.marks > 75, 1, NULL)) as '>75', "
        ."ROUND( COUNT(IF (exam_scores.marks > 75, 1, NULL)) / COUNT(subjects.name) * 100, 2)  as '>75%', "
        
        ."subjects.name 'Subject' "
        
    ."FROM (((((((( "
        ."academic_years "
        ."INNER JOIN batches ON academic_years.id = batches.academic_year_id) "
        ."INNER JOIN courses ON batches.course_id = courses.id) "
        ."INNER JOIN exam_groups ON batches.id = exam_groups.batch_id) "
        ."INNER JOIN exams ON exam_groups.id = exams.exam_group_id) "
        ."INNER JOIN subjects ON exams.subject_id = subjects.id) "
        ."INNER JOIN exam_scores ON exams.id = exam_scores.exam_id) "
        ."INNER JOIN students ON exam_scores.student_id = students.id) "
        ."LEFT JOIN student_categories ON students.student_category_id = student_categories.id) ";



    if ($years == "" and $terms == "" and $grades == "" and $batches == "" and $gender == "" and $category == "" and $subjects == "")
        $sql = $sql ."WHERE academic_years.name = '2018 - 2019' "
                    ."AND courses.course_name = 'GR 1' "
                    ."AND batches.name = 'A2019' "
                    ."AND exam_groups.name = 'Term1-2019' "
                    ."GROUP BY subjects.name";
    else
        $sql = $sql ."WHERE $years $grades $batches $terms  $gender $category $subjects "
                    ."GROUP BY subjects.name";                            

			// echo $sql;
$result = $conn->query($sql);
$rownumber = 1;
if ($result->num_rows > 0) {
    echo  "<thead>"
            . "<tr id =out class= w3-custom >"
                . "<th>Year</th><th>Exam</th>"
                . "<th>Grade</th>"
                . "<th>Total</th><th>Count</th><th>Ratio</th>"
                . "<th>Attended</th><th>Subject</th>"
            . "</tr>"
         ."</thead>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>"
            . "<td>" . $row["Year"]  . "</td>"
            . "<td>" . $row["Exam"]  . "</td>"
            . "<td>" . $row["Grade"] . "</td>"
            . "<td>" . $row["Total"] . "</td>";
                    
        if (
                $row[">75%"] >= 75 and 
                (
                    (strpos($row["Subject"], 'Computer') !== false)
                or  (strpos($row["Subject"], 'Math') !== false)
                or  (strpos($row["Subject"], 'Science') !== false)
                or  (strpos($row["Subject"], 'English') !== false)
                or  (strpos($row["Subject"], 'Art') !== false)
                or  (strpos($row["Subject"], 'Physical') !== false)
                )
            ) 
        {
            echo "<td>" . $row[">75"] . "</td>";
            echo "<td>" . $row[">75%"] . "%</td><td style= 'background:green; color:white'>Outstanding</td>";
            echo "<td>" . $row["Subject"] . "</td>";
            echo "<tr><td colspan=8 style=text-align:center;>75% of Students scored Greater than 75% - US Curriculum</td></tr>";
        }
        elseif (
                $row[">75%"] >= 60 and 
                (
                    (strpos($row["Subject"], 'Computer') !== false)
                or  (strpos($row["Subject"], 'Math') !== false)
                or  (strpos($row["Subject"], 'Science') !== false)
                or  (strpos($row["Subject"], 'English') !== false)
                or  (strpos($row["Subject"], 'Art') !== false)
                or  (strpos($row["Subject"], 'Physical') !== false)
                )
            )
        {
            echo "<td>" . $row[">75"] . "</td>";
            echo "<td>" . $row[">75%"] . "%</td><td style= 'background:green; color:white'>Very Good</td>";
            echo "<td>" . $row["Subject"] . "</td></tr>";
            echo "<tr><td colspan=8 style=text-align:center;>60% of Students scored Greater than 75% - US Curriculum</td></tr>";
        }
        elseif ($row[">65%"] >= 50 and 
                (
                    (strpos($row["Subject"], 'Computer') !== false)
                or  (strpos($row["Subject"], 'Math') !== false)
                or  (strpos($row["Subject"], 'Science') !== false)
                or  (strpos($row["Subject"], 'English') !== false)
                or  (strpos($row["Subject"], 'Art') !== false)
                or  (strpos($row["Subject"], 'Physical') !== false)
                )
            ) 
        {
            echo "<td>" . $row[">65"] . "</td>";
            echo "<td>" . $row[">65%"] . "%</td><td style= 'background:green; color:white'>Good</td>";
            echo "<td>" . $row["Subject"] . "</td></tr>";
            echo "<tr><td colspan=8 style=text-align:center;> Greater than or Equal to 50% of Students scored Greater than 65% - US Curriculum</td></tr>";
        }
        elseif ($row["=65%"] >= 75 and 
                (
                    (strpos($row["Subject"], 'Computer') !== false)
                or  (strpos($row["Subject"], 'Math') !== false)
                or  (strpos($row["Subject"], 'Science') !== false)
                or  (strpos($row["Subject"], 'English') !== false)
                or  (strpos($row["Subject"], 'Art') !== false)
                or  (strpos($row["Subject"], 'Physical') !== false)
                )
            ) 
        {
            echo "<td>" . $row["=65"] . "</td>";
            echo "<td>" . $row["=65%"] . "%</td><td style= 'background:green; color:white'>Acceptible</td>";
            echo "<td>" . $row["Subject"] . "</td></tr>";
            echo "<tr><td colspan=8 style=text-align:center;> Greater than or Equal to 75% of Students scored Equal to 65% - US Curriculum</td></tr>";            
        }
        elseif ($row[">70%"] >= 75 and 
                (
                    (strpos($row["Subject"], 'Arabic') !== false)
                or  (strpos($row["Subject"], 'Islamic') !== false)
                or  (strpos($row["Subject"], 'Social') !== false)
                or  (strpos($row["Subject"], 'Moral') !== false)
                )
            )
        {
            echo "<td>" . $row[">70"] . "</td>";
            echo "<td>" . $row[">70%"] . "%</td><td style= 'background:green; color:white'>Outstanding</td>";
            echo "<td>" . $row["Subject"] . "</td></tr>";
            echo "<tr><td colspan=8 style=text-align:center;>Greater than or Equal to 75% of Students scored Greater than 70% - UAE Curriculum</td></tr>";
        }
        elseif ($row[">70%"] >= 60 and 
                (
                    (strpos($row["Subject"], 'Arabic') !== false)
                or  (strpos($row["Subject"], 'Islamic') !== false)
                or  (strpos($row["Subject"], 'Social') !== false)
                or  (strpos($row["Subject"], 'Moral') !== false)
                )
            )
        {
            echo "<td>" . $row[">=60"] . "</td>";
            echo "<td>" . $row["=70%"] . "%</td><td style= 'background:green; color:white'>Very Good</td>";
            echo "<td>" . $row["Subject"] . "</td></tr>";
            echo "<tr><td colspan=8 style=text-align:center;>Greater than or Equal to 60% of Students scored Greater than 70% - UAE Curriculum</td></tr>";
        }
        elseif ($row[">50%"] >= 50 and 
                (
                    (strpos($row["Subject"], 'Arabic') !== false)
                or  (strpos($row["Subject"], 'Islamic') !== false)
                or  (strpos($row["Subject"], 'Social') !== false)
                or  (strpos($row["Subject"], 'Moral') !== false)
                )
            )
        {
            echo "<td>" . $row[">50"] . "</td>";
            echo "<td>" . $row["=50%"] . "%</td><td style= 'background:green; color:white'>Good</td>";
            echo "<td>" . $row["Subject"] . "</td></tr>";
            echo "<tr><td colspan=8 style=text-align:center;>Greater than or Equal to 50% of Students scored Greater than 50% - UAE Curriculum</td></tr>";
        }
        if ($row["=60%"] >= 75 and 
                (
                    (strpos($row["Subject"], 'Arabic') !== false)
                or  (strpos($row["Subject"], 'Islamic') !== false)
                or  (strpos($row["Subject"], 'Social') !== false)
                or  (strpos($row["Subject"], 'Moral') !== false)
                )
            )
        {
            echo "<td>" . $row["=60"] . "</td>";
            echo "<td>" . $row["=60%"] . "%</td><td style= 'background:green; color:white'>Acceptible</td>";
            echo "<td>" . $row["Subject"] . "</td></tr>";
            echo "<tr><td colspan=8 style=text-align:center;>Greater than or Equal to 75% of Students scored Equal to 60% - UAE Curriculum</td></tr>";
        }
        elseif ($row["=50%"] >= 75 and 
                (
                    (strpos($row["Subject"], 'Arabic') !== false)
                or  (strpos($row["Subject"], 'Islamic') !== false)
                or  (strpos($row["Subject"], 'Social') !== false)
                or  (strpos($row["Subject"], 'Moral') !== false)
                )
            )
        {
            echo "<td>" . $row["=50"] . "</td>";
            echo "<td>" . $row["=50%"] . "%</td><td style= 'background:green; color:white'>Acceptible</td>";
            echo "<td>" . $row["Subject"] . "</td></tr>";
            echo "<tr><td colspan=8 style=text-align:center;>Greater than or Equal to 75% of Students scored Equal to 50% - UAE Curriculum</td></tr>";
        }
    }

} else
    echo "Data Not Found, try to import it to DB";

$conn->close();