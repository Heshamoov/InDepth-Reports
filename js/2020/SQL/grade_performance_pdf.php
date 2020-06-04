<?php

include('../../../config/dbConfig.php');
include('../../../libs/tcpdf/tcpdf.php');
$year = "2019 - 2020";
$grade = $_POST["grade"];
$term = $_POST["term"];


$sql = "SELECT students.id, students.last_name name, concat(courses.course_name, ' - ', batches.name) as grade,
       exam_groups.name term, round(exam_scores.marks) marks, subjects.name subject
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

echo $subjects_query;

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



$html2 = '<h3 style="text-align: center">Academic Year: 2019 - 2020  / ' . $grade.' - '. $term . '</h3><table border="1" style="border-collapse: collapse; font-size: 10px; padding-left: 4px;">';

$pdf->SetFont($fontFamily, $fontStyle, $fontSize);
$pdf->AddPage('l');

if ($result->num_rows > 0) {
    $html2 .= '<tr>
<th  rowspan="2" colspan="6" style="text-align: right;font-weight: bold;">Courses</th>';
    for ($i = 1; $i <= $subjects_count; $i++)
        $html2 .= '<td  colspan="2" style="text-align: center">' . $i . '</td>';

    $html2 .= '</tr><tr>';

    while ($subject = $subjects->fetch_assoc())
        $html2 .= '<td colspan="2" style="font-weight: bold; text-align: center">' . $subject['name'] . '</td>';

    $html2 .= '</tr><tr><th style="font-weight: bold" colspan="4">Student Name</th>
<th style="font-weight: bold" colspan="2">Grade</th>';

    if (strcmp($term, 'Term 1') == 0 )  {
        for ($i = 1; $i <= $subjects_count; $i++)
            $html2 .= '<td style="text-align: center">C.E.1</td><td style="text-align: center">T.E.1</td>';
    }

    else if (strcmp($term, 'Term 2') == 0  ) {
        for ($i = 1; $i <= $subjects_count; $i++)
            $html2 .= '<td style="text-align: center">C.E.2</td><td style="text-align: center">T.E.2</td>';
    }

    else if (strcmp($term, 'Term 3') == 0   ) {
        for ($i = 1; $i <= $subjects_count; $i++)
            $html2 .= '<td style="text-align: center">C.E.3</td><td style="text-align: center">T.E.3</td>';
    }


    $html2 .= '</tr>';

    $prev_id = 0;
    $first_line = true;
    $new_line = false;
    $html2 .= '<tr>';
    while ($row = $result->fetch_assoc()) {
        if ($row['id'] != $prev_id) { // New Student
            if ($first_line) {
                $first_line = false;
                $prev_id = $row['id'];
            } else {
                if ($row['id'] != $prev_id)
                    $html2 .= '</tr><tr>';
                $prev_id = $row['id'];
            }
            $html2 .= '<td colspan="4">' . $row['name'] . '</td><td colspan="2">' . $row['grade'] . '</td><td style="font-size: 10px;text-align: center">'. $row['marks'] . '</td>';
        } else
            $html2 .= '<td style="font-size: 10px; text-align: center">' . $row['marks'] . '</td>';
    }
    $html2 .= '</tr></table>';
}
//echo $html2;


$pdf->writeHTML($html2, true, false, true, false, '');
ob_end_clean();
$pdf->Output('grade-performance.pdf', 'I');
$pdf->Close();