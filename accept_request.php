<?php
$from = $_POST['from'];
$to = $_POST['to'];
$con = new mysqli("127.0.0.1", "root", "", "network");

//friends
$stmt = $con->prepare("SELECT friends FROM users WHERE user_name='".$to."'");
$stmt->execute();
$result = $stmt->get_result();
$friends = $result->fetch_object()->friends;

if($friends == "") $friends.=$from;
else {
    $friends.=",".$from;
}
$stmt = $con->prepare("UPDATE users SET friends = '".$friends."' WHERE user_name = '".$to."'");
$stmt->execute();

$stmt = $con->prepare("SELECT friends FROM users WHERE user_name='".$from."'");
$stmt->execute();
$result = $stmt->get_result();
$friends = $result->fetch_object()->friends;

if($friends == "") $friends.=$to;
else {
    $friends.=",".$to;
}
$stmt = $con->prepare("UPDATE users SET friends = '".$friends."' WHERE user_name = '".$from."'");
$stmt->execute();



//requests
$stmt = $con->prepare("SELECT requests FROM users WHERE user_name='".$to."'");
$stmt->execute();
$result = $stmt->get_result();
$requests = $result->fetch_object()->requests;
$check = explode(",", $requests);
if(count($check) == 1) {
    $requests = str_replace($from, "", $requests);
}
else {
    $requests = str_replace(",".$from, "", $requests);
    $requests = str_replace($from.",", "", $requests);
}
$stmt = $con->prepare("UPDATE users SET requests = '".$requests."' WHERE user_name = '".$to."'");
$stmt->execute();




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