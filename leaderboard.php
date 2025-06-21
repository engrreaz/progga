<?php session_start(); ?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>প্রজ্ঞা লিডারবোর্ড</title>
  <link rel="stylesheet" href="assets/css/leaderboard.css">
</head>
<body>

  <h2>📊 প্রজ্ঞা লিডারবোর্ড</h2>

  <label for="boardType">Leaderboard ধরন:</label>
  <select id="boardType" onchange="loadLeaderboard()">
    <option value="global">🌐 গ্লোবাল</option>
    <option value="class">🏫 শ্রেণিভিত্তিক</option>
    <option value="school">🏛️ বিদ্যালয়ভিত্তিক</option>
    <option value="weekly">📅 সাপ্তাহিক</option>
    <option value="monthly">🗓️ মাসিক</option>
  </select>

  <table id="leaderboardTable">
    <thead>
      <tr>
        <th>অবস্থান</th>
        <th>নাম</th>
        <th>XP</th>
        <th>লেভেল</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <script>
    function loadLeaderboard() {
      const type = document.getElementById("boardType").value;
      fetch(`fetch_leaderboard.php?type=${type}`)
        .then(response => response.json())
        .then(data => {
          const tbody = document.querySelector("#leaderboardTable tbody");
          tbody.innerHTML = "";
          data.forEach((user, i) => {
            tbody.innerHTML += `
              <tr>
                <td>${i + 1}</td>
                <td>${user.name}</td>
                <td>${user.xp}</td>
                <td>${user.level}</td>
              </tr>`;
          });
        });
    }

    // প্রথমবার পেজ লোডে গ্লোবাল leaderboard লোড
    window.onload = loadLeaderboard;
  </script>

</body>
</html>
