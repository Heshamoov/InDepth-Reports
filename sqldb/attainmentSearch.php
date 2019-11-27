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

        ."COUNT(IF (exam_scores.marks >= 65 AND exam_scores.marks IS NOT NULL, 1, NULL)) as '>=65', "
        ."ROUND( COUNT(IF (exam_scores.marks >= 65 AND exam_scores.marks IS NOT NULL, 1, NULL)) / COUNT(IF (exam_scores.marks IS NOT NULL, 1, NULL)) * 100, 0)  as '>=65%', "

        ."COUNT(IF (exam_scores.marks >= 75 AND exam_scores.marks IS NOT NULL, 1, NULL)) as '>=75', "
        ."ROUND( COUNT(IF (exam_scores.marks >= 75 AND exam_scores.marks IS NOT NULL, 1, NULL)) / COUNT(IF (exam_scores.marks IS NOT NULL, 1, NULL)) * 100, 0)  as '>=75%', "
        
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

    $sql = $sql ."ORDER BY `>=75%` DESC, `>=65%` DESC";

// echo $sql;
$result = $conn->query($sql);
$rownumber = 1;
if ($result->num_rows > 0) {
    echo  "<thead'>"
            . "<tr class='w3-custom'>"
                . "<th id='tCol1'>Year</th>
                   <th id='tCol2'>Exam</th>"
                . "<th id='tCol3' >Grade</th>"
                . "<th id='tCol4'>Total</th> <th id='tCol5'>Count</th><th id='tCol6'>Ratio</th>"
                . "<th id='tCol7'>Attainment</th><th id='tCol8'>Subject</th>"
            . "</tr>"
         ."</thead>";

    while ($row = $result->fetch_assoc()) {
        // Outstanding
        if ($row[">=75%"] >= 75)
        {
/*Year*/    echo "<tr  class='w3-hover-green w3-border-0'>"
                    . "<td id='tCol1'>" . $row["Year"]  . "</td>";

/*Term*/    if ($terms !== "")
                echo "<td id='tCol2'>" . $row["Exam"]  . "</td>";
            else
                echo "<td id='tCol2'>ALL</td>";

/*Grade*/   if ($grades === "")
                echo "<td id='tCol3'>ALL</td>";
            else
/*Section*/     if ($batches !== "")
                    echo "<td id='tCol3'>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td id='tCol3'>" . $arr[0] . "</td>";
                }
            echo "<td id='tCol4'>" . $row["Total"] . "</td>";
            echo "<td id='tCol5'>" . $row[">=75"] . " equal or above mark 75</td>";
            echo "<td id='tCol6'>" . $row[">=75%"] . "%</td>
                  <td id='tCol7' class='w3-container w3-green'>Outstanding</td>";
            
            if ($subjects !== "")
                echo "<td id='tCol8'>" . $row["Subject"] . "</td>";
            else
                echo "<td id='tCol8'>ALL</td>";

            echo "<tr id='tCol9'></tr><tr id='tCol9' class='w3-hover-green' w3-border-bottom><td colspan=8 style=text-align:center;>"
                . $row[">=75%"] . "% (equal or above 75%) of students scored 75 or more</td></tr>";
        }

        // Very Good
        elseif ($row[">=75%"] >= 61 and $row[">=75%"] < 75)
        {

/*Year*/    echo "<tr  class='w3-hover-light-green w3-border-0'>"
                    . "<td id='tCol1'>" . $row["Year"]  . "</td>";

/*Term*/    if ($terms !== "")
                echo "<td id='tCol2'>" . $row["Exam"]  . "</td>";
            else
                echo "<td id='tCol2'>ALL</td>";

/*Grade*/   if ($grades === "")
                echo "<td id='tCol3'>ALL</td>";
            else
/*Section*/     if ($batches !== "")
                    echo "<td id='tCol3'>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td id='tCol3'>" . $arr[0] . "</td>";
                }

/*Total*/   echo "<td id='tCol4'>" . $row["Total"] . "</td>"
                . "<td id='tCol5'> " . $row[">=75"]   . " equal or above mark 75</td>"
                . "<td id='tCol6'>" . $row[">=75%"]  . "%</td>"
                . "<td id='tCol7' class='w3-container w3-light-green w3-text-white'>Very Good</td>";
            
/*Subject*/ if ($subjects !== "")
                echo "<td id='tCol8'>" . $row["Subject"] . "</td>";
            else
                echo "<td id='tCol8'>ALL</td>";

                echo "</tr><tr id='tCol9'></tr>"
                    ."<tr id='tCol9' class='w3-hover-light-green w3-border-bottom'>"
                        . "<td colspan=8 style=text-align:center;>"
                . $row[">=75%"] . "% of students within range [61% to 75%] scored 75 or more</td></tr>";
        }

        // Good
        elseif ($row[">=75%"] >= 50  and $row[">=75%"] < 61)
        {
/*Year*/    echo "<tr  class='w3-hover-lime w3-border-0'>"
                    . "<td id='tCol1'>" . $row["Year"]  . "</td>";

/*Term*/    if ($terms !== "")
                echo "<td id='tCol2'>" . $row["Exam"]  . "</td>";
            else
                echo "<td id='tCol2'>ALL</td>";

/*Grade*/   if ($grades === "")
                echo "<td id='tCol3'>ALL</td>";
            else
/*Section*/     if ($batches !== "")
                    echo "<td id='tCol3'>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td id='tCol3'>" . $arr[0] . "</td>";
                }

            echo "<td id='tCol4'>" . $row["Total"] . "</td>";
            echo "<td id='tCol5'>" . $row[">=75"] . " equal or above mark 75</td>";
            echo "<td id='tCol6'>" . $row[">=75%"] . "%</td>
                  <td id='tCol7' class='w3-container w3-blue'>Good</td>";

            if ($subjects !== "")
                echo "<td id='tCol8'> " . $row["Subject"] . "</td>";
            else
                echo "<td id='tCol8'>ALL</td>";

            echo "<tr id='tCol9' ></tr><tr id='tCol9' class='w3-hover-lime w3-border-bottom'>"
                ."<td  colspan=8 style=text-align:center;>"
                . $row[">=75%"] . "% of students within range [50% to 61%] scored 75 or more</td></tr>";
            
        }

        // Acceptable
        elseif ($row[">=65%"] >= 75) 
        {
            echo "<tr class='w3-hover-light-orange w3-border-0'><td id='tCol1'>"
            . $row['Year'] . "</td>";

/*Term*/    if ($terms !== "")
                echo "<td id='tCol2'>" . $row["Exam"]  . "</td>";
            else
                echo "<td id='tCol2'>ALL</td>";

/*Grade*/   if ($grades === "")
                echo "<td id='tCol3'>ALL</td>";
            else
/*Section*/     if ($batches !== "")
                    echo "<td id='tCol3'>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td id='tCol3'>" . $arr[0] . "</td>";
                }

echo "<td id='tCol4'>" . $row["Total"] . "</td>";
echo "<td id='tCol5'>" . $row[">=65"] . " equal or above mark 65</td>";
echo "<td id='tCol6'>" . $row[">=65%"] . "%</td><td id='tCol7' class='w3-container w3-orange'>Acceptable</td>";

            if ($subjects !== "")
                echo "<td id='tCol8'> " . $row["Subject"] . "</td>";
            else
                echo "<td id='tCol8'>ALL</td>";

            echo "<tr id='tCol9' ></tr><tr id='tCol9' class='w3-hover-orange w3-border-bottom'>"
                ."<td  colspan=8 style=text-align:center;>"
                . $row[">=65%"] . "% (equal or above 65%) of students scored 75 or more</td></tr>";
        }

        // Not Applicable
        else 
        {
            echo "<tr class='w3-hover-light-red w3-border-0'><td id='tCol1'>"
            . $row['Year'] . "</td>";

/*Term*/    if ($terms !== "")
                echo "<td id='tCol2'>" . $row["Exam"]  . "</td>";
            else
                echo "<td id='tCol2'>ALL</td>";

/*Grade*/   if ($grades === "")
                echo "<td id='tCol3'>ALL</td>";
            else
/*Section*/     if ($batches !== "")
                    echo "<td id='tCol3'>" . $row["Grade"]  . "</td>";
                else {
                    $arr = explode('-',trim($row["Grade"]));
                    echo "<td id='tCol3'>" . $arr[0] . "</td>";
                }

echo "<td id='tCol4'>" . $row["Total"] . "</td>";
echo "<td id='tCol5'>" . $row[">=65"] . " equal or above mark 65</td>";
echo "<td id='tCol6'>" . $row[">=65%"] . "%</td><td id='tCol7' class='w3-container w3-orange'>Not Applicable</td>";

            if ($subjects !== "")
                echo "<td id='tCol8'> " . $row["Subject"] . "</td>";
            else
                echo "<td id='tCol8'>ALL</td>";

            echo "<tr id='tCol9' ></tr><tr id='tCol9' class='w3-hover-orange w3-border-bottom'>"
                ."<td  colspan=8 style=text-align:center;>"
                . "Only " . $row[">=65%"] . "% of students scored 65 or more</td></tr>";
        }


    }
}

$conn->close();