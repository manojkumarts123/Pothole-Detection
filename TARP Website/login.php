<?php 
    $pdo = new PDO('mysql:host = localhost; port = 3306; dbname=pms', 'Manoj', '123');
    session_start();
    if ( isset($_POST["username"]) && isset($_POST["password"]) ) {
        unset($_SESSION["user"]);  // Logout current user
        $sql = "SELECT * FROM users where username = :un";
        $st = $pdo->prepare($sql);
        $st->execute(array(
            ':un' => $_POST['username'])
        );

        $row = $st->fetch(PDO::FETCH_ASSOC);

        $name = $row['name'];
        $pw = $row['password'];

        if ( $_POST['password'] == $pw) {
            $_SESSION["user"] = $row['username'];
            $_SESSION["name"] = $row['name'];
            $_SESSION["message"] = "Hi ".$name."!!Logged in Successfully";
            header( 'Location: home.php' ) ;
            return;
        } else {
            $_SESSION["error"] = "Incorrect username or password.";
            header( 'Location: login.php' ) ;
            return;
        }
    }
?>

<html>
    <head>
        <title>PMS:Login</title>

        <link rel="stylesheet" href="PMS.css">
    </head>
    <body>
        <h1 class="loginhead">Pothole Monitering System</h1>

        <div class="login_middle">
            <h2>Login</h2>
            <?php
                if(isset($_SESSION["error"])){
                    echo("<p style='color:red;font-weight:bold;'>".$_SESSION["error"]."</p>");
                    unset($_SESSION["error"]);
                }
                
                if(isset($_SESSION["message"])){
                    echo("<p style='color:green;font-weight:bold'>".$_SESSION["message"]."</p>");
                    unset($_SESSION["message"]);
                }
            
            ?>
            <form method="post">
                <input type="text" name="username" id="username" placeholder="User Name" required><br/><br/>
                <input type="password" name="password" id="password" placeholder="Password" required><br/><br/>
                <input type="submit" class="greenbutton loginbutton" value="Log in"> 
            </form>
        </div>
    </body>
</html>