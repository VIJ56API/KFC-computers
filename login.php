<?php
require_once __DIR__ . '/includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';
$redirect = $_GET['redirect'] ?? 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Please fill in all credentials.';
    } else {
        $pdo = getDb();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role']
            ];

            if ($user['role'] === 'admin' && $redirect === 'index.php') {
                header('Location: admin/products.php');
            } else {
                header("Location: $redirect");
            }
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <div class="form-card">
    <div style="text-align: center; margin-bottom: 2rem;">
      <h2 style="font-size: 2rem; color: #000000; font-weight: 900;">Sign In to <span class="gradient-text">KFC Computers</span></h2>
      <p style="color: #000000; font-size: 0.95rem; margin-top: 0.4rem; font-weight: 600;">
        Log in to save custom PC builds and complete your orders.
      </p>
    </div>

    <?php if (!empty($error)): ?>
      <div style="background: #fee2e2; border: 2px solid var(--accent-red); color: var(--accent-red); padding: 0.8rem; border-radius: var(--radius-sm); font-size: 0.88rem; margin-bottom: 1.2rem; font-weight: 700;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form action="login.php?redirect=<?= urlencode($redirect) ?>" method="POST">
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="e.g. customer@example.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
      </div>

      <button type="submit" class="btn btn-primary btn-block" style="padding: 0.8rem; margin-top: 1rem;">
        <i class="fa-solid fa-right-to-bracket"></i> Sign In
      </button>
    </form>

    <div style="margin-top: 1.8rem; text-align: center; font-size: 0.9rem; color: #000000; font-weight: 600;">
      Don't have an account? <a href="register.php?redirect=<?= urlencode($redirect) ?>">Create New Account</a>
    </div>

    <div style="margin-top: 1.5rem; background: var(--bg-surface); padding: 1rem; border-radius: var(--radius-sm); font-size: 0.85rem; color: #000000; font-weight: 600; border: 2px dashed #cbd5e1;">
      <strong>Demo Accounts:</strong><br>
      Admin: <code>admin@litcomputers.in</code> / <code>admin123</code><br>
      Customer: Create a new account or sign up below.
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
