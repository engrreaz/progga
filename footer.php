<!-- Bootstrap JS -->
</div>

<footer class="text-center mt-5 text-muted">
    <p>&copy; 2025 প্রজ্ঞা | সব অধিকার সংরক্ষিত</p>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Theme Toggle JS -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const body = document.body;
        const theme = localStorage.getItem('theme') || 'light';
        body.classList.add(`theme-${theme}`);

        document.getElementById('toggleTheme').addEventListener('click', () => {
            const current = body.classList.contains('theme-dark') ? 'dark' : 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            body.classList.remove(`theme-${current}`);
            body.classList.add(`theme-${next}`);
            localStorage.setItem('theme', next);
        });
    });
</script>

<script>
    // সিমুলেটেড লোড টাইমার (3 সেকেন্ড)
    window.addEventListener('load', () => {
        setTimeout(() => {
            const splash = document.getElementById('splash');
            splash.classList.add('hidden');
            // পুরোপুরি DOM থেকে সরাতে চাইলে:
            // splash.style.display = 'none';
        }, 100); // 3 সেকেন্ড পর splash স্ক্রিন লুকিয়ে যাবে
    });
</script>

<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="assets/js/main.js"></script>
</body>

</html>