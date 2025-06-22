<!DOCTYPE html>
<html lang="bn">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>প্রজ্ঞা — AI·PLS</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">

  <link href="assets/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/theme.css">

</head>

<body class="theme-light">

 <div id="splash" >
  <img src="assets/images/progga.png" style="width:100px;"/>
    <h1 class="m-0 p-0">প্রজ্ঞা</h1>
    <h6 class="m-0 p-0">শেখো, নিজের মতো করে</h6>
    <div class="loader"></div>
  </div>


  <nav class="navbar navbar-expand-lg bg-body-tertiary px-3 shadow-sm">
    <a class="navbar-brand" href="dashboard.php">প্রজ্ঞা — AI·PLS</a>

     <?php if (!empty($_SESSION['usr'])): ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="dashboard.php"><?php echo $usr . ' - ' . $userlevel; ?></a>
            </li>
            <li class="nav-item"><a class="nav-link" href="dashboard.php">ড্যাশবোর্ড</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">লগ আউট</a></li>
          </ul>
        </div>
      <?php endif; ?>


    <div class="ms-auto">
      <button id="toggleTheme" class="btn btn-outline-dark p-1 pe-2">🌙</button>
    </div>
  </nav>


  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
    
    </div>
  </nav>


<div class="container mt-4">




      <?php 
      // include 'sidebar.php'; 
      ?>

