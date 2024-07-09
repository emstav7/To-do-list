<?php

$host = 'localhost';
$db = 'todo_list';
$user = 'root';
$pass = ''; 
                  // try...catch Exception
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   //setAttribute defini des attributs pour la connexion PDO, une methode de l'objet PDO 
} catch (PDOException $e) {                                
    die("Could not connect to the database $db :" . $e->getMessage());
}
?>
