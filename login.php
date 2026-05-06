<?php
require_once __DIR__ . '/auth.php';

if (is_admin_logged_in()) {
    header('Location: admin/');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if (attempt_login($username, $password)) {
        header('Location: admin/');
        exit;
    }
    $error = 'Invalid username or password.';
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Login</title><link rel="stylesheet" href="stylez.css"></head>
<body><main class="container" style="padding:80px 0;max-width:520px;"><h1>Admin Login</h1><p>Default seeded admin: <strong>admin / admin123</strong>. Change it after first login.</p>
<?php if ($error): ?><p style="color:#dc2626"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post" style="display:grid;gap:12px;"><input name="username" placeholder="Username" required><input name="password" placeholder="Password" type="password" required><button class="btn btn-primary" type="submit">Login</button></form></main></body></html>
