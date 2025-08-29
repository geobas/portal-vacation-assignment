<?php
$title = 'Create User';
include __DIR__ . '/../partials/header.php';
?>
  <div class="container mt-5 login-container">
    <h2 class="mb-4">User properties</h2>

    <form action="/users" method="post" class="p-4 border rounded shadow-sm bg-light">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label for="name" class="form-label">Username</label>
        <input type="text" class="form-control w-50" id="name" name="username" required />
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control w-50" id="email" name="email" required />
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Employee code</label>
        <input type="text" maxlength="7" class="form-control w-50" id="employee_code" name="employee_code" required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,7);" />
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control w-50" id="password" name="password" required />
      </div>

      <button type="submit" class="btn btn-primary">Create</button>
      <a href="/users" class="btn btn-primary">Cancel</a>

      <div class="mt-3 text-center">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="text-center mb-4"><?= $_SESSION['error'] ?></div>
        <?php endif; ?>
        <?php unset($_SESSION['error']); ?>
      </div>
    </form>
  </div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
