<?php
               
        //connetto al db
        $DBhost = 'us-cdbr-iron-east-01.cleardb.net';
        $DBuser = 'bf1c9d658ff1ed';
        $DBpass = '3175e2fb';
        $DBname = 'ad_5b8045e5aae6ad9';
        /*$db->doQuery("CREATE TABLE ciao (id INT PRIMARY KEY);");
        $db->doQuery("INSERT INTO ciao VALUES(1);");*/
        $mysqli = new MySQLi($DBhost, $DBuser, $DBpass, $DBname);
        $myQuery = "SELECT * FROM domande ORDER BY RAND() LIMIT 1";
        $result = $mysqli->query($myQuery) or die($mysqli->error);
        $json = array();

        if(mysqli_num_rows($result)){//<--- levala, lo fa già il while il controllo
                while($row=mysqli_fetch_assoc($result)){
                        $json[]=$row;
                }
        }
        mysqli_close($mysqli);
        echo json_encode($json);
?>
