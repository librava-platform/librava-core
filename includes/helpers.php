<?php
/**
 * Librava - Helper Functions
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

/**
 * Send JSON response
 */
function sendResponse($success, $message, $data = null, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

/**
 * Validate token and return admin data
 */
function validateToken() {
    $headers = getallheaders();
    $token = null;

    // Check Authorization header
    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            $token = $matches[1];
        }
    }

    if (!$token) {
        sendResponse(false, 'Authentication required', null, 401);
    }

    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, username FROM admins WHERE token = ? AND token_expires > NOW()");
        $stmt->execute([$token]);
        $admin = $stmt->fetch();

        if (!$admin) {
            sendResponse(false, 'Invalid or expired token', null, 401);
        }

        return $admin;
    } catch (PDOException $e) {
        sendResponse(false, 'Authentication error', null, 500);
    }
}

/**
 * Upload file
 */
function uploadFile($file, $type = 'cover') {
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error: ' . $file['error']);
    }

    $allowedTypes = ($type === 'cover') ? ALLOWED_COVER_TYPES : ALLOWED_PDF_TYPES;
    $maxSize = ($type === 'cover') ? MAX_COVER_SIZE : MAX_PDF_SIZE;
    $uploadDir = ($type === 'cover') ? COVERS_DIR : PDFS_DIR;

    // Validate file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes)) {
        throw new Exception('Invalid file type. Allowed: ' . implode(', ', $allowedTypes));
    }

    // Validate file size
    if ($file['size'] > $maxSize) {
        throw new Exception('File too large. Maximum size: ' . ($maxSize / 1024 / 1024) . 'MB');
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;

    // Ensure directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        throw new Exception('Failed to save file');
    }

    // Return relative path
    return 'uploads/' . ($type === 'cover' ? 'covers' : 'pdfs') . '/' . $filename;
}

/**
 * Delete file
 */
function deleteFile($path) {
    if ($path && file_exists($path)) {
        unlink($path);
    }
}

/**
 * Get full URL for a path
 */
function getFullUrl($path) {
    if (!$path) return null;
    return BASE_URL . $path;
}

/**
 * Sanitize input
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Get request body (for POST/PUT)
 */
function getRequestBody() {
    return json_decode(file_get_contents('php://input'), true);
}

