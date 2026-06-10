<?php if (isset($_SESSION['admin_logged_in'])): ?>
        </div><!-- End content-area -->
        <footer style="padding: 1rem 2rem; border-top: 1px solid var(--border-soft); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem;">
            <span style="color:var(--text-muted); font-size:0.78rem;">&copy; <?= date('Y') ?> Lumos Studio. All rights reserved.</span>
            <span style="color:var(--text-muted); font-size:0.75rem; opacity:0.6;">Admin Panel v2.0</span>
        </footer>
    </main><!-- End main-content -->
</div><!-- End admin-wrapper -->
<?php else: ?>
    </div><!-- End login-root -->
<?php endif; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function() {
    // Mobile sidebar toggle
    const toggleBtn = document.getElementById('mobileToggle');
    const sidebar   = document.getElementById('sidebar');
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
        // Close on outside click
        document.addEventListener('click', function(e) {
            if (sidebar.classList.contains('show') && !sidebar.contains(e.target) && e.target !== toggleBtn) {
                sidebar.classList.remove('show');
            }
        });
    }

    // Live clock
    const clockEl = document.getElementById('liveClock');
    if (clockEl) {
        function updateClock() {
            const now = new Date();
            clockEl.textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
        }
        updateClock();
        setInterval(updateClock, 1000);
    }
})();
</script>
</body>
</html>