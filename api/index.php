<?php
/**
 * Librava - API Router
 * 
 * Routes:
 * POST   /api/index.php?action=login
 * POST   /api/index.php?action=logout
 * GET    /api/index.php?action=verify_token
 * GET    /api/index.php?action=books
 * GET    /api/index.php?action=books&id={id}
 * POST   /api/index.php?action=books (create)
 * POST   /api/index.php?action=books&id={id} (update with _method=PUT)
 * POST   /api/index.php?action=books&id={id}&_method=DELETE (delete)
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/books.php';

// Get action from query parameter
$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

if (!$action) {
    sendResponse(false, 'No action specified', null, 400);
}

// Route requests
switch ($action) {
    case 'login':
        handleLogin();
        break;
    
    case 'logout':
        handleLogout();
        break;
    
    case 'verify_token':
        handleVerifyToken();
        break;
    
    case 'books':
        if ($id) {
            // Single book operations
            if ($method === 'GET') {
                handleGetBook($id);
            } elseif ($method === 'POST' || $method === 'PUT') {
                handleUpdateBook($id);
            } elseif ($method === 'DELETE') {
                handleDeleteBook($id);
            } else {
                sendResponse(false, 'Method not allowed', null, 405);
            }
        } else {
            // Multiple books operations
            if ($method === 'GET') {
                handleGetBooks();
            } elseif ($method === 'POST') {
                handleCreateBook();
            } else {
                sendResponse(false, 'Method not allowed', null, 405);
            }
        }
        break;
    
    default:
        sendResponse(false, 'Invalid action', null, 404);
}

