<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Users List</title>
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
    <div class="mt-3 text-center">
      <?php if (isset($_SESSION['error'])): ?>
          <div class="text-center mb-4"><?= $_SESSION['error'] ?></div>
      <?php endif; ?>
      <?php unset($_SESSION['error']); ?>
     </div>
     
     <?php if (count($users) > 0): ?>
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
              &nbsp;&nbsp;&nbsp;
              <a href="javascript:void(0);" class="text-danger" onclick="event.preventDefault(); if(confirm('Delete user?')) document.getElementById('delete-form-<?= $user['id'] ?>').submit();">Delete</a>
                <form id="delete-form-<?= $user['id'] ?>" action="/users/<?= htmlspecialchars($user['id']) ?>/delete" method="post" style="display: none;">
              </form>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php endif; ?>

    <?php if (count($vacations) > 0): ?>
    <br>
    <h2 class="mb-4">Vacations List</h2>
    <table class="table table-striped table-bordered">
      <thead class="table-dark">
        <tr>
          <th>Username</th>
          <th>Date submitted</th>
          <th>Dates requested</th>
          <th>Total days</th>
          <th>Reason</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($vacations as $vacation): ?>
          <tr>
            <td><?= htmlentities($vacation['user_name']) ?></td>
            <td><?= htmlentities($vacation['submitted_at']) ?></td>
            <td><?= date('Y/m/d', strtotime($vacation['start_date'])) ?> - <?= date('Y/m/d', strtotime($vacation['end_date']))  ?></td>
            <td><?= (new DateTime($vacation['start_date']))->diff(new DateTime($vacation['end_date']))->days + 1; ?></td>
            <td><?= htmlentities($vacation['reason']) ?></td>
            <td><?= htmlentities($vacation['status_name']) ?></td>
            <td>
              <?php if ($vacation['status_id'] !== App\Enums\StatusEnum::APPROVED->value): ?>
              <a href="javascript:void(0);" class="text-danger" onclick="event.preventDefault(); if(confirm('Approve Vacation?')) document.getElementById('approve-form-<?= $vacation['id'] ?>').submit();">Approve</a>
                <form id="approve-form-<?= $vacation['id'] ?>" action="users/vacations/<?= htmlspecialchars($vacation['id']) ?>/approve" method="post" style="display: none;">
              </form>
              <?php endif; ?>
              &nbsp;&nbsp;&nbsp;
              <?php if ($vacation['status_id'] !== App\Enums\StatusEnum::REJECTED->value): ?>
              <a href="javascript:void(0);" class="text-danger" onclick="event.preventDefault(); if(confirm('Reject Vacation?')) document.getElementById('reject-form-<?= $vacation['id'] ?>').submit();">Reject</a>
                <form id="reject-form-<?= $vacation['id'] ?>" action="users/vacations/<?= htmlspecialchars($vacation['id']) ?>/reject" method="post" style="display: none;">
              </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php endif; ?>

    <a href="/logout">Sign Out</a>
  </div>

  <!-- Bootstrap JS Bundle (optional for interactivity) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
