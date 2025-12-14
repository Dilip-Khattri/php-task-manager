    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?> v<?php echo APP_VERSION; ?>. All rights reserved.</p>
        </div>
    </footer>

    <!-- Toast Notification Container -->
    <div id="toast-container"></div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay" style="display: none;">
        <div class="spinner"></div>
    </div>

    <!-- Core JavaScript -->
    <script src="js/script.js"></script>
    <script src="js/tasks.js"></script>
    
    <!-- Additional page-specific scripts -->
    <?php if (isset($additionalScripts)): ?>
        <?php foreach ($additionalScripts as $script): ?>
            <script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Initialize theme -->
    <script>
        // Apply saved theme on page load
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.body.setAttribute('data-theme', savedTheme);
    </script>
</body>
</html>
