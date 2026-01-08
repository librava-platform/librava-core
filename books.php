<?php
/**
 * Librava - Books List Page (Bootstrap 5 UI)
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

// Get language
$lang = $_GET['lang'] ?? 'en';
if (!in_array($lang, ['en', 'fa'])) {
    $lang = 'en';
}

// Page title
$pageTitle = $lang === 'fa' ? 'لیبراوا - فهرست کتاب‌ها' : 'Librava - Books Collection';

// Translations
$t = [
    'en' => [
        'page_title' => 'Books Collection',
        'subtitle' => 'Browse our digital library',
        'search_placeholder' => 'Search books...',
        'all_categories' => 'All Categories',
        'no_books' => 'No books available yet.',
        'add_first' => 'Be the first to add a book!',
        'author' => 'Author',
        'category' => 'Category',
        'read_more' => 'Read More',
        'view_details' => 'View Details',
        'total_books' => 'Total Books',
    ],
    'fa' => [
        'page_title' => 'مجموعه کتاب‌ها',
        'subtitle' => 'کتابخانه دیجیتال را مرور کنید',
        'search_placeholder' => 'جستجوی کتاب...',
        'all_categories' => 'همه دسته‌ها',
        'no_books' => 'هنوز کتابی موجود نیست.',
        'add_first' => 'اولین کتاب را اضافه کنید!',
        'author' => 'نویسنده',
        'category' => 'دسته‌بندی',
        'read_more' => 'بیشتر بخوانید',
        'view_details' => 'مشاهده جزئیات',
        'total_books' => 'تعداد کتاب‌ها',
    ]
];

$translation = $t[$lang];

// Get search and filter parameters
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

// Fetch books
try {
    $db = getDB();
    
    // Build query
    $sql = "SELECT * FROM books WHERE 1=1";
    $params = [];
    
    if ($search) {
        $sql .= " AND (title LIKE ? OR author LIKE ? OR description LIKE ?)";
        $searchParam = "%{$search}%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $params[] = $searchParam;
    }
    
    if ($category) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $books = $stmt->fetchAll();
    
    // Get all categories
    $stmt = $db->query("SELECT DISTINCT category FROM books WHERE category IS NOT NULL AND category != '' ORDER BY category");
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
} catch (PDOException $e) {
    $books = [];
    $categories = [];
}

// Include header
include __DIR__ . '/views/layout/header.php';
?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <h1 class="display-4 mb-3" style="color: var(--mint-leaf-2);">
                    <i class="bi bi-collection"></i> <?php echo $translation['page_title']; ?>
                </h1>
                <p class="lead text-muted"><?php echo $translation['subtitle']; ?></p>
                <div class="mt-3">
                    <span class="badge bg-primary fs-6">
                        <i class="bi bi-book"></i> <?php echo $translation['total_books']; ?>: <?php echo count($books); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="" class="row g-3">
                    <input type="hidden" name="lang" value="<?php echo $lang; ?>">
                    
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="<?php echo $translation['search_placeholder']; ?>"
                                   value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <select name="category" class="form-select">
                            <option value=""><?php echo $translation['all_categories']; ?></option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>" 
                                        <?php echo $category === $cat ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel"></i> 
                            <?php echo $lang === 'fa' ? 'فیلتر' : 'Filter'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Books Grid -->
<?php if (empty($books)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                    <h3 class="text-muted"><?php echo $translation['no_books']; ?></h3>
                    <p class="text-muted"><?php echo $translation['add_first']; ?></p>
                    <a href="<?php echo BASE_URL; ?>crud_test.php" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> 
                        <?php echo $lang === 'fa' ? 'افزودن کتاب' : 'Add Book'; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
        <?php foreach ($books as $book): ?>
            <div class="col">
                <div class="card h-100">
                    <!-- Book Cover -->
                    <?php if ($book['cover_path']): ?>
                        <img src="<?php echo getFullUrl($book['cover_path']); ?>" 
                             class="card-img-top book-cover" 
                             alt="<?php echo htmlspecialchars($book['title']); ?>">
                    <?php else: ?>
                        <div class="book-cover d-flex align-items-center justify-content-center">
                            <i class="bi bi-book display-1 text-muted"></i>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Book Info -->
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title" style="color: var(--mint-leaf-2);">
                            <?php echo htmlspecialchars($book['title']); ?>
                        </h5>
                        
                        <p class="card-text text-muted mb-2">
                            <i class="bi bi-person"></i> 
                            <small><?php echo $translation['author']; ?>:</small>
                            <strong><?php echo htmlspecialchars($book['author']); ?></strong>
                        </p>
                        
                        <?php if ($book['category']): ?>
                            <div class="mb-2">
                                <span class="badge-category">
                                    <i class="bi bi-tag"></i> <?php echo htmlspecialchars($book['category']); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($book['description']): ?>
                            <p class="card-text text-muted flex-grow-1">
                                <?php 
                                $desc = htmlspecialchars($book['description']);
                                echo mb_strlen($desc) > 100 ? mb_substr($desc, 0, 100) . '...' : $desc;
                                ?>
                            </p>
                        <?php endif; ?>
                        
                        <!-- Action Buttons -->
                        <div class="mt-auto pt-3">
                            <div class="d-grid gap-2">
                                <?php if ($book['pdf_path']): ?>
                                    <a href="<?php echo getFullUrl($book['pdf_path']); ?>" 
                                       target="_blank" 
                                       class="btn btn-primary btn-sm">
                                        <i class="bi bi-file-pdf"></i> 
                                        <?php echo $lang === 'fa' ? 'دانلود PDF' : 'Download PDF'; ?>
                                    </a>
                                <?php endif; ?>
                                <button class="btn btn-outline-secondary btn-sm" 
                                        onclick="viewBookDetails(<?php echo $book['id']; ?>)">
                                    <i class="bi bi-eye"></i> <?php echo $translation['view_details']; ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Footer -->
                    <div class="card-footer text-muted text-center">
                        <small>
                            <i class="bi bi-calendar"></i> 
                            <?php echo date('M d, Y', strtotime($book['created_at'])); ?>
                        </small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Book Details Modal -->
<div class="modal fade" id="bookDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--mint-leaf-2); color: white;">
                <h5 class="modal-title">
                    <i class="bi bi-book"></i> <?php echo $lang === 'fa' ? 'جزئیات کتاب' : 'Book Details'; ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bookDetailsContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Custom JavaScript
$customJS = <<<'JS'
const BASE_URL = '<?php echo BASE_URL; ?>';
const LANG = '<?php echo $lang; ?>';

async function viewBookDetails(id) {
    const modal = new bootstrap.Modal(document.getElementById('bookDetailsModal'));
    const content = document.getElementById('bookDetailsContent');
    
    modal.show();
    content.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
    
    try {
        const response = await fetch(`${BASE_URL}api/index.php?action=books&id=${id}`);
        const data = await response.json();
        
        if (data.success) {
            const book = data.data;
            content.innerHTML = `
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        ${book.cover_url 
                            ? `<img src="${book.cover_url}" class="img-fluid rounded shadow" alt="${book.title}">`
                            : '<div class="bg-light rounded p-5"><i class="bi bi-book display-1 text-muted"></i></div>'
                        }
                    </div>
                    <div class="col-md-8">
                        <h3 style="color: var(--mint-leaf-2);">${book.title}</h3>
                        <p class="text-muted mb-3">
                            <i class="bi bi-person"></i> <strong>${LANG === 'fa' ? 'نویسنده' : 'Author'}:</strong> ${book.author}
                        </p>
                        ${book.category ? `<span class="badge-category mb-3"><i class="bi bi-tag"></i> ${book.category}</span>` : ''}
                        <hr>
                        <h5>${LANG === 'fa' ? 'توضیحات' : 'Description'}:</h5>
                        <p>${book.description || (LANG === 'fa' ? 'توضیحی موجود نیست.' : 'No description available.')}</p>
                        <hr>
                        <div class="d-grid gap-2">
                            ${book.pdf_url 
                                ? `<a href="${book.pdf_url}" target="_blank" class="btn btn-primary">
                                    <i class="bi bi-file-pdf"></i> ${LANG === 'fa' ? 'دانلود PDF' : 'Download PDF'}
                                   </a>`
                                : ''
                            }
                        </div>
                        <small class="text-muted mt-3 d-block">
                            <i class="bi bi-calendar"></i> ${new Date(book.created_at).toLocaleDateString()}
                        </small>
                    </div>
                </div>
            `;
        } else {
            content.innerHTML = '<div class="alert alert-danger">Error loading book details.</div>';
        }
    } catch (error) {
        content.innerHTML = '<div class="alert alert-danger">Failed to load book details.</div>';
    }
}
JS;

// Include footer
include __DIR__ . '/views/layout/footer.php';
?>

