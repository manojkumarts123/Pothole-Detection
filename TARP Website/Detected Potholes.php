<?php 
    $pdo = new PDO('mysql:host = localhost; port = 3306; dbname=pms', 'Manoj', '123');
    session_start();

    if($_SESSION['user'] == null)
        header("Location: login.php");

    if(isset($_POST["id"])){
        
        $st = $pdo->prepare("UPDATE potholes SET status = :s, incharge = :i, resolved_date = (DATE_FORMAT(NOW(), '%Y-%m-%d'))  where id = :id");
        echo("<p>".$_POST['status']."</p>");
        echo("<p>".$_POST['id']."</p>");
        $st->execute(array(
            ':s' => $_POST["status"],
            ':id' => $_POST["id"],
            ':i' => $_SESSION['name']
        ));
        $_SESSION['message'] = "Status of the Pothole updated Successfully";
        header("location:Detected Potholes.php");
        return;
    }
?>
  
<html>
    <head>
        <title>PMS:Detected Potholes</title>

        <link rel="stylesheet" href="PMS.css">
    </head>
    <body>
        <div class="home_middle">
            <header class="clearfix">
                <p style="float:right; font-weight:bold"><?php echo("User: ".$_SESSION['name']); ?></p><br>
                <h1>Pothole Monitering System</h1><br>
                <a href="Home.php" ><button class="greenbutton goback">Go Back</button></a>
                <a  href="logout.php"><button class="redbutton logoutbutton">Logout</button></a>
            </header>
            
            <div class="pothole_middle">
                <?php
                if(isset($_SESSION["message"])){
                    echo("<p style='color:green;font-weight:bold'>".$_SESSION["message"]."</p>");
                    unset($_SESSION["message"]);
                }?>
                <table >
                    <tr>
                        <th>S.No</th>
                        <th>Location</th>
                        <th>Depth</th>
                        <th>Status</th>
                        <th>Edit Status</th>
                    </tr>
                    <?php 
                        $st = $pdo->prepare("SELECT * FROM potholes where status in ('Pending', 'Work in Progress') and (incharge is NULL or incharge = :name )");
                        $st->execute(array( ':name' => $_SESSION['name']));
                        $column=1;
                        while ($row = $st->fetch(PDO::FETCH_ASSOC)){
                            echo("<tr>");
                            echo("<td>".$column."</td>");
                            echo("<td><a href='https://www.google.com/maps/search/?api=1&query=".$row['latitude'].", ".$row['longtitude']."' target='_blank'><button class='locatebutton'>Locate</button></a></td>");
                            echo("<td>".$row['depth']."</td>");
                            echo("<td>".$row['status']."</td>");
                            if($row['status'] == 'Pending'){
                                echo("<td><form method='post' name=".$row['id']."><select id='status' name='status' onchange='check(this)'><option value='Pending' disabled>Pending</option>
                                                          <option value='Work in Progress'>Work in Progress</option>
                                                          <option value='Resolved'>Resolved</option></select>
                                                          <input type='hidden' name='id' value=".$row['id'].">
                                                          <input type='submit' id='submit' class='greenbutton submitbutton' ></form></td></tr>");
                            }
                            else{
                                    echo("<td><form method='post'><select id='status' name='status' onclick='check(this)'><option value='Pending' disabled>Pending</option>
                                                            <option value='Work in Progress' disabled>Work in Progress</option>
                                                            <option value='Resolved'>Resolved</option></select>
                                                            <input type='hidden' name='id' value=".$row['id'].">
                                                            <input type='submit' class='greenbutton submitbutton'></form></td></tr>");
                            }
                            $column++;    
                        }
                    ?>
                </table>  
            </div>
        </div>        
    </body>
</html>

<!--

13.630246, 79.423563
13.631961, 79.423979
13.635474, 79.423060
13.636731, 79.429691
13.639176, 79.427666
13.640812, 79.424678
13.645259, 79.417677
13.647407, 
