<?php
session_start();
include "conn.php";

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);

    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['role'];

    if ($row['role'] == "admin") {
        echo "admin";   // redirect from JS
    } else {
        echo "user";    // redirect from JS
    }
} else {
    echo "invalid";
}
?>
