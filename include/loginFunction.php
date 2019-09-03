<?php


function login() {
    
require 'config/dbConfig.php';

    $sql = "select users.id user,users.first_name name from users where users.username = '$_POST[user]';";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $user = $row['user'];
        $_SESSION['name'] = $row['name'];
    }

    $sql = "select * from users where username = '$_POST[user]' and (admin = 1 OR employee = 1) ;";
    echo $sql;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $_SESSION['login'] = 1;
        header('Location: statistics.php');
    } else {
        $_SESSION['noaccess'] = 1;
        header('Location: index.php');
    }
}

function checkLoggedIn(){
    
    if (!isset($_SESSION['login'])) {
    $_SESSION['notloggedin'] = 1;
    header('Location: index.php');
} 
}

?>