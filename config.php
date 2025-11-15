<?php

$user = 'Banks';
$password = 'Bank$101';
$database = 'engineering001';
$host = 'localhost';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn){
    echo "Error in connection" . mysqli_connect_error();
    return;
}