<?php
/**
 * Librava - Authentication Handlers
 */

require_once __DIR__ . '/../includes/helpers.php';

/**
 * Handle login
 */
function handleLogin() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendResponse(false, 'Method not allowed', null, 405);
    }

    // Get POST data (support both form-data and JSON)
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;

    // If not in POST, try JSON body
    if (!$username || !$password) {
        $body = getRequestBody();
        $username = $body['username'] ?? null;
        $password = $body['password'] ?? null;
    }

    // Validate input
    if (!$username || !$password) {
        sendResponse(false, 'Username and password are required', null, 400);
    }

    try {
        $db = getDB();
        
        // Get admin by username
        $stmt = $db->prepare("SELECT id, username, password_hash FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if (!$admin) {
            sendResponse(false, 'Invalid credentials', null, 401);
        }

        // Verify password
        if (!password_verify($password, $admin['password_hash'])) {
            sendResponse(false, 'Invalid credentials', null, 401);
        }

        // Generate token
        $token = bin2hex(random_bytes(32));
        $tokenExpires = date('Y-m-d H:i:s', strtotime('+30 days'));

        // Update token
        $stmt = $db->prepare("UPDATE admins SET token = ?, token_expires = ? WHERE id = ?");
        $stmt->execute([$token, $tokenExpires, $admin['id']]);

        sendResponse(true, 'Login successful', [
            'token' => $token,
            'username' => $admin['username'],
            'expires_at' => $tokenExpires
        ]);
    } catch (PDOException $e) {
        sendResponse(false, 'Login error: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Handle logout
 */
function handleLogout() {
    $admin = validateToken();

    try {
        $db = getDB();
        $stmt = $db->prepare("UPDATE admins SET token = NULL, token_expires = NULL WHERE id = ?");
        $stmt->execute([$admin['id']]);

        sendResponse(true, 'Logout successful');
    } catch (PDOException $e) {
        sendResponse(false, 'Logout error', null, 500);
    }
}

/**
 * Verify token (for testing)
 */
function handleVerifyToken() {
    $admin = validateToken();
    sendResponse(true, 'Token is valid', [
        'username' => $admin['username']
    ]);
}

