<?php

include ('../config/dbConfig.php');

$years = $_REQUEST["years"];
$terms = $_REQUEST["terms"];
$grades = $_REQUEST["grades"]; 
$batches = $_REQUEST["batches"];
$subjects = $_REQUEST["subjects"];
$gender = $_REQUEST["gender"];
$category = $_REQUEST["category"];


$sql =   "SELECT academic_years.name 'Year', exam_groups.name 'Exam', CONCAT(courses.course_name, ' - ', batches.name) as Grade, "
        ."COUNT(IF (exam_scores.marks IS NOT NULL, 1, NULL)) 'Total', "

        ."COUNT(IF (exam_scores.marks = 50 AND exam_scores.marks IS NOT NULL, 1, NULL)) as '=50', "
        ."ROUND( COUNT(IF (exam_scores.marks = 50 AND exam_scores.marks IS NOT NULL, 1, NULL)) / COUNT(IF (exam_scores.marks IS NOT NULL, 1, NULL)) * 100, 0)  as '=50%', "

        ."COUNT(IF (exam_scores.marks > 50 AND exam_scores.marks IS NOT NULL, 1, NULL)) as '>50', "
        ."ROUND( COUNT(IF (exam_scores.marks > 50 AND exam_scores.marks IS NOT NULL, 1, NULL)) / COUNT(IF (exam_scores.marks IS NOT NULL, 1, NULL)) * 100, 0)  as '>50%', "
        
        ."COUNT(IF (exam_scores.marks = 60 AND exam_scores.marks IS NOT NULL, 1, NULL))as '=60', "
        ."ROUND( COUNT(IF (exam_scores.marks = 60 AND exam_scores.marks IS NOT NULL, 1, NULL)) / COUNT(IF (exam_scores.marks IS NOT NULL, 1, NULL)) * 100, 0)  as '=60%', "

        ."COUNT(IF (exam_scores.marks = 65 AND exam_scores.marks IS NOT NULL, 1, NULL)) as '=65', "
        ."ROUND( COUNT(IF (exam_scores.marks = 65 AND exam_scores.marks IS NOT NULL, 1, NULL)) / COUNT(IF (exam_scores.marks IS NOT NULL, 1, NULL)) * 100, 0)  as '=65%', "

        ."COUNT(IF (exam_scores.marks > 65 AND exam_scores.marks IS NOT NULL, 1, NULL)) as '>65', "
        ."ROUND( COUNT(IF (exam_scores.marks > 65 AND exam_scores.marks IS NOT NULL, 1, NULL)) / COUNT(IF (exam_scores.marks IS NOT NULL, 1, NULL)) * 100, 0)  as '>65%', "
        
        ."COUNT(IF (exam_scores.marks > 70 AND exam_scores.marks IS NOT NULL, 1, NULL)) as '>70', "
        ."ROUND( COUNT(IF (exam_scores.marks > 70 AND exam_scores.marks IS NOT NULL, 1, NULL)) / COUNT(IF (exam_scores.marks IS NOT NULL, 1, NULL)) * 100, 0)  as '>70%', "

        ."COUNT(IF (exam_scores.marks > 75 AND exam_scores.marks IS NOT NULL, 1, NULL)) as '>75', "
        ."ROUND( COUNT(IF (exam_scores.marks > 75 AND exam_scores.marks IS NOT NULL, 1, NULL)) / COUNT(IF (exam_scores.marks IS NOT NULL, 1, NULL)) * 100, 0)  as '>75%', "
        
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
                    ."GROUP BY subjects.name, batches.name, courses.course_name, exam_groups.name ";
    else
        $sql = $sql ."WHERE $years $grades $batches $terms  $gender $category $subjects "
                    ."GROUP BY ";
                    //   if ($subjects !== "") $sql = $sql . "subjects.name ";
                    //   if ($terms !== "") $sql = $sql . "exam_groups.name ";
                    if ($years !== "") $sql = $sql . "academic_years.name ";

                    if ($grades !== "")
                        if ($years !== "")
                            $sql = $sql . ",courses.course_name ";
                        else 
                            $sql = $sql . "courses.course_name ";

                    if ($batches !== "")
                        if ($years !== "" or $grades !== "")
                            $sql = $sql . ",batches.name ";
                        else
                            $sql = $sql . "batches.name";
    
                    if ($subjects !== "")
                        if ($years !== "" or $grades !== "" or $batches !== "")
                            $sql = $sql . ",subjects.name ";
                        else
                            $sql = $sql . "subjects.name";

                    if ($terms !== "")
                        if ($years !== "" or $grades !== "" or $batches !== "" or $subjects !== "")
                            $sql = $sql . ",exam_groups.name ";
                        else
                            $sql = $sql . "exam_groups.name";    

    $sql = $sql ."ORDER BY `>75%` DESC, `>70%` DESC, `>65%` DESC, `=65%` DESC, `=60%` DESC, `>50%` DESC, `=50%` DESC";

 echo $sql;
$result = $conn->query($sql);
$rownumber = 1;
if ($result->num_rows > 0) {
    echo  "<thead'>"
            . "<tr class='w3-custom'>"
                . "<th>Year</th><th>Exam</th>"
                . "<th>Grade</th>"
                . "<th>Total</th><th>Count</th><th>Ratio</th>"
                . "<th>Attainment</th><th>Subject</th>"
            . "</tr>"
         ."</thead>";

    while ($row = $result->fetch_assoc()) {
                    
        if (
                $row[">75%"] >= 75 and 
                (
                    (strpos($row["Subject"], 'Computer') !== false)
                or  (strpos($row["Subject"], 'Math') !== false)
                or  (strpos($row["Subject"], 'Science') !== false)
                or  (strpos($row["Subject"], 'English') !== false)
                or  (strpos($row["Subject"], 'Art') !== false)
                or  (strpos($row["Subject"], 'Physical') !== false)
                or  (strpos($row["Subject"], 'French') !== false)
                or  (strpos($row["Subject"], 'Biology') !== false)
                or  (strpos($row["Subject"], 'Business') !== false)
                or  (strpos($row["Subject"], 'Chemistry') !== false)
                or  (strpos($row["Subject"], 'History') !== false)
                )
            ) 
        {
/*Year*/    echo "<tr class='w3-hover-light-green w3-border-0'>"
                    . "<td>" . $row["Year"]  . "</td>";

/*Term*/    if ($terms !== "")
                echo "<td>" . $row["Exam"]  . "</td>";
            else
                echo "<td>ALL</td>";

/*Grade*/   if ($grades === "")
                echo "<td>ALL</td>";
            else
/*Section*/     if ($batches !== "")
                    echo "<td>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td>" . $arr[0] . "</td>";
                }

            echo "<td>" . $row["Total"] . "</td>";
            echo "<td>" . $row[">75"] . " above 75</td>";
            echo "<td>" . $row[">75%"] . "%</td><td class='w3-container w3-green'>Outstanding</td>";

            if ($subjects !== "")
                echo "<td>" . $row["Subject"] . "</td>";
            else
                echo "<td>ALL</td>";

            echo "<tr ></tr><tr class='w3-hover-green w3-border-bottom'>"
                ."<td colspan=8 style=text-align:center;>Greater than or Equal to 75% of Students scored Greater than 75% - US Curriculum</td><</tr>";
            // echo "<tr class='w3-blue-gray'><td colspan=8></td></tr>";
            
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
                or  (strpos($row["Subject"], 'French') !== false)
                or  (strpos($row["Subject"], 'Biology') !== false)
                or  (strpos($row["Subject"], 'Business') !== false)
                or  (strpos($row["Subject"], 'Chemistry') !== false)
                or  (strpos($row["Subject"], 'History') !== false)
                )
            )
        {

/*Year*/    echo "<tr class='w3-hover-light-green w3-border-0'>"
                    . "<td>" . $row["Year"]  . "</td>";

/*Term*/    if ($terms !== "")
                echo "<td>" . $row["Exam"]  . "</td>";
            else
                echo "<td>ALL</td>";

/*Grade*/   if ($grades === "")
                echo "<td>ALL</td>";
            else
/*Section*/     if ($batches !== "")
                    echo "<td>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td>" . $arr[0] . "</td>";
                }

/*Total*/   echo "<td>" . $row["Total"] . "</td>"
                . "<td>" . $row[">75"]   . " above 75</td>"
                . "<td>" . $row[">75%"]  . "%</td>"
                . "<td class='w3-container w3-light-green w3-text-white'>Very Good</td>";
            
/*Subject*/ if ($subjects !== "")
                echo "<td>" . $row["Subject"] . "</td>";
            else
                echo "<td>ALL</td>";                    

                echo "</tr><tr></tr>"
                    ."<tr class='w3-hover-light-green w3-border-bottom'>"
                        . "<td colspan=8 style=text-align:center;>"
                                ." Greater than or Equal to 60% of Students scored Greater than 75% - US Curriculum"
                        . "</td>"
                    ."</tr>";
        }
        elseif ($row[">65%"] >= 50 and 
                (
                    (strpos($row["Subject"], 'Computer') !== false)
                or  (strpos($row["Subject"], 'Math') !== false)
                or  (strpos($row["Subject"], 'Science') !== false)
                or  (strpos($row["Subject"], 'English') !== false)
                or  (strpos($row["Subject"], 'Art') !== false)
                or  (strpos($row["Subject"], 'Physical') !== false)
                or  (strpos($row["Subject"], 'French') !== false)
                or  (strpos($row["Subject"], 'Biology') !== false)
                or  (strpos($row["Subject"], 'Business') !== false)
                or  (strpos($row["Subject"], 'Chemistry') !== false)
                or  (strpos($row["Subject"], 'History') !== false)
                )
            ) 
        {
/*Year*/    echo "<tr class='w3-hover-lime w3-border-0'>"
                    . "<td>" . $row["Year"]  . "</td>";

/*Term*/    if ($terms !== "")
                echo "<td>" . $row["Exam"]  . "</td>";
            else
                echo "<td>ALL</td>";

/*Grade*/   if ($grades === "")
                echo "<td>ALL</td>";
            else
/*Section*/     if ($batches !== "")
                    echo "<td>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td>" . $arr[0] . "</td>";
                }
            echo "<td>" . $row["Total"] . "</td>";
            echo "<td>" . $row[">65"] . " above 65</td>";
            echo "<td>" . $row[">65%"] . "%</td><td class='w3-container w3-lime'>Good</td>";
            
            if ($subjects !== "")
                echo "<td>" . $row["Subject"] . "</td>";
            else
                echo "<td>ALL</td>";                    

            echo "<tr></tr><tr class='w3-hover-lime' w3-border-bottom><td colspan=8 style=text-align:center;> Greater than or Equal to 50% of Students scored Greater than 65% - US Curriculum</td></tr>";
        }
        elseif ($row["=65%"] >= 75 and 
                (
                    (strpos($row["Subject"], 'Computer') !== false)
                or  (strpos($row["Subject"], 'Math') !== false)
                or  (strpos($row["Subject"], 'Science') !== false)
                or  (strpos($row["Subject"], 'English') !== false)
                or  (strpos($row["Subject"], 'Art') !== false)
                or  (strpos($row["Subject"], 'Physical') !== false)
                or  (strpos($row["Subject"], 'French') !== false)
                or  (strpos($row["Subject"], 'Biology') !== false)
                or  (strpos($row["Subject"], 'Business') !== false)
                or  (strpos($row["Subject"], 'Chemistry') !== false)
                or  (strpos($row["Subject"], 'History') !== false)
                )
            ) 
        {
 /*Year*/    echo "<tr class='w3-hover-khaki w3-border-0'>"
                    . "<td>" . $row["Year"]  . "</td>";

/*Term*/    if ($terms !== "")
                echo "<td>" . $row["Exam"]  . "</td>";
            else
                echo "<td>ALL</td>";

/*Grade*/   if ($grades === "")
                echo "<td>ALL</td>";
            else
/*Section*/     if ($batches !== "")
                    echo "<td>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td>" . $arr[0] . "</td>";
                }
            echo "<td>" . $row["Total"] . "</td>";
            echo "<td>" . $row["=65"] . " Equal to 65</td>";
            echo "<td>" . $row["=65%"] . "%</td><td class='w3-container w3-khaki'>Acceptible</td>";

            if ($subjects !== "")
                echo "<td>" . $row["Subject"] . "</td>";
            else
                echo "<td>ALL</td>";                    

            echo "<tr></tr><tr class='w3-hover-khaki' w3-border-bottom><td colspan=8 style=text-align:center;>Greater than or Equal to 75% of Students scored Equal to 65% - US Curriculum</td></tr>";            
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
/*Year*/    echo "<tr class='w3-hover-green w3-border-0'>"
                    . "<td>" . $row["Year"]  . "</td>";

/*Term*/    if ($terms !== "")
                echo "<td>" . $row["Exam"]  . "</td>";
            else
                echo "<td>ALL</td>";

/*Grade*/   if ($grades === "")
                echo "<td>ALL</td>";
            else
/*Section*/     if ($batches !== "")
                    echo "<td>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td>" . $arr[0] . "</td>";
                }

            echo "<td>" . $row["Total"] . "</td>";
            echo "<td>" . $row[">70"] . " above 70</td>";
            echo "<td>" . $row[">70%"] . "%</td><td class='w3-container w3-green'>Outstanding</td>";


            if ($subjects !== "")
                echo "<td>" . $row["Subject"] . "</td>";
            else
                echo "<td>ALL</td>";                    

            echo "<tr></tr><tr class='w3-hover-green' w3-border-bottom>"
                . "<td colspan=8 style=text-align:center;>Greater than or Equal to 75% of Students scored Greater than 70% - UAE Curriculum</td></tr>";
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
 /*Year*/    echo "<tr class='w3-hover-light-green w3-border-0'>"
                    . "<td>" . $row["Year"]  . "</td>";

/*Term*/    if ($terms !== "")
                echo "<td>" . $row["Exam"]  . "</td>";
            else
                echo "<td>ALL</td>";

/*Grade*/   if ($grades === "")
                echo "<td>ALL</td>";
            else
/*Section*/     if ($batches !== "")
                    echo "<td>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td>" . $arr[0] . "</td>";
                }
            echo "<td>" . $row["Total"] . "</td>";
            echo "<td>" . $row[">70"] . " above 70</td>";
            echo "<td>" . $row[">70%"] . "%</td><td class='w3-container w3-light-green w3-text-white'>Very Good</td>";
            if ($grades === "")
                echo "<td>ALL</td>";
            else
                if ($batches !== "")
                    echo "<td>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td>" . $arr[0] . "</td>";
                }
            echo "<tr></tr><tr class='w3-hover-light-green w3-border-bottom'><td colspan=8 style=text-align:center;>"
                . "Greater than or Equal to 60% of Students scored Greater than 70% - UAE Curriculum</td></tr>";
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
/*Year*/    echo "<tr class='w3-hover-lime w3-border-0'>"
                    . "<td>" . $row["Year"]  . "</td>";

/*Term*/    if ($terms !== "")
                echo "<td>" . $row["Exam"]  . "</td>";
            else
                echo "<td>ALL</td>";

/*Grade*/   if ($grades === "")
                echo "<td>ALL</td>";
            else
/*Section*/     if ($batches !== "")
                    echo "<td>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td>" . $arr[0] . "</td>";
                }
            echo "<td>" . $row["Total"] . "</td>";
            echo "<td>" . $row[">50"] . " above 50</td>";
            echo "<td>" . $row["=50%"] . "%</td><td class='w3-container w3-lime'>Good</td>";
            if ($grades === "")
                echo "<td>ALL</td>";
            else
                if ($batches !== "")
                    echo "<td>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td>" . $arr[0] . "</td>";
                }
            echo "<tr></tr><tr class='w3-hover-lime w3-border-bottom'><td colspan=8 style=text-align:center;>Greater than or Equal to 50% of Students scored Greater than 50% - UAE Curriculum</td></tr>";
        }
        elseif ($row["=60%"] >= 75 and 
                (
                    (strpos($row["Subject"], 'Arabic') !== false)
                or  (strpos($row["Subject"], 'Islamic') !== false)
                or  (strpos($row["Subject"], 'Social') !== false)
                or  (strpos($row["Subject"], 'Moral') !== false)
                )
            )
        {
/*Year*/    echo "<tr class='w3-hover-khaki w3-border-0'>"
                    . "<td>" . $row["Year"]  . "</td>";

/*Term*/    if ($terms !== "")
                echo "<td>" . $row["Exam"]  . "</td>";
            else
                echo "<td>ALL</td>";

/*Grade*/   if ($grades === "")
                echo "<td>ALL</td>";
            else
/*Section*/     if ($batches !== "")
                    echo "<td>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td>" . $arr[0] . "</td>";
                }
            echo "<td>" . $row["Total"] . "</td>";
            echo "<td>" . $row["=60"] . " Equal to 60</td>";
            echo "<td>" . $row["=60%"] . "%</td><td class='w3-container w3-khaki'>Acceptible</td>";
            if ($grades === "")
                echo "<td>ALL</td>";
            else
                if ($batches !== "")
                    echo "<td>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td>" . $arr[0] . "</td>";
                }
            echo "<tr></tr><tr class='w3-hover-khaki w3-border-bottom'><td colspan=8 style=text-align:center;>Greater than or Equal to 75% of Students scored Equal to 60% - UAE Curriculum</td></tr>";
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
 /*Year*/    echo "<tr class='w3-hover-khaki w3-border-0'>"
                    . "<td>" . $row["Year"]  . "</td>";

/*Term*/    if ($terms !== "")
                echo "<td>" . $row["Exam"]  . "</td>";
            else
                echo "<td>ALL</td>";

/*Grade*/   if ($grades === "")
                echo "<td>ALL</td>";
            else
/*Section*/     if ($batches !== "")
                    echo "<td>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td>" . $arr[0] . "</td>";
                }
            echo "<td>" . $row["Total"] . "</td>";
            echo "<td>" . $row["=50"] . " Equal to 50</td>";
            echo "<td>" . $row["=50%"] . "%</td><td class='w3-container w3-khaki'>Acceptible</td>";
            if ($grades === "")
                echo "<td>ALL</td>";
            else
                if ($batches !== "")
                    echo "<td>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td>" . $arr[0] . "</td>";
                }
            echo "<tr></tr><tr class='w3-hover-khaki' w3-border-bottom><td colspan=8 style=text-align:center;>Greater than or Equal to 75% of Students scored Equal to 50% - UAE Curriculum</td></tr>";
        }
        else
        {
 
 /*Year*/    echo "<tr class='w3-hover-orange w3-border-0'>"
                    . "<td>" . $row["Year"]  . "</td>";

/*Term*/    if ($terms !== "")
                echo "<td>" . $row["Exam"]  . "</td>";
            else
                echo "<td>ALL</td>";

/*Grade*/   if ($grades === "")
                echo "<td>ALL</td>";
            else
/*Section*/     if ($batches !== "")
                    echo "<td>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td>" . $arr[0] . "</td>";
                }
            echo "<td>" . $row["Total"] . "</td>";
            echo "<td>" . $row[">65"] . " above 65</td>";
            echo "<td>" . $row[">65%"] . "%</td><td class='w3-container w3-orange'>Rank N/A</td>";
            if ($grades === "")
                echo "<td>ALL</td>";
            else
                if ($batches !== "")
                    echo "<td>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td>" . $arr[0] . "</td>";
                }
            echo "<tr></tr><tr class='w3-hover-orange w3-border-bottom'>"
                 . "<td colspan=8 style=text-align:center;>Less than 75% of students scored Greater than or Equal to 65%</td></tr>";
        }
    }

} else
    echo "Data Not Found, try to import it to DB";

$conn->close();