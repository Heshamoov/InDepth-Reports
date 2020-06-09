
<?php

include('../../../config/dbConfig.php');


$year = "2019 - 2020";
$grade = $_REQUEST["Grade"];
$term = $_REQUEST["Term"];

$sql = "SELECT students.id, students.last_name name, concat(courses.course_name, ' - ', batches.name) as grade,
       exam_groups.name term, round(exam_scores.marks) mark, subjects.name subject
FROM ((((((((
    academic_years
    INNER JOIN batches ON academic_years.id = batches.academic_year_id)
    INNER JOIN courses ON batches.course_id = courses.id)
    INNER JOIN exam_groups ON batches.id = exam_groups.batch_id)
    INNER JOIN exams ON exam_groups.id = exams.exam_group_id)
    INNER JOIN subjects ON exams.subject_id = subjects.id)
    INNER JOIN exam_scores ON exams.id = exam_scores.exam_id)
    INNER JOIN students ON exam_scores.student_id = students.id)
    LEFT JOIN student_categories ON students.student_category_id = student_categories.id)

WHERE academic_years.name = '2019 - 2020'
  AND courses.course_name = '$grade' ";

if ($term == 'Term 1' OR $term == 'Term 1 - Class Evaluation') $condition = " AND exam_groups.name in ('Term 1', 'Term 1 - Class Evaluation') ";
if ($term == 'Term 2' OR $term == 'Term 2 - Class Evaluation') $condition = " AND exam_groups.name in ('Term 2', 'Term 2 - Class Evaluation') ";
if ($term == 'Term 3' OR $term == 'Term 3 - Class Evaluation') $condition = " AND exam_groups.name in ('Term 3', 'Term 3 - Class Evaluation') ";
$order = " ORDER BY batches.name, students.first_name, subject, term DESC;";

$sql .= $condition . $order;
// echo $sql;


$subjects_query = "SELECT distinct (subjects.name)
FROM ((((((((
    academic_years
    INNER JOIN batches ON academic_years.id = batches.academic_year_id)
    INNER JOIN courses ON batches.course_id = courses.id)
    INNER JOIN exam_groups ON batches.id = exam_groups.batch_id)
    INNER JOIN exams ON exam_groups.id = exams.exam_group_id)
    INNER JOIN subjects ON exams.subject_id = subjects.id)
    INNER JOIN exam_scores ON exams.id = exam_scores.exam_id)
    INNER JOIN students ON exam_scores.student_id = students.id)
    LEFT JOIN student_categories ON students.student_category_id = student_categories.id)

WHERE academic_years.name = '2019 - 2020'
  AND courses.course_name = '$grade'" . $condition . "ORDER BY batches.name, students.first_name, subjects.name, exam_groups.name";

// echo $subjects_query;

$count_subjects = "SELECT DISTINCT (subjects.name)
FROM ((((((((
    academic_years
    INNER JOIN batches ON academic_years.id = batches.academic_year_id)
    INNER JOIN courses ON batches.course_id = courses.id)
    INNER JOIN exam_groups ON batches.id = exam_groups.batch_id)
    INNER JOIN exams ON exam_groups.id = exams.exam_group_id)
    INNER JOIN subjects ON exams.subject_id = subjects.id)
    INNER JOIN exam_scores ON exams.id = exam_scores.exam_id)
    INNER JOIN students ON exam_scores.student_id = students.id)
    LEFT JOIN student_categories ON students.student_category_id = student_categories.id)

WHERE academic_years.name = '2019 - 2020'
  AND courses.course_name = '$grade'" . $condition;


$number_of_subjects = $conn->query($count_subjects);
$subjects_count = mysqli_num_rows($number_of_subjects);

$subjects = $conn->query($subjects_query);
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    echo "<thead>
            <tr><th>Grade: " . $grade . "</th><th colspan='". strval($subjects_count  * 2 + 1) ."'>Academic Year: 2019 - 2020</th></tr>" .
            "<tr><th rowspan='2'></th><th rowspan='2'>Courses</th>";

    for ($i = 1; $i <= $subjects_count; $i++) {
        echo "<td colspan='2'>" . $i . "</td>";
    }
    echo "</tr>";

    $subjects_array = array();
    while ($subject = $subjects->fetch_assoc()) {
        echo "<td colspan='2'>" . $subject['name'] . "</td>";
        $subjects_array[] = $subject['name'];
    }  echo "<tr><th>Student Name</th><th>Grade</th>";

    // echo "Subjects Array<br>";
    // for ($i=0; $i < count($subjects_array); $i++)
    //     echo $subjects_array[$i] . "<br>";
    

    $terms_array = array();

    if ($term == 'Term 1') {
        $terms_array[0] = "Term 1 - Class Evaluation"; $terms_array[1] = "Term 1"; 
        for ($i = 1; $i <= $subjects_count; $i++)
            echo '<td style="text-align: center">C.E.1</td><td style="text-align: center">T.E.1</td>';
    } elseif ($term == 'Term 2') {
        $terms_array[0] = "Term 2 - Class Evaluation"; $terms_array[1] = "Term 2"; 
        for ($i = 1; $i <= $subjects_count; $i++)
            echo '<td style="text-align: center">C.E.2</td><td style="text-align: center">T.E.2</td>';
    } elseif ($term == 'Term 3') {
        $terms_array[0] = "Term 3 - Class Evaluation"; $terms_array[1] = "Term 3"; 
        for ($i = 1; $i <= $subjects_count; $i++)
            echo '<td style="text-align: center">C.E.3</td><td style="text-align: center">T.E.3</td>';
    }
    echo "</tr></thead><tbody>";


    class Exam {
        
        public $term;
        public $subject;
        public $mark;

        function __construct($term,$subject,$mark) {
            $this->term = $term;
            $this->subject = $subject;
            $this->mark = $mark;
        }
    }

    class Student {
        public $name;
        public $grade;
        public $exams = array();

    
        function __construct($name,$grade,$exam, $i) {
            $this->name = $name;
            $this->grade = $grade;
            $this->exams[$i] = $exam;
        }
    }

    $prev_id = 0; $i=0; $push = false; $students = array();

    while ($row = $result->fetch_assoc()) {
        
        if ($prev_id != $row['id']) { //New Student
            
            if ($push) { 
                $students[] = $student; $push = false;
                // echo $row['id'] . " - " . $row['name'] . " PUSHED => " . $student ->name . "*<br>";
            }

            $prev_id = $row['id']; $i=0;
            
            $exam = new Exam($row['term'], $row['subject'],$row['mark']);
            $student = new Student($row['name'],$row['grade'], $exam, $i);
            $i++;
        }
        else {
            $exam = new Exam($row['term'], $row['subject'],$row['mark']);
            $student->exams[$i] = $exam;
            $i++; $push= true;
        }   
        // print_r($student);echo "<br><br>";
    }
    $students[] = $student; /// Push Last Object


    $prev_name = '';  $first_line = true; $new_line = false;

    
    // echo "Befor Last Object" . $students[count($students) - 2]->name . " - " . $students[count($students) - 2]->grade . "<br>";
    // echo "Last Object" . $students[count($students) - 1]->name . " - " . $students[count($students) - 1]->grade . "<br>";

    for($i=0; $i<count($students); $i++) {
        echo "<td>" . $students[$i]->name . "</td><td>" . $students[$i]->grade . "</td>";
        $print_mark = "<td>-</td>";
        for($s=0; $s<count($subjects_array); $s++) { // Subjects
            for ($t=0;$t<2;$t++) {     
                for ($e=0; $e < 2 * count($subjects_array); $e++) { // Exams array in student
                    if ($e < count($students[$i]->exams)) {
                        // if ($subjects_array[$s] == 'Islamic Studies')
                        // echo "<br>*" . str_replace(" ", "", $subjects_array[$s]) . "* => *" . str_replace(" ","",$students[$i]->exams[$e]->subject) . "*<br><br>";
                        if (strtolower(str_replace(" ", "", $subjects_array[$s])) == strtolower(str_replace(" ","",$students[$i]->exams[$e]->subject))) {  //Subject HIT
                            // if ($subjects_array[$s] == 'Islamic Studies') echo "HIT";
                            // echo str_replace(" ", "", $terms_array[$t]) . "*=>*" . str_replace(" ", "", $students[$i]->exams[$e]->term) . "*<br>";
                            if (strtolower(str_replace(" ", "", $terms_array[$t])) == strtolower(str_replace(" ", "", $students[$i]->exams[$e]->term)))
                                $print_mark =  "<td>" . $students[$i]->exams[$e]->subject . " - "
                                        . $students[$i]->exams[$e]->term    . " - " 
                                        . $students[$i]->exams[$e]->mark    . "</td>";
                        }
                    }
                }
                echo $print_mark; $print_mark = "<td>-</td>";
            }
            
        } 
        echo "</tr><tr>" ;
    }
} else {
    echo "No Data Found! Try another search.";
}

$conn->close();