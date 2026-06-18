            </main>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
    lucide.createIcons();

    function toggleDarkMode() {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
        if (typeof updateCharts === 'function') {
            updateCharts();
        }
    }

    function isDark() {
        return document.documentElement.classList.contains('dark');
    }
    </script>
    <?= $extraScripts ?? '' ?>
</body>
</html>
