<?php
class config {
public static function getConnexion() {
$host = "localhost";
$dbname = "projetweb2a";
$username = "root";
$password = "";

try {
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
return $conn;
} catch (PDOException $e) {
die('Erreur : ' . $e->getMessage());
}
}
}
?>