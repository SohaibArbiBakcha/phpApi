<?php
require "DataBase.php";
$db = new DataBase();
$myObj = new \stdClass();
if (isset($_POST['email']) && isset($_POST['password'])) {
    if ($db->dbConnect()) {
        if ($db->logIn("users", $_POST['email'], $_POST['password']) != false) {
            $id = $db->logIn("users", $_POST['email'], $_POST['password']);
            $myObj -> id = $id;
            $myObj -> statut = "Login Success";
            $myjson = json_encode($myObj);

            
            echo  $myjson;
        } else echo "Username or Password wrong";
    } else echo "Error: Database connection";
} else echo "All fields are required";
?>

<!--SELECT DISTINCT subjects.name, routines.day, routines.starting_hour, routines.starting_minute, routines.ending_hour,routines.ending_minute, classes.name from routines, subjects, classes, students WHERE routines.class_id = subjects.class_id AND subjects.school_id = classes.school_id AND classes.school_id = students.school_id AND students.user_id = 13; -->