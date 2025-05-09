<?php
class Config {
    private static $host = "localhost";
    private static $dbname = "db_html";
    private static $username = "root";
    private static $password = "";
    private static $conn = null;

    // Private constructor to prevent direct object creation
    private function __construct() {}

    // Method to get the database connection
    public static function getConnexion() {
        // Check if the connection already exists
        if (self::$conn === null) {
            try {
                // Establish a new connection if not already established
                self::$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8", self::$username, self::$password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Connection failed: ' . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
?>
