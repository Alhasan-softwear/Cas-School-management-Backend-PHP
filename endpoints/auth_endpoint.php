<?php
// Authentication endpoint - handles login, register, logout

// Get request method
$request_method = $_SERVER['REQUEST_METHOD'];

/**
 * Handle user registration
 * 
 * @return void
 */
function register() {
    global $cons;
    
    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Method Not Allowed
        echo json_encode(array("message" => "Method not allowed. Please use POST for registration."));
        return;
    }
    
    try {
        // Get posted data
        $data = json_decode(file_get_contents("php://input"));
        
        // Validate required fields
        if (
            !isset($data->username) || empty($data->username) ||
            !isset($data->email) || empty($data->email) ||
            !isset($data->password) || empty($data->password)
        ) {
            http_response_code(400); // Bad Request
            echo json_encode(array("message" => "Unable to register. Missing required fields."));
            return;
        }
        
        // Sanitize input
        $username = mysqli_real_escape_string($cons, $data->username);
        $email = mysqli_real_escape_string($cons, $data->email);
        $password = mysqli_real_escape_string($cons, $data->password);
        
        // Check if email already exists
        $check_query = "SELECT * FROM users WHERE email = '$email'";
        $check_result = mysqli_query($cons, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            http_response_code(409); // Conflict
            echo json_encode(array("message" => "Email already in use."));
            return;
        }
        
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Get current timestamp
        $created_at = date('Y-m-d H:i:s');
        
        // Query to insert new user
        $query = "INSERT INTO users (username, email, password, created_at) 
                  VALUES ('$username', '$email', '$hashed_password', '$created_at')";
        
        if (mysqli_query($cons, $query)) {
            $user_id = mysqli_insert_id($cons);
            
            http_response_code(201); // Created
            echo json_encode(array(
                "message" => "Registration successful.",
                "id" => $user_id,
                "username" => $username,
                "email" => $email,
                "created_at" => $created_at
            ));
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(array("message" => "Registration failed: " . mysqli_error($cons)));
        }
    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Server error: " . $e->getMessage()));
    }
}

/**
 * Handle user login
 * 
 * @return void
 */
function login() {
    global $cons;
    
    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Method Not Allowed
        echo json_encode(array("message" => "Method not allowed. Please use POST for login."));
        return;
    }
    
    try {
        // Get posted data
        $data = json_decode(file_get_contents("php://input"));
        
        // Validate required fields
        if (
            !isset($data->email) || empty($data->email) ||
            !isset($data->password) || empty($data->password)
        ) {
            http_response_code(400); // Bad Request
            echo json_encode(array("message" => "Unable to login. Missing email or password."));
            return;
        }
        
        // Sanitize input
        $email = mysqli_real_escape_string($cons, $data->email);
        $password = mysqli_real_escape_string($cons, $data->password);
        
        // Query to get user by email
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($cons, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                
                // Create session token (in a real app, you'd use JWT or similar)
                $token = bin2hex(random_bytes(32));
                $user_id = $user['id'];
                $expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));
                
                // Store token in database (in a real app)
                $token_query = "INSERT INTO user_tokens (user_id, token, expires_at) 
                               VALUES ('$user_id', '$token', '$expires_at')";
                mysqli_query($cons, $token_query);
                
                http_response_code(200); // OK
                echo json_encode(array(
                    "message" => "Login successful.",
                    "token" => $token,
                    "user" => array(
                        "id" => $user['id'],
                        "username" => $user['username'],
                        "email" => $user['email']
                    )
                ));
            } else {
                // Password is incorrect
                http_response_code(401); // Unauthorized
                echo json_encode(array("message" => "Invalid credentials."));
            }
        } else {
            // User not found
            http_response_code(401); // Unauthorized
            echo json_encode(array("message" => "Invalid credentials."));
        }
    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Server error: " . $e->getMessage()));
    }
}

/**
 * Handle user logout
 * 
 * @return void
 */
function logout() {
    global $cons;
    
    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Method Not Allowed
        echo json_encode(array("message" => "Method not allowed. Please use POST for logout."));
        return;
    }
    
    try {
        // Get authorization header
        $headers = getallheaders();
        $auth_header = isset($headers['Authorization']) ? $headers['Authorization'] : '';
        
        // Check if token exists
        if (empty($auth_header) || !preg_match('/Bearer\s(\S+)/', $auth_header, $matches)) {
            http_response_code(400); // Bad Request
            echo json_encode(array("message" => "No token provided."));
            return;
        }
        
        $token = $matches[1];
        
        // Delete token from database (in a real app)
        $query = "DELETE FROM user_tokens WHERE token = '$token'";
        mysqli_query($cons, $query);
        
        http_response_code(200); // OK
        echo json_encode(array("message" => "Logout successful."));
    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Server error: " . $e->getMessage()));
    }
}

// Route to appropriate function based on action parameter
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'register':
        register();
        break;
        
    case 'login':
        login();
        break;
        
    case 'logout':
        logout();
        break;
        
    default:
        // Action not specified or invalid
        http_response_code(400); // Bad Request
        echo json_encode(array(
            "message" => "Invalid or missing action parameter. Available actions: register, login, logout",
            "usage" => "?url=/auth&action=login"
        ));
        break;
}
?>