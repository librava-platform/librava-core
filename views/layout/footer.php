        </div><!-- /.container -->
    </div><!-- /.main-content -->

    <!-- Footer -->
    <footer class="py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <h5 style="color: var(--baltic-blue);">
                        <i class="bi bi-book-fill"></i> لیبراوا | Librava
                    </h5>
                    <p class="text-muted mb-0">
                        <?php echo ($lang ?? 'en') === 'fa' 
                            ? 'سیستم مدیریت کتابخانه دیجیتال' 
                            : 'Digital Library Management System'; ?>
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="text-muted mb-2">
                        <?php echo ($lang ?? 'en') === 'fa' 
                            ? 'توسعه یافته توسط' 
                            : 'Developed by'; ?>
                        <strong style="color: var(--teal);">Mohammad Taha Abdinasab</strong>
                    </p>
                    <p class="mb-0">
                        <a href="https://github.com/mohammadtahaabdinasab" target="_blank" class="text-decoration-none" style="color: var(--verdigris);">
                            <i class="bi bi-github"></i> GitHub
                        </a>
                        <span class="mx-2">|</span>
                        <a href="https://github.com/mohammadtahaabdinasab/librava" target="_blank" class="text-decoration-none" style="color: var(--verdigris);">
                            <i class="bi bi-code-square"></i> 
                            <?php echo ($lang ?? 'en') === 'fa' ? 'مخزن پروژه' : 'Project Repo'; ?>
                        </a>
                    </p>
                </div>
            </div>
            <hr class="my-3">
            <div class="text-center">
                <small class="text-muted">
                    &copy; <?php echo date('Y'); ?> Librava. 
                    <?php echo ($lang ?? 'en') === 'fa' 
                        ? 'تمامی حقوق محفوظ است.' 
                        : 'All rights reserved.'; ?>
                </small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php if (isset($customJS)): ?>
        <!-- Custom JavaScript -->
        <script><?php echo $customJS; ?></script>
    <?php endif; ?>
</body>
</html>

