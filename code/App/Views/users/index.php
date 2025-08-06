<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Table</title>
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
  <div class="container my-5">
    <h2 class="mb-4">User List</h2>
    <a href="/users/create" class="btn btn-primary mb-3">
      <i class="bi bi-person-plus"></i> Create User
    </a>
     <table class="table table-striped table-bordered">
      <thead class="table-dark">
        <tr>
          <th>Username</th>
          <th>Email</th>
          <th>Employee Code</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user): ?>
          <tr>
            <td><?= htmlentities($user['username']) ?></td>
            <td><?= htmlentities($user['email']) ?></td>
            <td><?= htmlentities($user['employee_code']) ?></td>
            <td>
              <a href="/users/<?= htmlspecialchars($user['id']) ?>/edit">Edit</a>
              <a href="#" class="text-danger" onclick="event.preventDefault(); if(confirm('Delete user?')) document.getElementById('delete-form-<?= $user['id'] ?>').submit();">Delete</a>
                <form id="delete-form-<?= $user['id'] ?>" action="/users/<?= htmlspecialchars($user['id']) ?>/delete" method="post" style="display: none;">
              </form>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <a href="/logout">Sign Out</a>
  </div>

  <!-- Bootstrap JS Bundle (optional for interactivity) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
