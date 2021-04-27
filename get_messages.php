<?php
$from = $_POST['from'];
$to = $_POST['to'];
$con = new mysqli("127.0.0.1", "root", "", "network");

$stmt = $con->prepare("SELECT messages FROM messages WHERE from_user='".$from."' and to_user='".$to."'");
$stmt->execute();
$result = $stmt->get_result();
$messages = $result->fetch_object()->messages;

echo $messages;
?>