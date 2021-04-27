<?php
$from = $_POST['from'];
$to = $_POST['to'];
$msg = $_POST['messages'];
$con = new mysqli("127.0.0.1", "root", "", "network");


$stmt = $con->prepare("SELECT messages FROM messages WHERE from_user='".$from."' and to_user='".$to."'");
$stmt->execute();
$result = $stmt->get_result();
$messages = $result->fetch_object()->messages;
if($messages == "" || is_null($messages)) {
    $stmt = $con->prepare("INSERT INTO messages (from_user, to_user, messages) VALUES ('".$from."', '".$to."', '".$msg."')");
    $stmt->execute();
    echo $msg;
}
else {
    $messages.="|".$msg;
    $stmt = $con->prepare("UPDATE messages SET messages='".$messages."' WHERE from_user='".$from."' and to_user='".$to."'");
    $stmt->execute();
    echo $messages;
}



$stmt = $con->prepare("SELECT messages FROM messages WHERE from_user='".$to."' and to_user='".$from."'");
$stmt->execute();
$result = $stmt->get_result();
$messages = $result->fetch_object()->messages;
if($messages == "" || is_null($messages)) {
    $stmt = $con->prepare("INSERT INTO messages (from_user, to_user, messages) VALUES ('".$to."', '".$from."', '".$msg."')");
    $stmt->execute();
}
else {
    $messages.="|".$msg;
    $stmt = $con->prepare("UPDATE messages SET messages='".$messages."' WHERE from_user='".$to."' and to_user='".$from."'");
    $stmt->execute();
}
?>