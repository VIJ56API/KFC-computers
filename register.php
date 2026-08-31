<?php
require_once __DIR__ . '/includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';
$redirect = $_GET['redirect'] ?? 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $pdo = getDb();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'An account with this email already exists.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'customer')");
            $stmt->execute([$name, $email, $hashed]);

            $userId = $pdo->lastInsertId();
            $_SESSION['user'] = [
                'id' => $userId,
                'name' => $name,
                'email' => $email,
                'role' => 'customer'
            ];

            header("Location: $redirect");
            exit;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <div class="form-card">
    <div style="text-align: center; margin-bottom: 2rem;">
      <h2 style="font-size: 2rem; color: #000000; font-weight: 900;">Create Your Account</h2>
      <p style="color: #000000; font-size: 0.95rem; margin-top: 0.4rem; font-weight: 600;">
        Join KFC Computers to track orders and save custom PC builds.
      </p>
    </div>

    <?php if (!empty($error)): ?>
      <div style="background: #fee2e2; border: 2px solid var(--accent-red); color: var(--accent-red); padding: 0.8rem; border-radius: var(--radius-sm); font-size: 0.88rem; margin-bottom: 1.2rem; font-weight: 700;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form action="register.php?redirect=<?= urlencode($redirect) ?>" method="POST">
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="name" class="form-control" placeholder="e.g. Rahul Sharma" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="e.g. rahul@example.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" placeholder="At least 6 characters" required>
      </div>

      <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter password" required>
      </div>

      <button type="submit" class="btn btn-primary btn-block" style="padding: 0.8rem; margin-top: 1rem;">
        <i class="fa-solid fa-user-plus"></i> Create Account
      </button>
    </form>

    <div style="margin-top: 1.8rem; text-align: center; font-size: 0.9rem; color: #000000; font-weight: 600;">
      Already have an account? <a href="login.php?redirect=<?= urlencode($redirect) ?>">Sign In</a>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
