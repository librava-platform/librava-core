<!DOCTYPE html>
<html lang="<?php echo $lang ?? 'en'; ?>" dir="<?php echo ($lang ?? 'en') === 'fa' ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Librava - Digital Library Management System">
    <meta name="author" content="Mohammad Taha Abdinasab">
    <title><?php echo $pageTitle ?? 'Librava - Digital Library'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <?php if (($lang ?? 'en') === 'fa'): ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <?php else: ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php endif; ?>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            /* Librava Green Palette */
            --frosted-mint: #d8f3dc;
            --celadon: #b7e4c7;
            --celadon-2: #95d5b2;
            --mint-leaf: #74c69d;
            --mint-leaf-2: #52b788;
            --sea-green: #40916c;
            --dark-emerald: #2d6a4f;
            --pine-teal: #1b4332;
            --evergreen: #081c15;
            
            /* Legacy names for compatibility */
            --baltic-blue: var(--mint-leaf-2);
            --teal: var(--sea-green);
            --verdigris: var(--mint-leaf);
            --cream: var(--frosted-mint);
        }
        
        body {
            font-family: <?php echo ($lang ?? 'en') === 'fa' ? "'Vazir', 'Tahoma', sans-serif" : "'Segoe UI', 'Roboto', sans-serif"; ?>;
            background: linear-gradient(135deg, var(--mint-leaf-2) 0%, var(--sea-green) 100%);
            min-height: 100vh;
            background-attachment: fixed;
        }
        
        .navbar {
            background: rgba(216, 243, 220, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            color: var(--mint-leaf-2) !important;
            font-size: 1.5rem;
        }
        
        .nav-link {
            color: var(--sea-green) !important;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-link:hover {
            color: var(--mint-leaf) !important;
        }
        
        .btn-primary {
            background: var(--mint-leaf-2);
            border-color: var(--mint-leaf-2);
        }
        
        .btn-primary:hover {
            background: var(--sea-green);
            border-color: var(--sea-green);
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            background: var(--frosted-mint);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .card-header {
            background: var(--mint-leaf-2);
            color: white;
            border: none;
            border-radius: 15px 15px 0 0 !important;
            padding: 1rem 1.5rem;
        }
        
        .book-cover {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            background: var(--celadon);
        }
        
        .badge-category {
            background: var(--mint-leaf);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        
        footer {
            background: rgba(216, 243, 220, 0.95);
            backdrop-filter: blur(10px);
            margin-top: 3rem;
        }
        
        .btn-lang {
            background: var(--mint-leaf);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            transition: background 0.3s;
        }
        
        .btn-lang:hover {
            background: var(--mint-leaf-2);
        }
        
        .main-content {
            min-height: calc(100vh - 200px);
            padding: 2rem 0;
        }
        
        .text-primary {
            color: var(--mint-leaf-2) !important;
        }
        
        .bg-primary {
            background-color: var(--mint-leaf-2) !important;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                <i class="bi bi-book-fill"></i> لیبراوا | Librava
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>">
                            <i class="bi bi-house-door"></i> 
                            <?php echo ($lang ?? 'en') === 'fa' ? 'خانه' : 'Home'; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>books.php">
                            <i class="bi bi-collection"></i> 
                            <?php echo ($lang ?? 'en') === 'fa' ? 'کتاب‌ها' : 'Books'; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>crud_test.php">
                            <i class="bi bi-gear"></i> 
                            <?php echo ($lang ?? 'en') === 'fa' ? 'مدیریت' : 'Admin'; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>api_test.html">
                            <i class="bi bi-code-slash"></i> 
                            <?php echo ($lang ?? 'en') === 'fa' ? 'API' : 'API'; ?>
                        </a>
                    </li>
                    <li class="nav-item ms-3">
                        <a href="?lang=<?php echo ($lang ?? 'en') === 'en' ? 'fa' : 'en'; ?>" class="btn btn-lang btn-sm">
                            <?php echo ($lang ?? 'en') === 'en' ? 'فارسی' : 'English'; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">

