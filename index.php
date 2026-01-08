<?php
/**
 * Librava - Home Page (Bootstrap 5 UI)
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

// Get language
$lang = $_GET['lang'] ?? 'en';
if (!in_array($lang, ['en', 'fa'])) {
    $lang = 'en';
}

$pageTitle = $lang === 'fa' ? 'لیبراوا - کتابخانه دیجیتال' : 'Librava - Digital Library';
$dir = ($lang === 'fa') ? 'rtl' : 'ltr';

// Translations
$t = [
    'en' => [
        'home' => 'Home',
        'books' => 'Books',
        'admin' => 'Admin',
        'api' => 'API',
    ],
    'fa' => [
        'home' => 'خانه',
        'books' => 'کتاب‌ها',
        'admin' => 'مدیریت',
        'api' => 'API',
    ]
];

// Fetch latest books
try {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM books ORDER BY created_at DESC LIMIT 6");
    $latestBooks = $stmt->fetchAll();
    
    // Get stats
    $stmt = $db->query("SELECT COUNT(*) FROM books");
    $totalBooks = $stmt->fetchColumn();
} catch (PDOException $e) {
    $latestBooks = [];
    $totalBooks = 0;
}

// Include header
include __DIR__ . '/views/layout/header.php';
?>

<!-- Hero Section -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card bg-gradient text-white" style="background: linear-gradient(135deg, var(--baltic-blue), var(--teal));">
            <div class="card-body text-center py-5">
                <h1 class="display-3 fw-bold mb-3">
                    <i class="bi bi-book-fill"></i> 
                    <?php echo $lang === 'fa' ? 'لیبراوا' : 'Librava'; ?>
                </h1>
                <p class="lead mb-4">
                    <?php echo $lang === 'fa' 
                        ? 'کتابخانه دیجیتال هوشمند شما' 
                        : 'Your Smart Digital Library'; ?>
                </p>
                <div class="row justify-content-center">
                    <div class="col-md-3 mb-3">
                        <div class="bg-white text-dark rounded p-3">
                            <h2 class="display-4 fw-bold mb-0" style="color: var(--mint-leaf);"><?php echo $totalBooks; ?></h2>
                            <p class="mb-0"><?php echo $lang === 'fa' ? 'کتاب' : 'Books'; ?></p>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="books.php?lang=<?php echo $lang; ?>" class="btn btn-light btn-lg me-2">
                        <i class="bi bi-collection"></i> 
                        <?php echo $lang === 'fa' ? 'مشاهده همه کتاب‌ها' : 'Browse All Books'; ?>
                    </a>
                    <a href="crud_test.php" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-gear"></i> 
                        <?php echo $lang === 'fa' ? 'پنل مدیریت' : 'Admin Panel'; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="row mb-5">
    <div class="col-12 mb-4">
        <h2 class="text-center text-white mb-4">
            <i class="bi bi-stars"></i> 
            <?php echo $lang === 'fa' ? 'ویژگی‌ها' : 'Features'; ?>
        </h2>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100 text-center">
            <div class="card-body">
                <div class="display-4 mb-3" style="color: var(--mint-leaf);">
                    <i class="bi bi-cloud-upload"></i>
                </div>
                <h5 class="card-title" style="color: var(--baltic-blue);">
                    <?php echo $lang === 'fa' ? 'آپلود آسان' : 'Easy Upload'; ?>
                </h5>
                <p class="card-text text-muted">
                    <?php echo $lang === 'fa' 
                        ? 'کتاب‌ها و فایل‌های PDF را به راحتی آپلود کنید' 
                        : 'Upload books and PDF files with ease'; ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100 text-center">
            <div class="card-body">
                <div class="display-4 mb-3" style="color: var(--verdigris);">
                    <i class="bi bi-search"></i>
                </div>
                <h5 class="card-title" style="color: var(--baltic-blue);">
                    <?php echo $lang === 'fa' ? 'جستجوی هوشمند' : 'Smart Search'; ?>
                </h5>
                <p class="card-text text-muted">
                    <?php echo $lang === 'fa' 
                        ? 'کتاب مورد نظر خود را سریع پیدا کنید' 
                        : 'Find your books quickly and efficiently'; ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100 text-center">
            <div class="card-body">
                <div class="display-4 mb-3" style="color: var(--teal);">
                    <i class="bi bi-phone"></i>
                </div>
                <h5 class="card-title" style="color: var(--baltic-blue);">
                    <?php echo $lang === 'fa' ? 'اپلیکیشن اندروید' : 'Android App'; ?>
                </h5>
                <p class="card-text text-muted">
                    <?php echo $lang === 'fa' 
                        ? 'دسترسی از طریق اپلیکیشن موبایل' 
                        : 'Access via mobile application'; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Latest Books Section -->
<?php if (!empty($latestBooks)): ?>
<div class="row mb-5">
    <div class="col-12 mb-4">
        <h2 class="text-center text-white mb-4">
            <i class="bi bi-clock-history"></i> 
            <?php echo $lang === 'fa' ? 'آخرین کتاب‌ها' : 'Latest Books'; ?>
        </h2>
    </div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($latestBooks as $book): ?>
            <div class="col">
                <div class="card h-100">
                    <?php if ($book['cover_path']): ?>
                        <img src="<?php echo getFullUrl($book['cover_path']); ?>" 
                             class="card-img-top book-cover" 
                             alt="<?php echo htmlspecialchars($book['title']); ?>">
                    <?php else: ?>
                        <div class="book-cover d-flex align-items-center justify-content-center">
                            <i class="bi bi-book display-1 text-muted"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title" style="color: var(--baltic-blue);">
                            <?php echo htmlspecialchars($book['title']); ?>
                        </h5>
                        <p class="card-text text-muted">
                            <i class="bi bi-person"></i> <?php echo htmlspecialchars($book['author']); ?>
                        </p>
                        <?php if ($book['category']): ?>
                            <span class="badge-category">
                                <i class="bi bi-tag"></i> <?php echo htmlspecialchars($book['category']); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-transparent">
                        <small class="text-muted">
                            <i class="bi bi-calendar"></i> 
                            <?php echo date('M d, Y', strtotime($book['created_at'])); ?>
                        </small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="col-12 text-center mt-4">
        <a href="books.php?lang=<?php echo $lang; ?>" class="btn btn-primary btn-lg">
            <i class="bi bi-arrow-right-circle"></i> 
            <?php echo $lang === 'fa' ? 'مشاهده همه کتاب‌ها' : 'View All Books'; ?>
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Developer Section -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-4">
                <h4 style="color: var(--baltic-blue);">
                    <i class="bi bi-code-square"></i> 
                    <?php echo $lang === 'fa' ? 'توسعه دهنده' : 'Developer'; ?>
                </h4>
                <p class="lead mb-3">Mohammad Taha Abdinasab</p>
                <p class="text-muted">
                    <?php echo $lang === 'fa' 
                        ? 'دانشجوی مهندسی نرم‌افزار - دانشگاه شهید چمران کرمان' 
                        : 'Computer Software Engineering Student - Shahid Chamran University of Kerman'; ?>
                </p>
                <div class="mt-3">
                    <a href="https://github.com/mohammadtahaabdinasab" target="_blank" class="btn btn-outline-primary me-2">
                        <i class="bi bi-github"></i> GitHub Profile
                    </a>
                    <a href="https://github.com/mohammadtahaabdinasab/librava" target="_blank" class="btn btn-outline-secondary">
                        <i class="bi bi-code-square"></i> Project Repository
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include __DIR__ . '/views/layout/footer.php';
?>