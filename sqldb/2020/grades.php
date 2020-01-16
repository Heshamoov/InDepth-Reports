<?php

include ('../config/dbConfig.php');

$sql = "SELECT DISTINCT name FROM courses WHERE name like 'GR%' ORDER BY name";

$result = $conn->query($sql);

while ($row = mysqli_fetch_array($result))
    echo $row['grade'] . "\t";

$conn->close();
