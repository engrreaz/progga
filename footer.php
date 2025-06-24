<!-- Bootstrap JS -->
</div>

<footer class="text-center mt-5 text-muted">
    <p>&copy; 2025 প্রজ্ঞা | সব অধিকার সংরক্ষিত</p>
</footer>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Theme Toggle JS -->
<script>







    document.addEventListener('DOMContentLoaded', function () {
        const body = document.body;
        const theme = localStorage.getItem('theme') || 'light';
        body.classList.add(`theme-${theme}`);
        const icon = document.getElementById('themeIcon');

        const isDark = document.body.classList.contains('theme-dark');

        if (isDark) {
            icon.classList.remove('bi-moon-fill');
            icon.classList.add('bi-sun-fill');
        } else {
            icon.classList.remove('bi-sun-fill');
            icon.classList.add('bi-moon-fill');
        }

        document.getElementById('toggleTheme').addEventListener('click', () => {
    
            const icon = document.getElementById('themeIcon');
            const current = body.classList.contains('theme-dark') ? 'dark' : 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            body.classList.remove(`theme-${current}`);
            body.classList.add(`theme-${next}`);


            const isDark = document.body.classList.contains('theme-dark');
            // alert(isDark);
            if (isDark) {
                icon.classList.remove('bi-moon-fill');
                icon.classList.add('bi-sun-fill');
            } else {
                icon.classList.remove('bi-sun-fill');
                icon.classList.add('bi-moon-fill');
            }

            localStorage.setItem('theme', next);
        });


        const togglers = document.querySelectorAll(".caret");

        togglers.forEach(function (el) {
            el.addEventListener("click", function () {
                this.parentElement.querySelector(".nested").classList.toggle("active");
                this.classList.toggle("caret-down");
            });
        });

    });




</script>

<script>
    // সিমুলেটেড লোড টাইমার (3 সেকেন্ড)
    window.addEventListener('load', () => {
        setTimeout(() => {
            const splash = document.getElementById('splash');
            // splash.classList.add('hidden');
            // পুরোপুরি DOM থেকে সরাতে চাইলে:
            splash.style.display = 'none';
        }, 100); // 3 সেকেন্ড পর splash স্ক্রিন লুকিয়ে যাবে
    });
</script>

<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="assets/js/main.js"></script>

</html>