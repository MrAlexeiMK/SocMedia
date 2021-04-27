<?php
$username = $_POST['username'];
$con = new mysqli("127.0.0.1", "root", "", "network");
$stmt = $con->prepare("UPDATE users SET status = 'offline' WHERE user_name='".$username."'");
$stmt->execute();
?>