<?php
$from = $_POST['from'];
$to = $_POST['to'];
$con = new mysqli("127.0.0.1", "root", "", "network");
$stmt = $con->prepare("SELECT requests FROM users WHERE user_name='".$to."'");
$stmt2 = $con->prepare("SELECT requests FROM users WHERE user_name='".$from."'");
$stmt3 = $con->prepare("SELECT friends FROM users WHERE user_name='".$to."'");

$stmt->execute();
$result = $stmt->get_result();
$requests = $result->fetch_object()->requests;
$check = explode(",", $requests);

$stmt2->execute();
$result2 = $stmt2->get_result();
$requests2 = $result2->fetch_object()->requests;
$check2 = explode(",", $requests2);

$stmt3->execute();
$result3 = $stmt3->get_result();
$requests3 = $result3->fetch_object()->requests;
$check3 = explode(",", $requests3);

$flag = true;
for($i = 0; $i < count($check); $i++) {
    if($check[$i] == $from) {
        $flag = false;
        echo "Вы уже отправляли заявку в друзья этому пользователю";
        break;
    }
}
for($i = 0; $i < count($check2); $i++) {
    if($check2[$i] == $to) {
        $flag = false;
        echo "Этот пользователь уже отправил вам заявку в друзья";
        break;
    }
}
for($i = 0; $i < count($check3); $i++) {
    if($check3[$i] == $from) {
        $flag = false;
        echo "Вы уже друзья";
        break;
    }
}
if($flag) {
    if($requests == "") $requests.=$from;
    else {
        $requests.=",".$from;
    }
    $stmt = $con->prepare("UPDATE users SET requests = '".$requests."' WHERE user_name = '".$to."'");
    $stmt->execute();
    echo "Заявка в друзья успешно отправлена";
}
?>