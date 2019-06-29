<?php

include ('../config/dbConfig.php');

$sql = "CREATE TABLE marks (
                        Serial SMALLINT(6) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        MOE INT(6) UNSIGNED NOT NULL, 
                        Exam_Group VARCHAR(30) NOT NULL,
                        Grade VARCHAR(10) NOT NULL,
                        Batch VARCHAR(10) NOT NULL,
                        Subject VARCHAR(30) NOT NULL,
                        Mark DECIMAL (5,2) UNSIGNED NOT NULL)";

if ($conn->query($sql) === TRUE)
    echo "Marks table created successfully\n";
else
    echo "Error creating table: " . $conn->error . "\n";

$conn->close();
