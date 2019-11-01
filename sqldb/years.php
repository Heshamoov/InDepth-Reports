<?php
include ('../config/dbConfig.php');

$sql = "SELECT  distinct(acd_code) FROM new_marks ORDER BY acd_code";

$result = $conn->query($sql);
while ($row = mysqli_fetch_array($result))
    echo $row["acd_code"] . "\t";

$conn->close();