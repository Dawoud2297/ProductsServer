<?php

class Dbc
{
    private $DB_NAME;
    private $DB_USERNAME;
    private $DB_PASSWORD;
    private $DB_HOST;

    final protected function connection()
    {
        // $this->DB_HOST = "localhost";
        // $this->DB_USERNAME = "mahmoud";
        // $this->DB_PASSWORD = "#1q2w3e4r5t#";
        // $this->DB_NAME = "products";

        // $connection = new mysqli($this->DB_HOST,$this->DB_USERNAME,$this->DB_PASSWORD,$this->DB_NAME);

        // if($connection->connect_error) {
        //     die("Connection Error : " . $connection->connect_error);
        // }
        // return $connection;
        $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
        $cleardb_server = $cleardb_url["host"];
        $cleardb_username = $cleardb_url["user"];
        $cleardb_password = $cleardb_url["pass"];
        $cleardb_db = substr($cleardb_url["path"],1);
        $active_group = 'default';
        $query_builder = TRUE;
        // Connect to DB
        $conn = new mysqli($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);


        // $connect = new mysqli($this->DB_HOST, $this->DB_USERNAME, $this->DB_PASSWORD, $this->DB_NAME);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }
}


// PDO
// try {
//     $connection = new PDO(
//         "mysql:host=$this->DB_HOST;dbname=$this->DB_NAME",
//         $this->DB_USERNAME,
//         $this->DB_PASSWORD
//     );
//     $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     return $connection;
// } catch (PDOException $e) {
//     die("Connection failed: " . $e->getMessage());
// }
// $connection = null;