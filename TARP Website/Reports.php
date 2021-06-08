<?php 
    $pdo = new PDO('mysql:host = localhost; port = 3306; dbname=pms', 'Manoj', '123');
    session_start();
    if($_SESSION['user'] == null)
        header("Location: login.php");
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
                <h1>Pothole Monitering System</h1>
                <a href="Home.php" ><button class="greenbutton goback">Go Back</button></a>
                <a  href="logout.php"><button class="redbutton logoutbutton">Logout</button></a>
            </header>
            
            <div class="pothole_middle">
                <div class="search">
                    <form method='post'>
                        <input type=text class='textbox' name='username'placeholder="Search by name"> 
                        <pre class='text'> or  Search by Date: </pre>
                        <input type=date  class='date' name='fromdate'> <pre class='text'> to </pre> <input type=date class='date' name='todate'>
                        <input type='submit' class="greenbutton submitbutton">
                    </form>
                </div>
                <br>
                <?php
                    if(isset($_SESSION["error"])){
                        echo("<p style='color:red;font-weight:bold;'>".$_SESSION["error"]."</p>");
                        unset($_SESSION["error"]);
                    }
            
                ?>
                <table>
                    <tr>
                        <th>S.No</th>
                        <th>Location</th>
                        <th>Depth</th>
                        <th>Status</th>
                        <th>Incharge</th>
                        <th>Resolved Date</th>
                    </tr>
                    <?php
                        if(isset($_POST['username']) && isset($_POST['fromdate']) && isset($_POST['todate'])){
                            if($_POST['username'] != null && $_POST['fromdate'] ==null && $_POST['todate']==null){
                                /*echo($_POST['username']);
                                echo($_POST['fromdate']);
                                echo($_POST['todate']);*/
                                $st = $pdo->prepare("SELECT * FROM potholes where status = 'Resolved' and incharge = :name ORDER BY resolved_date DESC");
                                $st->execute(array( ':name' => $_POST['username']));
                                $column=1;
                                while ($row = $st->fetch(PDO::FETCH_ASSOC)){
                                    echo("<tr>");
                                    echo("<td>".$column."</td>");
                                    echo("<td><a href='https://www.google.com/maps/search/?api=1&query=".$row['latitude'].", ".$row['longtitude']."' target='_blank'><button class='locatebutton'>Locate</button></a></td>");
                                    echo("<td>".$row['depth']."</td>");
                                    echo("<td>".$row['status']."</td>");
                                    echo("<td>".$row['incharge']."</td>");
                                    $st1 = $pdo->prepare("SELECT DATE_FORMAT(:t, '%d-%m-%Y') AS DATE");
                                    $st1->execute(array( ':t' => $row['resolved_date']));
                                    $row1 = $st1->fetch(PDO::FETCH_ASSOC);
                                    echo("<td>".$row1['DATE']."</td>");
                                    $column++;    
                                }
                            }
                    
                        elseif($_POST['username'] == null && $_POST['fromdate'] !=null && $_POST['todate']!=null){
                            //echo("hi");
                            $st = $pdo->prepare("SELECT * FROM potholes where status = 'Resolved' and (resolved_date BETWEEN DATE_FORMAT( :fd, '%Y-%m-%d') AND DATE_FORMAT(:td, '%Y-%m-%d')) ORDER BY resolved_date DESC");
                            $FDate = $_POST['fromdate'];
                            $fromDate = date("Y-m-d", strtotime($FDate));
                            
                            $TDate = $_POST['todate'];
                            $toDate = date("Y-m-d", strtotime($TDate));
                            //echo($fromDate." ". $toDate);
                            $st->execute(array(
                                ':fd' => $fromDate,
                                ':td' => $toDate
                            ));
                            $column=1;
                            while ($row = $st->fetch(PDO::FETCH_ASSOC)){
                                echo("<tr>");
                                echo("<td>".$column."</td>");
                                echo("<td><a href='https://www.google.com/maps/search/?api=1&query=".$row['latitude'].", ".$row['longtitude']."' target='_blank'><button class='locatebutton'>Locate</button></a></td>");
                                echo("<td>".$row['depth']."</td>");
                                echo("<td>".$row['status']."</td>");
                                echo("<td>".$row['incharge']."</td>");
                                $st1 = $pdo->prepare("SELECT DATE_FORMAT(:t, '%d-%m-%Y') AS DATE");
                                $st1->execute(array( ':t' => $row['resolved_date']));
                                $row1 = $st1->fetch(PDO::FETCH_ASSOC);
                                echo("<td>".$row1['DATE']."</td>");
                                $column++;    
                            }
                        }
                        elseif($_POST['username'] == null && $_POST['fromdate'] ==null && $_POST['todate']!=null){
                            $_SESSION['error'] = "Please Enter from date";
                            header( 'Location: Reports.php' ) ;
                        }
                        elseif($_POST['username'] == null && $_POST['fromdate'] !=null && $_POST['todate']==null){
                            $_SESSION['error'] = "Please Enter to date";
                            header( 'Location: Reports.php' ) ;
                        }
                        elseif($_POST['username'] != null && $_POST['fromdate'] !=null && $_POST['todate']!= null){
                            //echo("bye");
                            $st = $pdo->prepare("SELECT * FROM potholes where status = 'Resolved' and incharge = :name AND (resolved_date BETWEEN DATE_FORMAT( :fd, '%Y-%m-%d') AND DATE_FORMAT(:td, '%Y-%m-%d')) ORDER BY resolved_date DESC");
                            $st->execute(array(
                                ':name' => $_POST['username'],
                                ':fd' => $_POST['fromdate'],
                                ':td' => $_POST['todate']
                            ));
                            $column=1;
                            while ($row = $st->fetch(PDO::FETCH_ASSOC)){
                                echo("<tr>");
                                echo("<td>".$column."</td>");
                                echo("<td><a href='https://www.google.com/maps/search/?api=1&query=".$row['latitude'].", ".$row['longtitude']."' target='_blank'><button class='locatebutton'>Locate</button></a></td>");
                                echo("<td>".$row['depth']."</td>");
                                echo("<td>".$row['status']."</td>");
                                echo("<td>".$row['incharge']."</td>");
                                $st1 = $pdo->prepare("SELECT DATE_FORMAT(:t, '%d-%m-%Y') AS DATE");
                                $st1->execute(array( ':t' => $row['resolved_date']));
                                $row1 = $st1->fetch(PDO::FETCH_ASSOC);
                                echo("<td>".$row1['DATE']."</td>");
                                $column++;    
                            }
                        }
                        else{
                            $_SESSION['error'] = "Please Enter complete data(incharge name or dates or both)";
                            header( 'Location: Reports.php' ) ;
                        }
                    }
                        else{
                            $st = $pdo->prepare("SELECT * FROM potholes where status = 'Resolved' ORDER BY resolved_date DESC");
                            $st->execute(array());
                            $column=1;
                            while ($row = $st->fetch(PDO::FETCH_ASSOC)){
                                echo("<tr>");
                                echo("<td>".$column."</td>");
                                echo("<td><a href='https://www.google.com/maps/search/?api=1&query=".$row['latitude'].", ".$row['longtitude']."' target='_blank'><button class='locatebutton'>Locate</button></a></td>");
                                echo("<td>".$row['depth']."</td>");
                                echo("<td>".$row['status']."</td>");
                                echo("<td>".$row['incharge']."</td>");
                                $st1 = $pdo->prepare("SELECT DATE_FORMAT(:t, '%d-%m-%Y') AS dat");
                                $st1->execute(array( ':t' => $row['resolved_date']));
                                $row1 = $st1->fetch(PDO::FETCH_ASSOC);
                                echo("<td>".$row1['dat']."</td>");
                                $column++;    
                            }
                        }


                    ?>
                    <!--
                    <tr>
                        <td>1.</td>
                        <td><a href="https://www.google.com/maps/place/13%C2%B037'54.4%22N+79%C2%B025'10.8%22E/@13.6316295,79.4188997,18.83z/data=!4m5!3m4!1s0x0:0x0!8m2!3d13.631787!4d79.419653" target="_blank"><button class="locatebutton">Locate</button></a></td>
                        <td>1.5 m</td>
                        <td>Resolved</td>
                        <td>N.Krishna Murty</td>
                        <td>25-09-2020</td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td><a href="https://www.google.com/maps/place/13%C2%B037'52.0%22N+79%C2%B024'56.2%22E/@13.6309035,79.4151684,19.38z/data=!4m5!3m4!1s0x0:0x0!8m2!3d13.631114!4d79.415607" target="_blank"><button class="locatebutton">Locate</button></a></td>
                        <td>1.25 m</td>
                        <td>Pending</td>
                        <td>Manoj</td>
                        <td>05-02-2021</td>
                    </tr>-->
                </table>


                
            </div>
        </div>        

        
    </body>
</html>