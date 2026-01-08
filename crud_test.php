<?php
/**
 * Librava - Complete CRUD Testing Page
 * Test all Create, Read, Update, Delete operations
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

// Get all books for display
try {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM books ORDER BY created_at DESC");
    $books = $stmt->fetchAll();
} catch (PDOException $e) {
    $books = [];
    $error = $e->getMessage();
}

// Get token from localStorage will be handled by JavaScript
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librava - CRUD Test Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --baltic-blue: #05668d;
            --teal: #028090;
            --verdigris: #00a896;
            --mint-leaf: #02c39a;
            --cream: #f0f3bd;
            --danger: #e63946;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--baltic-blue) 0%, var(--teal) 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        header {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: var(--baltic-blue);
            margin-bottom: 5px;
        }

        .subtitle {
            color: var(--teal);
            font-size: 0.9em;
        }

        .auth-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .auth-status {
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .auth-status.logged-out {
            background: #ffcccc;
            color: #c00;
        }

        .auth-status.logged-in {
            background: #ccffcc;
            color: #0a0;
        }

        .login-form {
            display: flex;
            gap: 10px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 150px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: var(--baltic-blue);
            font-weight: bold;
            font-size: 0.9em;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            border: 2px solid var(--verdigris);
            border-radius: 8px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        button {
            background: var(--mint-leaf);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        button:hover {
            background: var(--teal);
        }

        button.danger {
            background: var(--danger);
        }

        button.danger:hover {
            background: #d62828;
        }

        button.secondary {
            background: #666;
        }

        button.secondary:hover {
            background: #444;
        }

        .section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: var(--baltic-blue);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 3px solid var(--mint-leaf);
        }

        .books-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .books-table th {
            background: var(--baltic-blue);
            color: white;
            padding: 12px;
            text-align: left;
        }

        .books-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        .books-table tr:hover {
            background: var(--cream);
        }

        .book-cover-thumb {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .action-buttons button {
            padding: 6px 12px;
            font-size: 12px;
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: none;
        }

        .message.show {
            display: block;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .crud-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 968px) {
            .crud-grid {
                grid-template-columns: 1fr;
            }
        }

        .hidden {
            display: none;
        }

        .token-display {
            background: #f0f3bd;
            padding: 8px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 11px;
            word-break: break-all;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🔧 Librava - Complete CRUD Test Panel</h1>
            <p class="subtitle">Test Create, Read, Update, Delete operations | Developer: Mohammad Taha Abdinasab</p>
        </header>

        <!-- Authentication Section -->
        <div class="auth-section">
            <div id="auth-status" class="auth-status logged-out">
                ❌ Not Logged In - Login required for Create/Update/Delete
            </div>

            <div id="login-section">
                <div class="login-form">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" id="username" value="admin">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" id="password" value="admin123">
                    </div>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button onclick="login()">Login</button>
                    </div>
                </div>
            </div>

            <div id="logout-section" class="hidden">
                <button onclick="logout()" class="danger">Logout</button>
                <div id="token-display" class="token-display"></div>
            </div>

            <div id="auth-message" class="message"></div>
        </div>

        <!-- CREATE / UPDATE Section -->
        <div class="crud-grid">
            <div class="section">
                <h2 id="form-title">➕ Create New Book</h2>
                <div id="form-message" class="message"></div>

                <form id="book-form" onsubmit="event.preventDefault(); saveBook();">
                    <input type="hidden" id="book-id" value="">

                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" id="title" required>
                    </div>

                    <div class="form-group">
                        <label>Author *</label>
                        <input type="text" id="author" required>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea id="description"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" id="category">
                    </div>

                    <div class="form-group">
                        <label>Cover Image</label>
                        <input type="file" id="cover" accept="image/*">
                        <small id="current-cover"></small>
                    </div>

                    <div class="form-group">
                        <label>PDF File</label>
                        <input type="file" id="pdf" accept="application/pdf">
                        <small id="current-pdf"></small>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 15px;">
                        <button type="submit" id="save-button">Create Book</button>
                        <button type="button" onclick="resetForm()" class="secondary">Cancel / Reset</button>
                    </div>
                </form>
            </div>

            <!-- READ Section -->
            <div class="section">
                <h2>📖 All Books (<?php echo count($books); ?>)</h2>
                <button onclick="refreshBooks()">🔄 Refresh List</button>
                <div id="books-container">
                    <?php if (empty($books)): ?>
                        <p style="padding: 20px; text-align: center; color: #999;">No books yet. Create your first book!</p>
                    <?php else: ?>
                        <table class="books-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cover</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Category</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="books-tbody">
                                <?php foreach ($books as $book): ?>
                                <tr>
                                    <td><?php echo $book['id']; ?></td>
                                    <td>
                                        <?php if ($book['cover_path']): ?>
                                            <img src="<?php echo getFullUrl($book['cover_path']); ?>" 
                                                 class="book-cover-thumb" 
                                                 alt="<?php echo htmlspecialchars($book['title']); ?>">
                                        <?php else: ?>
                                            <div class="book-cover-thumb" style="background: #ddd;"></div>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($book['title']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                                    <td><?php echo htmlspecialchars($book['category'] ?? '-'); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button onclick="viewBook(<?php echo $book['id']; ?>)">View</button>
                                            <button onclick="editBook(<?php echo $book['id']; ?>)" class="secondary">Edit</button>
                                            <button onclick="deleteBook(<?php echo $book['id']; ?>)" class="danger">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
        let authToken = localStorage.getItem('librava_token') || '';

        // Update auth status on load
        updateAuthStatus();

        function updateAuthStatus() {
            const status = document.getElementById('auth-status');
            const loginSection = document.getElementById('login-section');
            const logoutSection = document.getElementById('logout-section');
            const tokenDisplay = document.getElementById('token-display');

            if (authToken) {
                status.className = 'auth-status logged-in';
                status.textContent = '✅ Logged In - You can Create/Update/Delete books';
                loginSection.classList.add('hidden');
                logoutSection.classList.remove('hidden');
                tokenDisplay.textContent = 'Token: ' + authToken;
            } else {
                status.className = 'auth-status logged-out';
                status.textContent = '❌ Not Logged In - Login required for Create/Update/Delete';
                loginSection.classList.remove('hidden');
                logoutSection.classList.add('hidden');
            }
        }

        function showMessage(elementId, message, isError = false) {
            const el = document.getElementById(elementId);
            el.textContent = message;
            el.className = 'message show ' + (isError ? 'error' : 'success');
            setTimeout(() => el.classList.remove('show'), 5000);
        }

        async function login() {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch(BASE_URL + 'api/index.php?action=login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username, password })
                });

                const data = await response.json();

                if (data.success && data.data.token) {
                    authToken = data.data.token;
                    localStorage.setItem('librava_token', authToken);
                    updateAuthStatus();
                    showMessage('auth-message', '✓ Login successful!');
                } else {
                    showMessage('auth-message', '✗ ' + data.message, true);
                }
            } catch (error) {
                showMessage('auth-message', '✗ Login error: ' + error.message, true);
            }
        }

        function logout() {
            authToken = '';
            localStorage.removeItem('librava_token');
            updateAuthStatus();
            showMessage('auth-message', '✓ Logged out successfully');
        }

        async function saveBook() {
            if (!authToken) {
                alert('Please login first!');
                return;
            }

            const bookId = document.getElementById('book-id').value;
            const formData = new FormData();

            formData.append('title', document.getElementById('title').value);
            formData.append('author', document.getElementById('author').value);
            formData.append('description', document.getElementById('description').value);
            formData.append('category', document.getElementById('category').value);

            const coverFile = document.getElementById('cover').files[0];
            if (coverFile) formData.append('cover', coverFile);

            const pdfFile = document.getElementById('pdf').files[0];
            if (pdfFile) formData.append('pdf', pdfFile);

            try {
                const url = bookId 
                    ? BASE_URL + 'api/index.php?action=books&id=' + bookId
                    : BASE_URL + 'api/index.php?action=books';

                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Authorization': 'Bearer ' + authToken },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showMessage('form-message', '✓ ' + data.message);
                    resetForm();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showMessage('form-message', '✗ ' + data.message, true);
                }
            } catch (error) {
                showMessage('form-message', '✗ Error: ' + error.message, true);
            }
        }

        async function viewBook(id) {
            try {
                const response = await fetch(BASE_URL + 'api/index.php?action=books&id=' + id);
                const data = await response.json();

                if (data.success) {
                    const book = data.data;
                    alert(
                        'BOOK DETAILS\n' +
                        '=================\n' +
                        'ID: ' + book.id + '\n' +
                        'Title: ' + book.title + '\n' +
                        'Author: ' + book.author + '\n' +
                        'Category: ' + (book.category || '-') + '\n' +
                        'Description: ' + (book.description || '-') + '\n' +
                        'Cover: ' + (book.cover_url || 'None') + '\n' +
                        'PDF: ' + (book.pdf_url || 'None') + '\n' +
                        'Created: ' + book.created_at
                    );
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        async function editBook(id) {
            if (!authToken) {
                alert('Please login first!');
                return;
            }

            try {
                const response = await fetch(BASE_URL + 'api/index.php?action=books&id=' + id);
                const data = await response.json();

                if (data.success) {
                    const book = data.data;
                    
                    document.getElementById('book-id').value = book.id;
                    document.getElementById('title').value = book.title;
                    document.getElementById('author').value = book.author;
                    document.getElementById('description').value = book.description || '';
                    document.getElementById('category').value = book.category || '';

                    if (book.cover_path) {
                        document.getElementById('current-cover').textContent = '(Current: ' + book.cover_path + ')';
                    }
                    if (book.pdf_path) {
                        document.getElementById('current-pdf').textContent = '(Current: ' + book.pdf_path + ')';
                    }

                    document.getElementById('form-title').textContent = '✏️ Update Book #' + book.id;
                    document.getElementById('save-button').textContent = 'Update Book';

                    // Scroll to form
                    document.getElementById('book-form').scrollIntoView({ behavior: 'smooth' });
                } else {
                    alert('Error loading book: ' + data.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        async function deleteBook(id) {
            if (!authToken) {
                alert('Please login first!');
                return;
            }

            if (!confirm('Are you sure you want to DELETE this book?\n\nThis action cannot be undone!')) {
                return;
            }

            try {
                const response = await fetch(BASE_URL + 'api/index.php?action=books&id=' + id + '&_method=DELETE', {
                    method: 'POST',
                    headers: { 'Authorization': 'Bearer ' + authToken }
                });

                const data = await response.json();

                if (data.success) {
                    alert('✓ ' + data.message);
                    location.reload();
                } else {
                    alert('✗ ' + data.message);
                }
            } catch (error) {
                alert('✗ Error: ' + error.message);
            }
        }

        function resetForm() {
            document.getElementById('book-id').value = '';
            document.getElementById('book-form').reset();
            document.getElementById('current-cover').textContent = '';
            document.getElementById('current-pdf').textContent = '';
            document.getElementById('form-title').textContent = '➕ Create New Book';
            document.getElementById('save-button').textContent = 'Create Book';
        }

        function refreshBooks() {
            location.reload();
        }
    </script>
</body>
</html>

