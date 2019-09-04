<?php

include ('../config/dbConfig.php');

 $terms = $_REQUEST["terms"];
  $grades = $_REQUEST["grades"];
  $years = $_REQUEST["years"]; 
  $batches = $_REQUEST["batches"];
 $subjects = $_REQUEST["subjects"];
 $gender = $_REQUEST["gender"];
 $category = $_REQUEST["category"];

if ($terms == "" and $grades == "" and $years == "" and $batches == "" and $gender == "" and $subjects == "" and $category == "")
    $and = "AND academic_years.name = '2018 - 2019' ".
                  " AND courses.course_name = 'GR 1' ".
                  " AND exam_groups.name = 'Term1-2019' ".
                  " AND batches.name = 'A2019' ";

else $and = ' AND ';

$sql = "SELECT "
        . "T2.academic academic, "
        . "T2.s2_name s2_name, "
        . "T1.exams exams, "
        . "T1.batch batch, "
        . "T1.grade grade, "
        . "T1.COUNT more_than_75, "
        . "T3.COUNT more_than_65, "
        . "T4.COUNT equal_to_65, "
        . "T5.COUNT more_than_70, "
        . "T6.COUNT more_than_50, "
        . "T7.COUNT equal_to_60, "
        . "T8.COUNT equal_to_50, "
        . "T9.COUNT below_50, "        
        . "T2.Total Total, "
        . "ROUND(((T1.COUNT * 100) / T2.Total),2) AVG_M75, "
        . "ROUND(((T3.COUNT * 100) / T2.Total),2) AVG_M65, "
        . "ROUND(((T4.COUNT * 100) / T2.Total),2) AVG_E65, "
        . "ROUND(((T5.COUNT * 100) / T2.Total),2) AVG_M70, "
        . "ROUND(((T6.COUNT * 100) / T2.Total),2) AVG_M50, "
        . "ROUND(((T7.COUNT * 100) / T2.Total),2) AVG_E60, "
        . "ROUND(((T8.COUNT * 100) / T2.Total),2) AVG_E50, "
        . "ROUND(((T9.COUNT * 100) / T2.Total),2) AVG_B50 "
        . "FROM "
        . "( "
        . " SELECT "
        . "    COUNT(*) Total, academic_years.name academic, "
        . "     subjects.name s2_name, "
        . "     courses.course_name grade, "
        . "     exam_groups.name exams "
        . "FROM "
        . "     ( "
        . "         ( "
        . "             ( "
        . "                 ( "
        . "                     ( "
        . "                         ( "
        . "                              ( ("
        . "                             students "
        . "LEFT JOIN student_categories on students.student_category_id  = student_categories.id )  "
        . "                         INNER JOIN batches ON students.batch_id = batches.id "
        . "                         ) "
        . "                         LEFT JOIN academic_years ON academic_years.id = batches.academic_year_id "
        . "                         ) "
        . "                     INNER JOIN courses ON batches.course_id = courses.id "
        . "                     ) "
        . "                 INNER JOIN exam_groups ON students.batch_id = exam_groups.batch_id "
        . "                 ) "
        . "              INNER JOIN exams ON exam_groups.id = exams.exam_group_id "
        . "              ) "
        . "        INNER JOIN exam_scores ON students.id = exam_scores.student_id AND exam_scores.exam_id = exams.id "
        . "         ) "
        . "     INNER JOIN subjects ON exams.subject_id = subjects.id "
        . "     ) ";
if ($terms == "" and $grades == "" and $years == "" and $batches == "" and $gender == "" and $subjects == "" and $category == "") {


    $sql = $sql . " WHERE academic_years.name = '2018 - 2019' ".
                  " AND courses.course_name = 'GR 1' ".
                  " AND exam_groups.name = 'Term1-2019' ".
                  " AND batches.name = 'A2019' "
            . " GROUP BY "
            . "     courses.course_name, academic_years.name,";
} else {
    $sql = $sql . "WHERE  $years $grades  $batches $terms   $gender $category $subjects"
            . "GROUP BY courses.course_name, ";
}


$sql = $sql . "     exam_groups.name, "
        . "     subjects.name "
        . ") AS T2 "
        . "JOIN( "
        . "    SELECT "
        . "       COUNT(*) COUNT, "
        . "       exam_groups.name exams, "
        . "       subjects.name s1_name, "
        . "       courses.course_name grade, "
        . "       batches.name batch "
        . "   FROM "
        . "       ( "
        . "           ( "
        . "               ( "
        . "                   ( "
        . "                       ( "
        . "                           ( (("
        . "                              students "
        . "LEFT JOIN student_categories on students.student_category_id = student_categories.id )  "
        . "                            INNER JOIN batches ON students.batch_id = batches.id "
        . "                            ) "
        . "                         LEFT JOIN academic_years ON academic_years.id = batches.academic_year_id "
        . "                         ) "
        . "                        INNER JOIN courses ON batches.course_id = courses.id "
        . "                        ) "
        . "                    INNER JOIN exam_groups ON students.batch_id = exam_groups.batch_id "
        . "                  ) "
        . "               INNER JOIN exams ON exam_groups.id = exams.exam_group_id  "
        . "               ) "
        . "           INNER JOIN exam_scores ON students.id = exam_scores.student_id AND exam_scores.exam_id = exams.id "
        . "           ) "
        . "       INNER JOIN subjects ON exams.subject_id = subjects.id "
        . "       ) "
        . "   WHERE "
        . "       exam_scores.marks > 75 $and $years $grades  $batches $terms    $gender $category $subjects "
        . "   GROUP BY "
        . "       courses.course_name, "
        . "       exam_groups.name, "
        . "       subjects.name "
        . ") AS T1 "
        . "ON "
        . "    T2.s2_name = T1.s1_name AND T2.grade = T1.grade AND T1.exams = T2.exams "
        . "LEFT JOIN( "
        . "    SELECT "
        . "        COUNT(*) COUNT, "
        . "        exam_groups.name exams, "
        . "        subjects.name s3_name, "
        . "        courses.course_name grade, "
        . "        batches.name batch "
        . "    FROM "
        . "        ( "
        . "            ( "
        . "                ( "
        . "                    ( "
        . "                        ( "
        . "                            ( (("
        . "                                students "
        . "LEFT JOIN student_categories on students.student_category_id = student_categories.id )  "
        . "                            INNER JOIN batches ON students.batch_id = batches.id "
        . "                            ) "
        . "                         LEFT JOIN academic_years ON academic_years.id = batches.academic_year_id "
        . "                         ) "
        . "                        INNER JOIN courses ON batches.course_id = courses.id "
        . "                        ) "
        . "                    INNER JOIN exam_groups ON students.batch_id = exam_groups.batch_id "
        . "                    ) "
        . "                INNER JOIN exams ON exam_groups.id = exams.exam_group_id "
        . "                ) "
        . "            INNER JOIN exam_scores ON students.id = exam_scores.student_id AND exam_scores.exam_id = exams.id "
        . "            ) "
        . "        INNER JOIN subjects ON exams.subject_id = subjects.id "
        . "        ) "
        . "    WHERE "
        . "        exam_scores.marks > 65 $and $years $grades  $batches $terms    $gender $category $subjects "
        . "    GROUP BY "
        . "        courses.course_name, "
        . "        exam_groups.name, "
        . "        subjects.name "
        . ") AS T3 "
        . "ON "
        . "    T2.s2_name = T3.s3_name AND T2.grade = T3.grade AND T2.exams = T3.exams "
        . " LEFT JOIN( "
        . "    SELECT "
        . "        COUNT(*) COUNT, "
        . "        exam_groups.name exams, "
        . "        subjects.name s4_name, "
        . "        courses.course_name grade, "
        . "        batches.name batch "
        . "    FROM "
        . "        ( "
        . "            ( "
        . "                ( "
        . "                    ( "
        . "                        ( "
        . "                            ((( "
        . "                                students "
        . "LEFT JOIN student_categories on students.student_category_id = student_categories.id )  "
        . "                            INNER JOIN batches ON students.batch_id = batches.id "
        . "                            ) "
        . "                         LEFT JOIN academic_years ON academic_years.id = batches.academic_year_id "
        . "                         ) "
        . "                        INNER JOIN courses ON batches.course_id = courses.id "
        . "                       ) "
        . "                   INNER JOIN exam_groups ON students.batch_id = exam_groups.batch_id "
        . "                   ) "
        . "               INNER JOIN exams ON exam_groups.id = exams.exam_group_id "
        . "               ) "
        . "           INNER JOIN exam_scores ON students.id = exam_scores.student_id AND exam_scores.exam_id = exams.id "
        . "           ) "
        . "       INNER JOIN subjects ON exams.subject_id = subjects.id "
        . "       ) "
        . "   WHERE "
        . "       exam_scores.marks = 65 $and $years $grades  $batches $terms    $gender $category $subjects "
        . "   GROUP BY "
        . "       courses.course_name, "
        . "      exam_groups.name, "
        . "      subjects.name "
        . ") AS T4 "
        . "ON "
        . "   T2.s2_name = T4.s4_name AND T2.grade = T4.grade AND T2.exams = T4.exams "
        . "LEFT JOIN( "
        . "    SELECT "
        . "       COUNT(*) COUNT, "
        . "       exam_groups.name exams, "
        . "       subjects.name s5_name, "
        . "       courses.course_name grade, "
        . "       batches.name batch "
        . "   FROM "
        . "       ( "
        . "           ( "
        . "              ( "
        . "                   ( "
        . "                       ( "
        . "                           (( ("
        . "                               students "
        . "LEFT JOIN student_categories on students.student_category_id = student_categories.id )  "
        . "                          INNER JOIN batches ON students.batch_id = batches.id "
        . "                           ) "
        . "                         LEFT JOIN academic_years ON academic_years.id = batches.academic_year_id "
        . "                         ) "
        . "                       INNER JOIN courses ON batches.course_id = courses.id "
        . "                      ) "
        . "                  INNER JOIN exam_groups ON students.batch_id = exam_groups.batch_id "
        . "                  ) "
        . "              INNER JOIN exams ON exam_groups.id = exams.exam_group_id "
        . "             ) "
        . "        INNER JOIN exam_scores ON students.id = exam_scores.student_id AND exam_scores.exam_id = exams.id "
        . "         ) "
        . "     INNER JOIN subjects ON exams.subject_id = subjects.id "
        . "     ) "
        . "  WHERE "
        . "       exam_scores.marks > 70 $and $years $grades  $batches $terms    $gender $category $subjects "
        . "   GROUP BY "
        . "       courses.course_name, "
        . "       exam_groups.name, "
        . "       subjects.name "
        . ") AS T5 "
        . "ON "
        . "   T2.s2_name = T5.s5_name AND T2.grade = T5.grade AND T2.exams = T5.exams "
        . "LEFT JOIN( "
        . "   SELECT "
        . "       COUNT(*) COUNT, "
        . "       exam_groups.name exams, "
        . "      subjects.name s6_name, "
        . "      courses.course_name grade, "
        . "       batches.name batch "
        . "   FROM "
        . "       ( "
        . "           ( "
        . "               ( "
        . "                   ( "
        . "                       ( "
        . "                           ( (("
        . "                              students "
        . "LEFT JOIN student_categories on students.student_category_id = student_categories.id )  "
        . "                          INNER JOIN batches ON students.batch_id = batches.id "
        . "                          ) "
        . "                         LEFT JOIN academic_years ON academic_years.id = batches.academic_year_id "
        . "                         ) "
        . "                      INNER JOIN courses ON batches.course_id = courses.id "
        . "                      ) "
        . "                  INNER JOIN exam_groups ON students.batch_id = exam_groups.batch_id "
        . "                   ) "
        . "               INNER JOIN exams ON exam_groups.id = exams.exam_group_id "
        . "               ) "
        . "           INNER JOIN exam_scores ON students.id = exam_scores.student_id AND exam_scores.exam_id = exams.id "
        . "           ) "
        . "       INNER JOIN subjects ON exams.subject_id = subjects.id "
        . "       ) "
        . "   WHERE "
        . "       exam_scores.marks > 50 $and $years $grades  $batches $terms    $gender $category $subjects "
        . "   GROUP BY "
        . "       courses.course_name, "
        . "       exam_groups.name, "
        . "       subjects.name "
        . ") AS T6 "
        . "ON "
        . "    T2.s2_name = T6.s6_name AND T2.grade = T6.grade AND T2.exams = T6.exams "
        . "LEFT JOIN( "
        . "    SELECT "
        . "        COUNT(*) COUNT, "
        . "       exam_groups.name exams, "
        . "        subjects.name s7_name, "
        . "        courses.course_name grade, "
        . "        batches.name batch "
        . "    FROM "
        . "        ( "
        . "            ( "
        . "                ( "
        . "                    ( "
        . "                        ( "
        . "                           ( (("
        . "                               students "
        . "LEFT JOIN student_categories on students.student_category_id = student_categories.id )  "
        . "                           INNER JOIN batches ON students.batch_id = batches.id "
        . "                          ) "
        . "                         LEFT JOIN academic_years ON academic_years.id = batches.academic_year_id "
        . "                         ) "
        . "                      INNER JOIN courses ON batches.course_id  = courses.id "
        . "                      ) "
        . "                  INNER JOIN exam_groups ON students.batch_id = exam_groups.batch_id "
        . "                  ) "
        . "              INNER JOIN exams ON exam_groups.id = exams.exam_group_id "
        . "              ) "
        . "          INNER JOIN exam_scores ON students.id = exam_scores.student_id AND exam_scores.exam_id = exams.id "
        . "          ) "
        . "      INNER JOIN subjects ON exams.subject_id = subjects.id "
        . "      ) "
        . "  WHERE "
        . "       exam_scores.marks = 60 $and $years $grades  $batches $terms    $gender $category $subjects "
        . "   GROUP BY "
        . "      courses.course_name, "
        . "      exam_groups.name, "
        . "      subjects.name "
        . ") AS T7 "
        . "ON "
        . "   T2.s2_name = T7.s7_name AND T2.grade = T7.grade AND T2.exams = T7.exams "
        . "LEFT JOIN( "
        . "   SELECT "
        . "       COUNT(*) COUNT, "
        . "      exam_groups.name exams, "
        . "      subjects.name s8_name, "
        . "      courses.course_name grade, "
        . "     batches.name batch "
        . "  FROM "
        . "     ( "
        . "         ( "
        . "            ( "
        . "                 ( "
        . "                    ( "
        . "                        ((( "
        . "                            students "
        . "LEFT JOIN student_categories on students.student_category_id = student_categories.id )  "
        . "                         INNER JOIN batches ON students.batch_id = batches.id "
        . "                         ) "
        . "                         LEFT JOIN academic_years ON academic_years.id = batches.academic_year_id "
        . "                         ) "
        . "                    INNER JOIN courses ON batches.course_id = courses.id "
        . "                    ) "
        . "                INNER JOIN exam_groups ON students.batch_id = exam_groups.batch_id "
        . "                ) "
        . "            INNER JOIN exams ON exam_groups.id = exams.exam_group_id "
        . "           ) "
        . "       INNER JOIN exam_scores ON students.id = exam_scores.student_id AND exam_scores.exam_id = exams.id "
        . "        ) "
        . "   INNER JOIN subjects ON exams.subject_id = subjects.id "
        . "     ) "
        . "  WHERE "
        . "     exam_scores.marks = 50 $and $years $grades  $batches $terms    $gender $category $subjects "
        . " GROUP BY "
        . "     courses.course_name, "
        . "      exam_groups.name, "
        . "      subjects.name "
        . ") AS T8 "
        . "ON "
        . " T2.s2_name = T8.s8_name AND T2.grade = T8.grade AND T2.exams = T8.exams"
        . " "
        . "LEFT JOIN( "
        . "   SELECT "
        . "       COUNT(*) COUNT, "
        . "      exam_groups.name exams, "
        . "      subjects.name s9_name, "
        . "      courses.course_name grade, "
        . "     batches.name batch "
        . "  FROM "
        . "     ( "
        . "         ( "
        . "            ( "
        . "                 ( "
        . "                    ( "
        . "                        ((( "
        . "                            students "
        . "LEFT JOIN student_categories on students.student_category_id = student_categories.id )  "
        . "                         INNER JOIN batches ON students.batch_id = batches.id "
        . "                         ) "
        . "                         LEFT JOIN academic_years ON academic_years.id = batches.academic_year_id "
        . "                         ) "
        . "                    INNER JOIN courses ON batches.course_id = courses.id "
        . "                    ) "
        . "                INNER JOIN exam_groups ON students.batch_id = exam_groups.batch_id "
        . "                ) "
        . "            INNER JOIN exams ON exam_groups.id = exams.exam_group_id "
        . "           ) "
        . "       INNER JOIN exam_scores ON students.id = exam_scores.student_id AND exam_scores.exam_id = exams.id "
        . "        ) "
        . "   INNER JOIN subjects ON exams.subject_id = subjects.id "
        . "     ) "
        . "  WHERE "
        . "     exam_scores.marks < 50 $and $years $grades  $batches $terms    $gender $category $subjects "
        . " GROUP BY "
        . "     courses.course_name, "
        . "      exam_groups.name, "
        . "      subjects.name "
        . ") AS T9 "
        . "ON "
        . " T2.s2_name = T9.s9_name AND T2.grade = T9.grade AND T2.exams = T9.exams; ";




//			echo $sql;
$result = $conn->query($sql);
$rownumber = 1;
if ($result->num_rows > 0) {
    echo "<thead><tr id =out class= w3-custom ><th>Year</th>"
    . "<th>Grade</th>"
    . "<th>Term</th>"
    . "<th>Subject</th>"
    . "<th>Attended</th>"
    . "<th>Qualified</th>"
    . "<th>Average</th>"
    . "<th>Status<th></tr></thead><tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["academic"] . "</td>"
        . "<td>" . $row["grade"] . "</td>"
        . "<td>" . $row["exams"] . "</td>"
        . "<td>" . $row["s2_name"] . "</td>"
        . "<td>" . $row["Total"] . "</td>";
        if ($row["AVG_M75"] >= 75) {
            echo "<td>" . $row["more_than_75"] . "</td>";
            echo "<td>" . $row["AVG_M75"] . "</td>";
            echo "<td style= 'background:green; color:white' > Outstanding</td>";
        } else if ($row["AVG_M75"] >= 60) {
            echo "<td>" . $row["more_than_75"] . "</td>";
            echo "<td>" . $row["AVG_M75"] . "</td>";
            echo "<td style= 'background:#1EB513; color:white' > Very Good</td>";
        } else if ($row["AVG_M65"] >= 50) {
            echo "<td>" . $row["more_than_65"] . "</td>";
            echo "<td>" . $row["AVG_M65"] . "</td>";
            echo "<td style= 'background:orange; color:white'>Good</td>";
        } else if ($row["AVG_E65"] >= 75) {
            echo "<td>" . $row["equal_to_65"] . "</td>";
            echo "<td>" . $row["AVG_E65"] . "</td>";
            echo "<td>Acceptible</td>";
        } else if ($row["AVG_M75"] >= 75) {
            echo "<td>" . $row["more_than_75"] . "</td>";
            echo "<td>" . $row["AVG_M75"] . "</td>";
            echo "<td style= 'background:green; color:white' >Outstanding</td>";
        } else if ($row["AVG_M70"] >= 60) {
            echo "<td>" . $row["more_than_70"] . "</td>";
            echo "<td>" . $row["AVG_M70"] . "</td>";
            echo "<td>Very Good</td>";
        } else if ($row["AVG_M50"] >= 50) {
            echo "<td>" . $row["more_than_50"] . "</td>";
            echo "<td>" . $row["AVG_M50"] . "</td>";
            echo "<td style= 'background:orange; color:white' >Good</td>";
        } else if ($row["AVG_E60"] >= 75) {
            echo "<td>" . $row["equal_to_60"] . "</td>";
            echo "<td>" . $row["AVG_E60"] . "</td>";
            echo "<td style= 'background:blue; color:white'>Acceptible</td>";
        } else if ($row["AVG_E50"] >= 75) {
            echo "<td>" . $row["equal_to_50"] . "</td>";
            echo "<td>" . $row["AVG_E50"] . "</td>";
            echo "<td>Acceptible</td>";
        } else {
            echo "<td>" . $row["below_50"] . "</td>";
            echo "<td>" . $row["AVG_B50"] . "</td>";
            echo "<td style= 'background:red; color:white'>Failed</td>";
        }


        echo "</tr>";
        echo "</tbody>";
    }
} else {
    echo "Data Not Found, try to import it to DB";
}

$conn->close();




