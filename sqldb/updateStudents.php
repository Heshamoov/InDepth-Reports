<?php

include ('../config/dbConfig.php');

$sql = "UPDATE `students` SET " . $_REQUEST["sql"];
echo $sql;

if ($conn->query($sql) === TRUE)
    echo "\nStudnets updated successfully\n";
else
    echo "\nError updating: " . $conn->error . "\n";

$conn->close();
