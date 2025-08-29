<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Vacation Portal</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .login-container {
      max-width: 400px;
      margin: 80px auto;
      padding: 30px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="login-container">
      <h3 class="text-center mb-4">Login</h3>
      <form action="/login" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label for="email" class="form-label">Username</label>
          <input class="form-control" id="email" name="username" placeholder="Enter username">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
        <div class="mt-3 text-center">
          <?php if (isset($_SESSION['error'])): ?>
              <div class="text-center mb-4"><?= $_SESSION['error'] ?></div>
          <?php endif; ?>
          <?php unset($_SESSION['error']); ?>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap 5 JS (optional, for interactivity) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
