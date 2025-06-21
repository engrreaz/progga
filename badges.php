<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>ব্যাজ গ্যালারি</title>
  <link rel="stylesheet" href="assets/css/badges.css">
</head>
<body>
  <h2>🎖️ আমার ব্যাজসমূহ</h2>
  <div id="badgeContainer" class="badge-grid"></div>

  <script>
    fetch('fetch_user_badges.php')
      .then(res => res.json())
      .then(data => {
        const container = document.getElementById('badgeContainer');
        data.forEach(badge => {
          const div = document.createElement('div');
          div.className = "badge-card " + (badge.earned ? "earned" : "locked");
          div.innerHTML = `
            <img src="${badge.icon_url || 'placeholder.png'}" alt="${badge.name}">
            <h4>${badge.name}</h4>
            <p>${badge.description}</p>
          `;
          container.appendChild(div);
        });
      });
  </script>
</body>
</html>
