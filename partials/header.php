<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ITCS333 Course Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/style.css">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
      <a class="navbar-brand" href="index.php">ITCS333</a>
      <div class="ms-auto">
        <?php if (isset($_SESSION['user'])): ?>
          <span class="text-light me-2">Hi, <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
          <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
        <?php endif; ?>
      </div>
    </nav>