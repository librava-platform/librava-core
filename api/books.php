<?php
/**
 * Librava - Books CRUD Handlers
 */

require_once __DIR__ . '/../includes/helpers.php';

/**
 * Get all books
 */
function handleGetBooks() {
    try {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM books ORDER BY created_at DESC");
        $books = $stmt->fetchAll();

        // Add full URLs
        foreach ($books as &$book) {
            $book['cover_url'] = getFullUrl($book['cover_path']);
            $book['pdf_url'] = getFullUrl($book['pdf_path']);
        }

        sendResponse(true, 'Books retrieved successfully', $books);
    } catch (PDOException $e) {
        sendResponse(false, 'Error retrieving books: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Get single book
 */
function handleGetBook($id) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        $book = $stmt->fetch();

        if (!$book) {
            sendResponse(false, 'Book not found', null, 404);
        }

        // Add full URLs
        $book['cover_url'] = getFullUrl($book['cover_path']);
        $book['pdf_url'] = getFullUrl($book['pdf_path']);

        sendResponse(true, 'Book retrieved successfully', $book);
    } catch (PDOException $e) {
        sendResponse(false, 'Error retrieving book: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Create book (requires auth)
 */
function handleCreateBook() {
    validateToken(); // Will exit if invalid

    // Get form data
    $title = sanitize($_POST['title'] ?? '');
    $author = sanitize($_POST['author'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $category = sanitize($_POST['category'] ?? '');

    // Validate required fields
    if (!$title || !$author) {
        sendResponse(false, 'Title and author are required', null, 400);
    }

    try {
        // Upload files
        $coverPath = null;
        $pdfPath = null;

        if (isset($_FILES['cover'])) {
            $coverPath = uploadFile($_FILES['cover'], 'cover');
        }

        if (isset($_FILES['pdf'])) {
            $pdfPath = uploadFile($_FILES['pdf'], 'pdf');
        }

        // Insert into database
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO books (title, author, description, category, cover_path, pdf_path, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$title, $author, $description, $category, $coverPath, $pdfPath]);

        $bookId = $db->lastInsertId();

        // Get created book
        $stmt = $db->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$bookId]);
        $book = $stmt->fetch();

        $book['cover_url'] = getFullUrl($book['cover_path']);
        $book['pdf_url'] = getFullUrl($book['pdf_path']);

        sendResponse(true, 'Book created successfully', $book, 201);
    } catch (Exception $e) {
        // Clean up uploaded files on error
        if (isset($coverPath)) deleteFile($coverPath);
        if (isset($pdfPath)) deleteFile($pdfPath);
        
        sendResponse(false, 'Error creating book: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Update book (requires auth)
 */
function handleUpdateBook($id) {
    validateToken(); // Will exit if invalid

    try {
        $db = getDB();
        
        // Check if book exists
        $stmt = $db->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        $book = $stmt->fetch();

        if (!$book) {
            sendResponse(false, 'Book not found', null, 404);
        }

        // Get form data
        $title = sanitize($_POST['title'] ?? $book['title']);
        $author = sanitize($_POST['author'] ?? $book['author']);
        $description = sanitize($_POST['description'] ?? $book['description']);
        $category = sanitize($_POST['category'] ?? $book['category']);

        $coverPath = $book['cover_path'];
        $pdfPath = $book['pdf_path'];

        // Upload new cover if provided
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] !== UPLOAD_ERR_NO_FILE) {
            $newCoverPath = uploadFile($_FILES['cover'], 'cover');
            if ($newCoverPath) {
                // Delete old cover
                if ($coverPath) {
                    deleteFile($coverPath);
                }
                $coverPath = $newCoverPath;
            }
        }

        // Upload new PDF if provided
        if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] !== UPLOAD_ERR_NO_FILE) {
            $newPdfPath = uploadFile($_FILES['pdf'], 'pdf');
            if ($newPdfPath) {
                // Delete old PDF
                if ($pdfPath) {
                    deleteFile($pdfPath);
                }
                $pdfPath = $newPdfPath;
            }
        }

        // Update database
        $stmt = $db->prepare("
            UPDATE books 
            SET title = ?, author = ?, description = ?, category = ?, cover_path = ?, pdf_path = ?
            WHERE id = ?
        ");
        $stmt->execute([$title, $author, $description, $category, $coverPath, $pdfPath, $id]);

        // Get updated book
        $stmt = $db->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        $updatedBook = $stmt->fetch();

        $updatedBook['cover_url'] = getFullUrl($updatedBook['cover_path']);
        $updatedBook['pdf_url'] = getFullUrl($updatedBook['pdf_path']);

        sendResponse(true, 'Book updated successfully', $updatedBook);
    } catch (Exception $e) {
        sendResponse(false, 'Error updating book: ' . $e->getMessage(), null, 500);
    }
}

/**
 * Delete book (requires auth)
 */
function handleDeleteBook($id) {
    validateToken(); // Will exit if invalid

    try {
        $db = getDB();
        
        // Check if book exists
        $stmt = $db->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        $book = $stmt->fetch();

        if (!$book) {
            sendResponse(false, 'Book not found', null, 404);
        }

        // Delete files
        if ($book['cover_path']) {
            deleteFile($book['cover_path']);
        }
        if ($book['pdf_path']) {
            deleteFile($book['pdf_path']);
        }

        // Delete from database
        $stmt = $db->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$id]);

        sendResponse(true, 'Book deleted successfully');
    } catch (PDOException $e) {
        sendResponse(false, 'Error deleting book: ' . $e->getMessage(), null, 500);
    }
}

