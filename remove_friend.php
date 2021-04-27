<?php
$from = $_POST['from'];
$to = $_POST['to'];
$con = new mysqli("127.0.0.1", "root", "", "network");

//friends
$stmt = $con->prepare("SELECT friends FROM users WHERE user_name='".$to."'");
$stmt->execute();
$result = $stmt->get_result();
$friends = $result->fetch_object()->friends;
$list = explode(",", $friends);

if(count($list) == 1) $friends = str_replace($from, "", $friends);
else {
    $friends = str_replace($from.",", "", $friends);
    $friends = str_replace(",".$from, "", $friends);
}
$stmt = $con->prepare("UPDATE users SET friends = '".$friends."' WHERE user_name = '".$to."'");
$stmt->execute();

$stmt = $con->prepare("SELECT friends FROM users WHERE user_name='".$from."'");
$stmt->execute();
$result = $stmt->get_result();
$friends = $result->fetch_object()->friends;
$list = explode(",", $friends);

if(count($list) == 1) $friends = str_replace($to, "", $friends);
else {
    $friends = str_replace($to.",", "", $friends);
    $friends = str_replace(",".$to, "", $friends);
}
$stmt = $con->prepare("UPDATE users SET friends = '".$friends."' WHERE user_name = '".$from."'");
$stmt->execute();
echo $to;
?>