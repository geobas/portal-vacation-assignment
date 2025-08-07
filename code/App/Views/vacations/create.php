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
    <h2 class="mb-4">Vacation request</h2>

    <form action="/vacations" method="post" class="p-4 border rounded shadow-sm bg-light">
      <div class="mb-3">
      <label for="start_date" class="form-label">Start Date</label>
      <input type="text" class="form-control w-50" id="start_date" name="start_date" required>
      </div>

      <div class="mb-3">
      <label for="end_date" class="form-label">End Date</label>
      <input type="text" class="form-control w-50" id="end_date" name="end_date" required>
      </div>

      <div class="mb-3">
        <label for="reason" class="form-label">Reason</label>
        <textarea class="form-control w-51 h-10" id="reason" name="reason" rows="5" required></textarea>
      </div>

      <button type="submit" class="btn btn-primary">Create</button>
      <a href="/vacations" class="btn btn-primary">Cancel</a>

      <div class="mt-3 text-center">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="text-center mb-4"><?= $_SESSION['error'] ?></div>
        <?php endif; ?>
        <?php unset($_SESSION['error']); ?>
      </div>
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
