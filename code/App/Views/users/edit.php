<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Update User</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    .login-container {
      max-width: 500px;
      margin: 80px auto;
      padding: 30px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
  </style>  
</head>
<body>    
  <div class="container mt-5 login-container">
    <h2 class="mb-4">User properties</h2>

    <form action="/users/<?= htmlspecialchars($user['id']) ?>" method="post" class="p-4 border rounded shadow-sm bg-light">
      <div class="mb-3">
        <label for="name" class="form-label">Username</label>
        <input type="text" class="form-control w-50" id="name" name="username" value="<?= htmlspecialchars($user['username']) ?>" required />
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control w-50" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required />
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Employee code</label>
        <input type="text" maxlength="7" class="form-control w-50" id="employee_code" name="employee_code" value="<?= htmlspecialchars($user['employee_code']) ?>" required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,7);" />
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control w-50" id="password" name="password" />
      </div>

      <button type="submit" class="btn btn-primary">Update</button>
      <a href="/users" class="btn btn-primary">Cancel</a>
    </form>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
