<?php

include ('../config/dbConfig.php');

$sql = "SELECT DISTINCT grade FROM new_marks WHERE grade like 'GR%' ORDER BY grade";

$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['grade'] . "\t";

$conn->close();
