<?php
    if(!empty($_POST)) {
        if(isset($_POST['enter'])) {
            header('Location: login.php');
        }
        if(isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] != "" && $_POST['password'] != "") {
            $username = $_POST['username'];
            $pass = $_POST['password'];
            $con = new mysqli("127.0.0.1", "root", "", "network");
            $stmt = $con->prepare("INSERT INTO users (user_name, password, status, friends, requests) VALUES ('".$username."', '".$pass."', 'offline', '', '')");
            if(!$stmt->execute()) {
                echo "Пользователь с этим ником уже существует!";
            }
            else {
                setcookie('username', $username);
                header('Location: main.php');
            }
        }
        else {
            echo "Заполните все поля!";
        }
    }
?>
<div id="reg">
<h3>Регистрация</h3>
<form action="reg.php" method="POST">
<input type="text" name="username" placeholder="Никнейм" /> <br />
<input type="password" name="password" placeholder="Пароль" /> <br />
<input type="submit" name="register" value="Регистрация" />
<input type="submit" name="enter" value="Войти" />
</form>
</div>