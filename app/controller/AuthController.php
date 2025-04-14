<?php

class AuthController {
    private $db;
    private $messages = [];
    
    /**
     * Initialize controller and connect to database
     */
    public function __construct() {
        try {
            // Try to use Database class if available
            if (file_exists('../config/database.php')) {
                require_once '../config/database.php';
                $database = new Database();
                $this->db = $database->getConnection();
            } else {
                // Fallback to direct PDO connection
                $dsn = 'mysql:host=localhost;dbname=greenmind;charset=utf8mb4';
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                ];
                $this->db = new PDO($dsn, 'root', '', $options);
            }
            
            // Ensure users table exists
            $this->ensureUsersTableExists();
            
            // Create default admin user if not exists
            $this->createDefaultAdmin();
            
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            // Handle gracefully
            die('Service temporarily unavailable. Please try again later.');
        }
    }
    
    /**
     * Create users table if it doesn't exist
     */
    private function ensureUsersTableExists() {
        try {
            // Check if users table exists
            $stmt = $this->db->query("SHOW TABLES LIKE 'users'");
            $tableExists = $stmt->rowCount() > 0;
            
            if (!$tableExists) {
                // Create users table
                $this->db->exec("
                    CREATE TABLE IF NOT EXISTS users (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        username VARCHAR(50) NOT NULL UNIQUE,
                        password VARCHAR(255) NOT NULL,
                        email VARCHAR(100) NULL,
                        role ENUM('admin', 'editor', 'user') NOT NULL DEFAULT 'user',
                        full_name VARCHAR(100) NULL,
                        last_login DATETIME NULL,
                        status ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
                ");
                
                error_log("Users table created successfully");
            }
        } catch (PDOException $e) {
            error_log("Error creating users table: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Create default admin user if it doesn't exist
     */
    private function createDefaultAdmin() {
        try {
            // Check if admin user exists
            $stmt = $this->db->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->execute(['username' => 'admin']);
            
            if ($stmt->rowCount() == 0) {
                // Create default admin
                $password = password_hash('admin123', PASSWORD_DEFAULT);
                $stmt = $this->db->prepare("
                    INSERT INTO users (username, password, role, full_name, status) 
                    VALUES (:username, :password, :role, :full_name, :status)
                ");
                $stmt->execute([
                    'username' => 'admin',
                    'password' => $password,
                    'role' => 'admin',
                    'full_name' => 'Administrator',
                    'status' => 'active'
                ]);
                
                error_log("Default admin user created successfully");
            }
        } catch (PDOException $e) {
            error_log("Error creating default admin: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Handle user login
     * 
     * @param array $data Form data containing username and password
     * @return bool|string True on success, error message on failure
     */
    public function login($data) {
        try {
            // Validate CSRF token
            if (!isset($data['csrf_token']) || !isset($_SESSION['csrf_token']) || $data['csrf_token'] !== $_SESSION['csrf_token']) {
                return "Security validation failed. Please refresh the page and try again.";
            }
            
            // Validate required fields
            if (empty($data['username']) || empty($data['password'])) {
                return "Username and password are required.";
            }
            
            // Sanitize input
            $username = $this->sanitizeInput($data['username']);
            $password = $data['password']; // Don't sanitize password to preserve special characters
            
            // Get user by username
            $stmt = $this->db->prepare("
                SELECT id, username, password, role, status 
                FROM users 
                WHERE username = :username
            ");
            $stmt->execute(['username' => $username]);
            
            if ($stmt->rowCount() === 0) {
                // Username not found, but don't reveal this for security
                return "Invalid username or password.";
            }
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Check if user is active
            if ($user['status'] !== 'active') {
                return "Your account is not active. Please contact an administrator.";
            }
            
            // Verify password
            if (!password_verify($password, $user['password'])) {
                return "Invalid username or password.";
            }
            
            // Check if password needs rehash (in case of algorithm changes)
            if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $updateStmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
                $updateStmt->execute([
                    'password' => $newHash,
                    'id' => $user['id']
                ]);
            }
            
            // Update last login time
            $updateLoginStmt = $this->db->prepare("UPDATE users SET last_login = NOW() WHERE id = :id");
            $updateLoginStmt->execute(['id' => $user['id']]);
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['last_login'] = time();
            
            // Regenerate session ID for security
            session_regenerate_id(true);
            
            // Create a new CSRF token
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            
            return true;
            
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return "An error occurred during login. Please try again later.";
        }
    }
    
    /**
     * Handle user logout
     */
    public function logout() {
        // Clear all session data
        $_SESSION = [];
        
        // Delete the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy the session
        session_destroy();
    }
    
    /**
     * Check if user is logged in
     * 
     * @return bool True if user is logged in, false otherwise
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']);
    }
    
    /**
     * Check if user is admin
     * 
     * @return bool True if user is admin, false otherwise
     */
    public function isAdmin() {
        return $this->isLoggedIn() && $_SESSION['role'] === 'admin';
    }
    
    /**
     * Get current user data
     * 
     * @return array|null User data or null if not logged in
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        try {
            $stmt = $this->db->prepare("
                SELECT id, username, email, role, full_name, last_login, status, created_at 
                FROM users 
                WHERE id = :id
            ");
            $stmt->execute(['id' => $_SESSION['user_id']]);
            
            if ($stmt->rowCount() === 0) {
                // User no longer exists in database
                $this->logout();
                return null;
            }
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error getting current user: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Set success message in session
     *
     * @param string $message Success message
     */
    public function setSuccessMessage($message) {
        $_SESSION['success_message'] = $message;
    }
    
    /**
     * Set error message in session
     *
     * @param string $message Error message
     */
    public function setErrorMessage($message) {
        $_SESSION['error_message'] = $message;
    }
    
    /**
     * Sanitize user input
     * 
     * @param string $input Input to sanitize
     * @return string Sanitized input
     */
    private function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
