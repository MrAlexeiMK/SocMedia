<?php
if(!isset($_COOKIE['username'])) {
    header('Location: /login.php');
}
if(isset($_POST['exit'])) {
    unset($_COOKIE['username']);
    setcookie('username', null, -1, '/');
    header('Location: login.php');
}
//set status online
$username = $_COOKIE['username'];
$con = new mysqli("127.0.0.1", "root", "", "network");
$stmt = $con->prepare("UPDATE users SET status = 'online' WHERE user_name = '".$username."'");
$stmt->execute();
?>
<html>
<head>
<script src="scripts/jquery.js"></script>
<link rel="stylesheet" href="styles/styles.css">
<script>
    //timer of get offline status
    $(document).ready(function() { 
        setTimeout(set_status_offline, 120000);
    });
    
    //set status offline after go out
    window.onbeforeunload = function() {
        set_status_offline();
    };
        
    function set_status_offline() {  
        $.ajax({  
            url: "set_status_offline.php",
            type: "POST",
            data: {"username": $('#nickname').text()} 
        });
    }  
    
    function add_to_friend(username) {    
    $.ajax({  
            url: "add_to_friend.php",
            type: "POST",
            data: {"from": $('#nickname').text(), "to": username},
            success: function(html){
                $('#info').html(html);
            }
        });
    }
        
    function accept_request(user) {
    $.ajax({  
            url: "accept_request.php",
            type: "POST",
            data: {"from": $('#nickname').text(), "to": user},
            success: function(html){
                if(html[0] == '1') {
                    $('#'+html.substring(1)).remove();
                }
                else $('#info').html(html);
            }
        });
    }
    
    function decline_request(user) {
    $.ajax({  
            url: "decline_request.php",
            type: "POST",
            data: {"from": $('#nickname').text(), "to": user},
            success: function(html){
                if(html[0] == '1') {
                    $('#'+html.substring(1)).remove();
                }
                else $('#info').html(html);
            }
        });
    }
        
    function remove_friend(user) {
    $.ajax({  
            url: "remove_friend.php",
            type: "POST",
            data: {"from": $('#nickname').text(), "to": user},
            success: function(html){
                $('#'+html).remove();
            }
        });
    }
    
    function send_message() {
        var msg = $("#type").val();
        var to = $("#who").text();
        var from = $('#nickname').text();
        if(msg != "") {
            $.ajax({  
                url: "send_message.php",
                type: "POST",
                data: {"from": from, "to": to, "messages": msg},
                success: function(html) {
                    var m = html.replaceAll("|", "\n");
                    $("#area").html(m);
                }
            });
        }
        $('#type').val("");
    }
    
    function open_messaging(username) {
    $.ajax({  
            url: "get_messages.php",
            type: "POST",
            data: {"from": $('#nickname').text(), "to": username},
            success: function(html) {
                var m = html.replaceAll("|", "\n");
                $("#im").html("<span id='who'>"+username+"</span><br /><textarea id='area' disabled>"+m+"</textarea><br /><br /><textarea id='type'></textarea><br /><input type='submit' onclick='send_message()' id='send' value='Отправить' />");
                $("#center").remove();
            }
        });  
    }
    
    String.prototype.replaceAll = function(search, replacement) {
        var target = this;
        return target.split(search).join(replacement);
    };
</script>
</head>
<body>

<div id="menu">
<h3 id="nickname"><?=$username?></h3>
    <form action="main.php" method="POST">
        <input type="submit" value="Пользователи" name="users"> <br />
        <input type="submit" value="Сообщения" name="messages"> <br />
        <input type="submit" value="Друзья" name="friends"> <br />
        <input type="submit" value="Заявки в друзья" name="requests"> <br />
        <input type="submit" value="Выйти" name="exit"> <br />
    </form>
</div>

<div id="center">
<?php
if(empty($_POST) || isset($_POST['users'])) {
    $con = new mysqli("127.0.0.1", "root", "", "network");
    $stmt = $con->prepare("SELECT user_name, status FROM users");
    $stmt->execute();
    $result = $stmt->get_result();
    echo "<table border='2' id='table'>";
    while($user = $result->fetch_object()) {
        if($user->user_name == $username) continue;
        $color = "red";
        if($user->status == "online") $color = "lightgreen";
        echo "<tr><td style='background-color: ".$color."'>".$user->user_name.", статус: ".$user->status." <img src='icons/add.jpg' title='Отправить предложение на дружбу' onclick=\"add_to_friend('".$user->user_name."')\" '> <img src='icons/send.jpg' title='Написать сообщение' onclick=\"open_messaging('".$user->user_name."')\" '></td></tr>"; 
    }
    echo "</table>";
}
else if(isset($_POST['requests'])) {
    $con = new mysqli("127.0.0.1", "root", "", "network");
    $stmt = $con->prepare("SELECT requests FROM users WHERE user_name='".$username."'");
    $stmt->execute();
    $result = $stmt->get_result();
    $requests = $result->fetch_object()->requests;
    $list = explode(",", $requests);
    $i = 0;
    $len = count($list);
    echo "<table border='2' id='table'>";
    while($i < $len) {
        $color = "lightgray";
        $user = $list[$i];
        if($user != "") {
            echo "<tr id='".$user."'><td style='background-color: ".$color."'>".$user."<img src='icons/del.jpg' title='Отклонить' onclick=\"decline_request('".$user."')\" '> <img src='icons/add.jpg' title='Добавить в друзья' onclick=\"accept_request('".$user."')\" '></td></tr>";
        }
        $i++;
    }
    echo "</table>";
}
else if(isset($_POST['friends'])) {
    $con = new mysqli("127.0.0.1", "root", "", "network");
    $stmt = $con->prepare("SELECT friends FROM users WHERE user_name='".$username."'");
    $stmt->execute();
    $result = $stmt->get_result();
    $friends = $result->fetch_object()->friends;
    $list = explode(",", $friends);
    $i = 0;
    $len = count($list);
    echo "<table border='2' id='table'>";
    while($i < $len) {
        $color = "lightblue";
        $user = $list[$i];
        if($user != "") {
            echo "<tr id='".$user."'><td style='background-color: ".$color."'>".$user." <img src='icons/send.jpg' title='Написать сообщение' onclick=\"open_messaging('".$user."')\" '><img src='icons/del.jpg' title='Удалить из друзей' onclick=\"remove_friend('".$user."')\" '></td></tr>";
        }
        $i++;
    }
    echo "</table>";
}
else {
    $con = new mysqli("127.0.0.1", "root", "", "network");
    $stmt = $con->prepare("SELECT to_user FROM messages WHERE from_user='".$username."'");
    $stmt->execute();
    $result = $stmt->get_result();
    $color = "pink";
    echo "<table border='2' id='table'>";
    while(($us = $result->fetch_object()) != null) {
        $user = $us->to_user;
        echo "<tr><td id='msg' onclick=\"open_messaging('".$user."')\" style='background-color: ".$color."'>".$user."</td></tr>";
    }
    echo "</table>";
}
?>
<span id="info"></span>
<br />
</div>

<div id="im">
    
</div>
</body>
</html>