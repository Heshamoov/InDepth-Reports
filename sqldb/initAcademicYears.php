

<?php

include ('../config/dbConfig.php');




$sql = "SELECT  name FROM academic_years  ORDER BY name ";
$result = $conn->query($sql);
while ($row = mysqli_fetch_array($result))
    echo $row["name"] . "\t";

$conn->close();



