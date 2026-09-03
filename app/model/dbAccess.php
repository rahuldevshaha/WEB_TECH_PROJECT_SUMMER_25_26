<?php

$env="localhost";
$user="root";
$password="";
$database_name="messManagerDB";



$conn = mysqli_connect($env, $user, $password, $database_name);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}




function exeQuery($sql)
{
    global $conn;
    return mysqli_query($conn, $sql);
}




function getRowCount($result)
{
    return mysqli_num_rows($result);
}




function getDataRow($result)
{
    return mysqli_fetch_assoc($result);
}




?>
