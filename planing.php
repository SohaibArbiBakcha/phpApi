<?php
require "DataBase.php";
$db = new DataBase();
$myObj = new \stdClass();
if (isset($_POST['user_id'])) {
    if ($db->dbConnect()) {
        $myjson = $db->getPlanning($_POST['user_id']);
        $status = $myjson["statut"] ;
        if ($status == true) {          
            echo  $myjson;
        } else echo "Oops !! Something went wrong...";
    } else echo "Error: Database connection";
} else echo "No data found";
?>
