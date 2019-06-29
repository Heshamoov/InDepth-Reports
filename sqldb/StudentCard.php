<?php

include ('../config/dbConfig.php');


$sql = "SELECT Grade, Batch, Name, MOE FROM students ORDER BY Grade, Batch";
$i = 1;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {

        echo "<td>";
        echo 'CLASS &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
        echo '<img src="css/imges/Alsanawbar-Logo1.jpg" style="width: 6rem">';
        echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp SECTION <br>';

        echo '<strong>' . $row['Grade'] . '</strong>&nbsp&nbsp&nbsp&nbsp &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
        echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<strong>' . $row['Batch'] . '</strong><br>';

        echo "<br><strong>" . $row['Name'] . "</strong><br><br>";
        echo 'User Name:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
        echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Password:<br>';
        echo '<strong>' . $row['MOE'] . '</strong>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
        echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<strong>' . $row['MOE'] . '123</strong><br>';
        echo 'URL: alsanawbar.school';
        echo "</td>";
        if ($i % 2 == 0) {
            echo '</tr>';
            echo '<tr>'
            . '<td>'
            . 'Al Sanawbar School<br>Tel: +97137679889 - Fax: +97137679885'
            . '</td>'
            . '<td>'
            . 'Al Sanawbar School<br>Tel: +97137679889 - Fax: +97137679885'
            . '</td>'
            . '</tr>';
        }
        if ($i % 6 == 0)
            echo '<tr style="border: none;">'
            . '<td style = "border: none;">'
            . '<br><br>'
            . '</td><td style = "border: none;">'
            . '<br><br>'
            . '</td>'
            . '</tr>';
        $i++;
    }
} else
    echo "Sorry, make sure that you've imported marks.\nError Message: " . $conn->error . ".\n";


$conn->close();
