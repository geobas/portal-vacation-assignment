<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create vacation</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css">
  <style>
    .login-container {
      max-width: 600px;
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
    <h2 class="mb-4">Vacation request</h2>

    <form action="/vacations" method="post" class="p-4 border rounded shadow-sm bg-light">
      <div class="row mb-3 align-items-center">
        <div class="col-sm-3">
          <label for="start_date" class="col-form-label">Date from</label>
        </div>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="start_date" name="start_date" required>
        </div>
      </div>

      <div class="row mb-3 align-items-center">
        <div class="col-sm-3">
          <label for="end_date" class="col-form-label">Date to</label>
        </div>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="end_date" name="end_date" required>
        </div>
      </div>

      <div class="mb-3 d-flex align-items-start">
        <label for="reason" class="form-label me-3 pt-2" style="min-width: 113px;">Reason</label>
        <textarea class="form-control" id="reason" name="reason" rows="5" required></textarea>
      </div>

      <div class="d-flex justify-content-between">
        <div>
          <button type="submit" class="btn btn-primary">Create</button>
          <a href="/vacations" class="btn btn-secondary">Cancel</a>
        </div>
      </div>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="text-danger text-center mt-3">
          <?= $_SESSION['error'] ?>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>
    </form>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script>
  <script>
    const startDateEl = document.getElementById('start_date');
    const endDateEl = document.getElementById('end_date');

    new Datepicker(startDateEl, {
      format: 'yyyy/mm/dd',
      autohide: true
    });

   new Datepicker(endDateEl, {
      format: 'yyyy/mm/dd',
      autohide: true
   });
</script>  
</body>
</html>
