<?php
require_once 'db.php';
$user_id = $_SESSION['user_id'] ?? 1;

// ১০ দিনের XP সংগ্রহ
$sql = "
SELECT DATE(created_at) as date, SUM(xp) as total_xp
FROM user_xp_log
WHERE email = ?
GROUP BY DATE(created_at)
ORDER BY DATE(created_at) DESC
LIMIT 10
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usr);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
$data = array_reverse($data); // পুরনো থেকে নতুন

?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>প্রগতি গ্রাফ</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { font-family: sans-serif; text-align: center; background: #f7f8fc; padding: 40px; }
    canvas { max-width: 800px; margin: auto; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 0 6px rgba(0,0,0,0.1); }
  </style>
</head>
<body>
  <h2>📈 ডেইলি XP প্রগ্রেস</h2>
  <canvas id="xpChart"></canvas>

  <script>
    const labels = <?= json_encode(array_column($data, 'date')) ?>;
    const xpData = <?= json_encode(array_column($data, 'total_xp')) ?>;

    new Chart(document.getElementById('xpChart'), {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'প্রতিদিনের XP',
          data: xpData,
          borderColor: '#00a36c',
          backgroundColor: 'rgba(0, 163, 108, 0.1)',
          fill: true,
          tension: 0.3,
          pointRadius: 5
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            title: { display: true, text: 'XP' }
          },
          x: {
            title: { display: true, text: 'তারিখ' }
          }
        }
      }
    });
  </script>
</body>
</html>
