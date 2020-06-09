<?php

include('../../../config/dbConfig.php');
include('../../../libs/tcpdf/tcpdf.php');
$year = "2019 - 2020";
$grade = $_POST["grade"];
$term = $_POST["term"];


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

//echo $term;
//echo $grade;
$condition = '';

if ($term == 'Term 1' OR $term == 'Term 1 - Class Evaluation')
    $condition = " AND exam_groups.name in ('Term 1', 'Term 1 - Class Evaluation') ";
if ($term == 'Term 2' OR $term == 'Term 2 - Class Evaluation')
    $condition = " AND exam_groups.name in ('Term 2', 'Term 2 - Class Evaluation') ";
if ($term == 'Term 3' OR $term == 'Term 3 - Class Evaluation')
    $condition = " AND exam_groups.name in ('Term 3', 'Term 3 - Class Evaluation') ";

$order = " ORDER BY batches.name, students.first_name, subjects.name, exam_groups.name DESC;";

$sql .= $condition . $order;
//echo $sql;


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

//echo $subjects_query;
$subjects = $conn->query($subjects_query);

$result = $conn->query($sql);

class PDF extends TCPDF
{
// Page header
    function Header()
    {
        // Logo
        $this->SetTitle('Grade Performance');
        $this->Rect(10, 10, 277, 260, 'D'); //For A4
        $this->Image('..\..\..\images\sanawbar.jpg', 15, 18, 20, 20, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('times', 'B', 13);
        $this->SetXY(38, 15);
        $this->Cell(10, 0, 'Al SANAWBAR SCHOOL', 0, 2, 'L');
        $this->SetFont('times', '', 10);
        $this->Cell(38, 10, 'Manaseer School Road, P.0 Box 1781', 0, '', 'L');
        $this->SetFont('times', 'U', 18);
        $this->Cell(180, 10, 'Grade Performance Report', 0, 2, 'R');
//        $this->SetFont('times', 'B', 10);
//        $this->Cell(120, 5, 'TRN 100270764200003', 0, 2, 'R');
        $this->SetXY(38, 25);
        $this->SetFont('times', '', 10);
        $this->Cell(38, 10, 'Tel: 03 767 98889', 0, '2', 'L');
        $this->Cell(38, 0, 'www.alsanawbarschool.com', 0, '', 'L');
        $this->SetLineWidth(0.1);
//        $this->Line(30, 45, 250, 45);
        $this->Ln(10);
    }

// Page footer
//    public function Footer()
//    {
//        $date = date('d-m-Y');
//        $this->SetFont('times', 'I', 10);
//        $this->SetY(-60);
////        $this->Cell(0, 10, '__________________   ', 0, 0, 'R');
//        $this->SetY(-55);
////        $this->Cell(0, 10, 'Accountant Signature   ', 0, 0, 'R');
//        $this->SetY(-15);
//        // times italic 8
//        $this->SetFont('times', 'I', 8);
//        // Page number
////            $this->Cell(0, 10, 'Printed by ' . $_SESSION['name'], 0, 0, 'L');
////            $this->Cell(0, 10, 'Printed on ' . $date, 0, 0, 'R');
//    }
}

$pdf = new PDF();
$pdf->SetTitle('Grade Performance');
$pdf->SetMargins(10, 40, 10);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setFontSubsetting(true);
$fontFamily = 'Times'; // 'Courier', 'Helvetica', 'Arial', 'Times', 'Symbol', 'ZapfDingbats'
$fontStyle = ''; // 'B', 'I', 'U', 'BI', 'BU', 'IU', 'BIU'
$fontSize = 7; // float, in point



$head = '<h3 style="text-align: center">Academic Year: 2019 - 2020  / ' . $grade.' - '. $term . '</h3><table border="1" style="border-collapse: collapse; font-size: 10px; padding-left: 4px;">';

$pdf->SetFont($fontFamily, $fontStyle, $fontSize);
$pdf->AddPage('l');

if ($result->num_rows > 0) {

    $head .= '<tr>
                <th  rowspan="2" colspan="6" style="text-align: right;font-weight: bold;">Courses</th>';
    for ($i = 1; $i <= $subjects_count; $i++)
        $head .= '<td  colspan="2" style="text-align: center">' . $i . '</td>';

    $head .= '</tr><tr>';

    $subjects_array = array();
    while ($subject = $subjects->fetch_assoc()) {
        $head .= '<td colspan="2" style="font-weight: bold; text-align: center">' . $subject['name'] . '</td>';
        $subjects_array[] = $subject['name'];
    }  
    
    $head .= '</tr><tr><th style="font-weight: bold" colspan="4">Student Name</th>
    <th style="font-weight: bold" colspan="2">Grade</th>';


    $terms_array = array();

    if ($term == 'Term 1') {
        $terms_array[0] = "Term 1 - Class Evaluation"; $terms_array[1] = "Term 1"; 
        for ($i = 1; $i <= $subjects_count; $i++)
            $head .= '<td style="text-align: center">C.E.1</td><td style="text-align: center">T.E.1</td>';
    } elseif ($term == 'Term 2') {
        $terms_array[0] = "Term 2 - Class Evaluation"; $terms_array[1] = "Term 2"; 
        for ($i = 1; $i <= $subjects_count; $i++)
            $head .= '<td style="text-align: center">C.E.2</td><td style="text-align: center">T.E.2</td>';
    } elseif ($term == 'Term 3') {
        $terms_array[0] = "Term 3 - Class Evaluation"; $terms_array[1] = "Term 3"; 
        for ($i = 1; $i <= $subjects_count; $i++)
            $head .= '<td style="text-align: center">C.E.3</td><td style="text-align: center">T.E.3</td>';
    }
    $head .= "</tr>";


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
            
            if ($push) { $students[] = $student; $push = false;}

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
        // print_r($student);html .= "<br><br>";
    }


    $prev_name = '';  $first_line = true; $new_line = false; $prev_section = $students[0]->grade;
    $html = $head; $number_of_rows = 0; 
    for($i=0; $i<count($students); $i++) {
        $number_of_rows++;
        
        if ($students[$i]->grade != $prev_section) {
            // $pdf->SetFont($fontFamily, $fontStyle, $fontSize);
            // $pdf->AddPage();
            // $pdf->writeHTML($html, true, false, true, false, '');
            $prev_section = $students[$i]->grade;
            $html .= "</table><br pagebreak='true'/>" . $head;
        }

        $html .= '<tr><td colspan="4">' . $students[$i]->name . '</td><td colspan="2">' . $students[$i]->grade . '</td>';

        $print_mark = "<td>-</td>";
        for($s=0; $s<count($subjects_array); $s++) { // Subjects
            for ($t=0;$t<2;$t++) {
                
                for ($e=0; $e < 2 * count($subjects_array); $e++) { // Exams array in student
                    if ($e < count($students[$i]->exams)) {
                        if (strtolower(str_replace(" ", "", $subjects_array[$s])) == strtolower(str_replace(" ","",$students[$i]->exams[$e]->subject))) {  //Subject HIT
                            if (strtolower(str_replace(" ", "", $terms_array[$t])) == strtolower(str_replace(" ", "", $students[$i]->exams[$e]->term)))
                                $print_mark =  "<td>" . $students[$i]->exams[$e]->mark    . "</td>";
                        }
                    }
                }
                $html .= $print_mark; $print_mark = "<td>-</td>";
            }
            
        } 
        $html .= "</tr>" ;
    }
    $html .= "</table>";
} else {
    echo "No Data Found! Try another search.";    
}

// echo $html;


$pdf->writeHTML($html, true, false, true, false, '');
ob_end_clean();
$pdf->Output('grade-performance.pdf', 'I');
$pdf->Close();