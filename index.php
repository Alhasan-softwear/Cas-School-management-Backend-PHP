<?php
// Set timezone
date_default_timezone_set("Africa/Lagos");

// Include database connection
require_once 'connect.php';

// Set headers for API response
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Get request method and URL endpoint
$request_method = $_SERVER['REQUEST_METHOD'];
$endpoint = isset($_GET['url']) ? $_GET['url'] : '/';

// Remove leading slash if present
if (substr($endpoint, 0, 1) === '/') {
    $endpoint = substr($endpoint, 1);
}

// Route request to appropriate endpoint file based on URL
switch ($endpoint) {
    case '':
    case '/':
        // Default endpoint - show API info
        echo json_encode([
            'status' => 'success',
            'message' => 'API is running',
            'available_endpoints' => [
                '/auth',
                '/users',
                '/posts',
                '/comments'
            ]
        ]);
        break;
        
    case 'auth':
        require_once 'endpoints/auth_endpoint.php';
        break;
        
    case 'users':
        require_once 'endpoints/users_endpoint.php';
        break;
        
    case 'posts':
        require_once 'endpoints/posts_endpoint.php';
        break;
        
    case 'comments':
        require_once 'endpoints/comments_endpoint.php';
        break;
        
    default:
        // Endpoint not found
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Endpoint not found'
        ]);
        break;
}
?>