# PHP REST API Template

A simple PHP REST API template with different URL endpoints for a social networking application.

## API Structure

- `index.php`: Main entry point that routes requests to appropriate endpoint handlers based on URL
- `connect.php`: Database connection configuration
- `endpoints/auth_endpoint.php`: Handles authentication (register, login, logout)
- `endpoints/users_endpoint.php`: Handles user management operations
- `endpoints/posts_endpoint.php`: Handles post management operations
- `endpoints/comments_endpoint.php`: Handles comment management operations

## URL Structure

The API uses a URL parameter to route requests to the appropriate endpoint:

```
index.php?url=/endpoint
```

Available endpoints:
- `/auth`: Authentication operations
- `/users`: User management
- `/posts`: Post management
- `/comments`: Comment management

## Authentication Endpoints

### Register a new user
```
POST index.php?url=/auth&action=register
```

Request Body:
```json
{
  "username": "newuser",
  "email": "newuser@example.com",
  "password": "securepassword"
}
```

Response:
```json
{
  "message": "Registration successful.",
  "id": "3",
  "username": "newuser",
  "email": "newuser@example.com",
  "created_at": "2023-01-03 12:00:00"
}
```

### Login
```
POST index.php?url=/auth&action=login
```

Request Body:
```json
{
  "email": "user@example.com",
  "password": "securepassword"
}
```

Response:
```json
{
  "message": "Login successful.",
  "token": "a1b2c3d4e5f6g7h8i9j0...",
  "user": {
    "id": "1",
    "username": "user1",
    "email": "user@example.com"
  }
}
```

### Logout
```
POST index.php?url=/auth&action=logout
```

Headers:
```
Authorization: Bearer a1b2c3d4e5f6g7h8i9j0...
```

Response:
```json
{
  "message": "Logout successful."
}
```

## User Endpoints

### Get All Users
```
GET index.php?url=/users
```

Response:
```json
{
  "records": [
    {
      "id": "1",
      "username": "user1",
      "email": "user1@example.com",
      "created_at": "2023-01-01 12:00:00"
    },
    {
      "id": "2",
      "username": "user2",
      "email": "user2@example.com",
      "created_at": "2023-01-02 12:00:00"
    }
  ]
}
```

### Get User by ID
```
GET index.php?url=/users&id=1
```

Response:
```json
{
  "id": "1",
  "username": "user1",
  "email": "user1@example.com",
  "created_at": "2023-01-01 12:00:00"
}
```

### Create User
```
POST index.php?url=/users
```

Request Body:
```json
{
  "username": "newuser",
  "email": "newuser@example.com",
  "password": "securepassword"
}
```

Response:
```json
{
  "message": "User created successfully.",
  "id": "3",
  "username": "newuser",
  "email": "newuser@example.com",
  "created_at": "2023-01-03 12:00:00"
}
```

### Update User
```
PUT index.php?url=/users
```

Request Body:
```json
{
  "id": "1",
  "username": "updateduser",
  "email": "updated@example.com"
}
```

Response:
```json
{
  "message": "User updated successfully.",
  "user": {
    "id": "1",
    "username": "updateduser",
    "email": "updated@example.com",
    "created_at": "2023-01-01 12:00:00",
    "updated_at": "2023-01-04 12:00:00"
  }
}
```

### Delete User
```
DELETE index.php?url=/users&id=1
```

Response:
```json
{
  "message": "User deleted successfully."
}
```

## Post Endpoints

### Get All Posts (with pagination)
```
GET index.php?url=/posts&page=1&limit=10
```

Response:
```json
{
  "records": [
    {
      "id": "1",
      "user_id": "1",
      "username": "user1",
      "title": "First Post",
      "content": "This is the content of the first post.",
      "created_at": "2023-01-03 12:00:00"
    },
    {
      "id": "2",
      "user_id": "2",
      "username": "user2",
      "title": "Second Post",
      "content": "This is the content of the second post.",
      "created_at": "2023-01-04 12:00:00"
    }
  ],
  "pagination": {
    "total_records": 25,
    "total_pages": 3,
    "current_page": 1,
    "limit": 10
  }
}
```

### Get Posts by User
```
GET index.php?url=/posts&user_id=1
```

### Get Post by ID
```
GET index.php?url=/posts&id=1
```

Response:
```json
{
  "id": "1",
  "user_id": "1",
  "username": "user1",
  "title": "First Post",
  "content": "This is the content of the first post.",
  "created_at": "2023-01-03 12:00:00"
}
```

### Create Post
```
POST index.php?url=/posts
```

Request Body:
```json
{
  "user_id": "1",
  "title": "My New Post",
  "content": "This is the content of my new post."
}
```

Response:
```json
{
  "message": "Post created successfully.",
  "id": "3",
  "user_id": "1",
  "username": "user1",
  "title": "My New Post",
  "content": "This is the content of my new post.",
  "created_at": "2023-01-05 12:00:00"
}
```

### Update Post
```
PUT index.php?url=/posts
```

Request Body:
```json
{
  "id": "1",
  "title": "Updated Post Title",
  "content": "This is the updated content of my post."
}
```

Response:
```json
{
  "message": "Post updated successfully.",
  "post": {
    "id": "1",
    "user_id": "1",
    "username": "user1",
    "title": "Updated Post Title",
    "content": "This is the updated content of my post.",
    "created_at": "2023-01-03 12:00:00",
    "updated_at": "2023-01-05 12:00:00"
  }
}
```

### Delete Post
```
DELETE index.php?url=/posts&id=1
```

Response:
```json
{
  "message": "Post deleted successfully."
}
```

## Comment Endpoints

### Get Comments for a Post (with pagination)
```
GET index.php?url=/comments&post_id=1&page=1&limit=10
```

Response:
```json
{
  "records": [
    {
      "id": "1",
      "post_id": "1",
      "user_id": "2",
      "username": "user2",
      "content": "Great post!",
      "created_at": "2023-01-03 13:00:00"
    },
    {
      "id": "2",
      "post_id": "1",
      "user_id": "3",
      "username": "user3",
      "content": "I agree with this.",
      "created_at": "2023-01-03 14:00:00"
    }
  ],
  "pagination": {
    "total_records": 15,
    "total_pages": 2,
    "current_page": 1,
    "limit": 10
  }
}
```

### Get Comment by ID
```
GET index.php?url=/comments&id=1
```

### Create Comment
```
POST index.php?url=/comments
```

Request Body:
```json
{
  "user_id": "2",
  "post_id": "1",
  "content": "This is my comment on the post."
}
```

Response:
```json
{
  "message": "Comment created successfully.",
  "id": "3",
  "user_id": "2",
  "username": "user2",
  "post_id": "1",
  "content": "This is my comment on the post.",
  "created_at": "2023-01-05 12:00:00"
}
```

### Update Comment
```
PUT index.php?url=/comments
```

Request Body:
```json
{
  "id": "1",
  "content": "Updated comment content."
}
```

Response:
```json
{
  "message": "Comment updated successfully.",
  "comment": {
    "id": "1",
    "post_id": "1",
    "user_id": "2",
    "username": "user2",
    "content": "Updated comment content.",
    "created_at": "2023-01-03 13:00:00",
    "updated_at": "2023-01-05 12:00:00"
  }
}
```

### Delete Comment
```
DELETE index.php?url=/comments&id=1
```

Response:
```json
{
  "message": "Comment deleted successfully."
}
```

## Setup Instructions

1. Ensure you have a web server with PHP and MySQL installed
2. Import the database schema (see below)
3. Update the database connection details in `connect.php`
4. Place all files in your web server's document root or a subdirectory
5. Access the API endpoints using the appropriate HTTP methods and URL parameters

## Database Schema

This API template assumes the following database tables:

### Users Table
```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME
);
```

### User Tokens Table (for authentication)
```sql
CREATE TABLE user_tokens (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  token VARCHAR(255) NOT NULL UNIQUE,
  expires_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Posts Table
```sql
CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(100) NOT NULL,
  content TEXT NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Comments Table
```sql
CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT NOT NULL,
  user_id INT NOT NULL,
  content TEXT NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME,
  FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## Error Handling

The API returns appropriate HTTP status codes:

- 200: OK (Success)
- 201: Created (Resource created successfully)
- 400: Bad Request (Missing or invalid parameters)
- 401: Unauthorized (Invalid credentials or token)
- 404: Not Found (Resource not found)
- 405: Method Not Allowed (Incorrect HTTP method)
- 409: Conflict (Resource already exists, e.g., email already in use)
- 500: Internal Server Error (Server-side error)

## Security Considerations

This template includes basic security measures:
- Password hashing
- Input sanitization using mysqli_real_escape_string()
- Proper HTTP status codes
- CORS headers
- Token-based authentication

For production use, consider implementing:
- JWT for more robust authentication
- Rate limiting
- Input validation
- Prepared statements instead of string concatenation
- HTTPS enforcement

## Setup Instructions

1. Ensure you have a web server with PHP and MySQL installed
2. Import the database schema (not included in this template)
3. Update the database connection details in `connect.php`
4. Place all files in your web server's document root or a subdirectory
5. Access the API endpoints using the appropriate HTTP methods

## Database Schema

This API template assumes the following database tables:

### Users Table
```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME
);
```

### Posts Table
```sql
CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(100) NOT NULL,
  content TEXT NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
```

## Error Handling

The API returns appropriate HTTP status codes:

- 200: OK (Success)
- 201: Created (Resource created successfully)
- 400: Bad Request (Missing or invalid parameters)
- 404: Not Found (Resource not found)
- 405: Method Not Allowed (Incorrect HTTP method)
- 500: Internal Server Error (Server-side error)

## Security Considerations

This template includes basic security measures:
- Password hashing
- Input sanitization using mysqli_real_escape_string()
- Proper HTTP status codes
- CORS headers

For production use, consider implementing:
- Authentication (JWT, OAuth)
- Rate limiting
- Input validation
- Prepared statements instead of string concatenation