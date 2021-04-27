<?php
$from = $_POST['from'];
$to = $_POST['to'];
$con = new mysqli("127.0.0.1", "root", "", "network");

$stmt = $con->prepare("SELECT requests FROM users WHERE user_name='".$from."'");
$stmt->execute();
$result = $stmt->get_result();
$requests = $result->fetch_object()->requests;
$check = explode(",", $requests);
if(count($check) == 1) {
    $requests = str_replace($to, "", $requests);
}
else {
    $requests = str_replace(",".$to, "", $requests);
    $requests = str_replace($to.",", "", $requests);
}
$stmt = $con->prepare("UPDATE users SET requests = '".$requests."' WHERE user_name = '".$from."'");
$stmt->execute();

echo "1".$to;
?>