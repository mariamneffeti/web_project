<?php
$host="localhost";
$dbname="web_project";
$username= "root";
$password="";

try{
    $conn=new pdo("mysql:host=$host;dbname=$dbname;charset=utf8",$username,$password,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],);

}
catch(Exception $e){
    die('Erreur : '.$e->getMessage());
}