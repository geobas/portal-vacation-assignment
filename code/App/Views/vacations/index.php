<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Vacations List</title>
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
      <h2 class="mb-4">Vacations List</h2>
      <div class="d-flex justify-content-between align-items-center mb-3">
      <a href="/vacations/create" class="btn btn-primary mb-3">
        <i class="bi bi-person-plus"></i> Create Vacation
      </a>
      <span class="text-muted fw-bold">
        <span>
          Username : <?= $username ?>
        </span>
        <br>
        <span>
          Total vacation days: <?= App\Models\Vacation::TOTAL_VACATION_DAYS ?>
        </span>
        <br>
        <span>
          Vacation days left: 
          <?= App\Models\Vacation::TOTAL_VACATION_DAYS - array_reduce($vacations, function($temp, $v) {
            return $temp + ((new DateTime($v['start_date']))->diff(new DateTime($v['end_date']))->days + 1);
          }, 0); ?>
        <span>
      </span>
    </div>
    <table class="table table-striped table-bordered">
      <thead class="table-dark">
        <tr>
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
            <td><?= htmlentities($vacation['submitted_at']) ?></td>
            <td><?= date('Y/m/d', strtotime($vacation['start_date'])) ?> - <?= date('Y/m/d', strtotime($vacation['end_date']))  ?></td>
            <td><?= (new DateTime($vacation['start_date']))->diff(new DateTime($vacation['end_date']))->days + 1; ?></td>
            <td><?= htmlentities($vacation['reason']) ?></td>
            <td><?= htmlentities($vacation['status_name']) ?></td>
            <td>
              <?php if ($vacation['status_id'] === App\Enums\StatusEnum::PENDING->value): ?>
              <a href="javascript:void(0);" class="text-danger" onclick="event.preventDefault(); if(confirm('Remove Vacation?')) document.getElementById('delete-form-<?= $vacation['id'] ?>').submit();">Remove</a>
                <form id="delete-form-<?= $vacation['id'] ?>" action="/vacations/<?= htmlspecialchars($vacation['id']) ?>/delete" method="post" style="display: none;">
              </form>
              <? endif; ?>
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
