<?php

include ('../config/dbConfig.php');

$sql = "SELECT DISTINCT subject_name FROM new_marks ORDER BY subject_name";

$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['subject_name'] . "\t";

$conn->close();
