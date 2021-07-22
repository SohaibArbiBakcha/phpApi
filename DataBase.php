<?php
require "DataBaseConfig.php";

class DataBase
{
    public $connect;
    public $data;
    private $sql;
    protected $servername;
    protected $email;
    protected $password;
    protected $databasename;

    public function __construct()
    {
        $this->connect = null;
        $this->data = null;
        $this->sql = null;
        $dbc = new DataBaseConfig();
        $this->servername = $dbc->servername;
        $this->username = $dbc->username;
        $this->password = $dbc->password;
        $this->databasename = $dbc->databasename;
    }

    function dbConnect()
    {
        $this->connect = mysqli_connect($this->servername, $this->username, $this->password, $this->databasename);
        return $this->connect;
    }

    function prepareData($data)
    {
        return mysqli_real_escape_string($this->connect, stripslashes(htmlspecialchars($data)));
    }

    function logIn($table, $email, $password)
    {
        $username = $this->prepareData($email);
        $password = $this->prepareData($password);
        $this->sql = "select * from " . $table . " where email = '" . $email . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $dbusername = $row['email'];
            $dbpassword = $row['password'];
            if ($dbusername == $email && password_verify($password, $dbpassword)) {
                $login = $row['id'];
            } else $login = false;
        } else $login = false;

        return $login;
    }

    function signUp($table, $name, $email, $password)
    {
        $name = $this->prepareData($name);
        $password = $this->prepareData($password);
        $email = $this->prepareData($email);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $this->sql =
            "INSERT INTO " . $table . " (name, email, password) VALUES ('" . $name . "','" . $email . "','" . $password . "')";
        if (mysqli_query($this->connect, $this->sql)) {
            return true;
        } else return false;
    }


    function getPlanning($id)
    {
        $id = $this->prepareData($id);
        $myObj = new \stdClass();
        $rows = array();

        $this->sql =
        "SELECT DISTINCT subjects.name AS subName, routines.day, routines.starting_hour, routines.starting_minute, routines.ending_hour,routines.ending_minute, classes.name from routines, subjects, classes, students WHERE routines.class_id = subjects.class_id AND subjects.school_id = classes.school_id AND classes.school_id = students.school_id AND students.user_id = " . $id;
        
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        
        if (mysqli_num_rows($result) != 0) {
            while ($row = mysqli_fetch_assoc($result)){

                $rows[] = $row;

            }
            $myObj -> data = $rows;
            $myObj -> statut = true;
            $myjson = json_encode($myObj);
            return $myjson;
        } else $login = false;

    }

      function getUserInfo($id)
    {
        $id = $this->prepareData($id);
        $myObj = new \stdClass();
        $rows = array();

        $this->sql = "  SELECT
                            users.name as 'fullname',
                            users.address as 'stdAddress',
                            users.phone,
                            users.email ,
                            users.birthday,
                            users.blood_group ,
                            users.gender,
                            schools.name,
                            schools.address
                        from 
                             
                            schools, 
                            users,
                            routines
                        WHERE  schools.id = users.school_id
                        AND  users.id  = " . $id;
       
        // "SELECT users.id,users.name,users.address,users.phone,users.email ,users.birthday,users.blood_group ,users.gender, schools.name,schools.address FROM users, schools WHERE schools.id = users.school_id AND users.id = ". $id;
        
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        
        if (mysqli_num_rows($result) != 0) {
            while ($row = mysqli_fetch_assoc($result)){

                $rows[] = $row;

            }
            $myObj -> data = $rows;
            $myObj -> statut = true;
            $myjson = json_encode($myObj);
            return $myjson;
        } else $login = false;

    }
}

?>
