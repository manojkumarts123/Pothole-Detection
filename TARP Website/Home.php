<?php 
    $pdo = new PDO('mysql:host = localhost; port = 3306; dbname=pms', 'Manoj', '123');
    session_start();

    if($_SESSION['user'] == null)
        header("Location: login.php");
?>

<html>
    <head>
        <title>PMS:Home</title>

        <link rel="stylesheet" href="PMS.css">
    </head>
    <body>
        <div class="home_middle">
            <header class="clearfix">
                <p style="float:right;font-weight:bold"><?php echo("User: ".$_SESSION['name']); ?></p><br>
                <h1>Pothole Monitering System</h1>
                <a  href="logout.php"><button class="redbutton logoutbutton">Logout</button></a>
            </header>
            
            <!--<div class="pothole_middle">-->
                <?php
                    if(isset($_SESSION["message"])){
                        echo("<br><br><br><p style='color:green;font-weight:bold'>".$_SESSION["message"]."</p>");
                        unset($_SESSION["message"]);
                    }
                ?>
                <a href="Detected potholes.php"><div class="image img1">
                    <h2>Detected Potholes</h2>
                </div></a>
                <a href="Reports.php"><div class="image img2">
                    <h2>Pothole Reports</h2>
                </div></a>
            <!--</div>-->
        </div>        

        
    </body>
</html>