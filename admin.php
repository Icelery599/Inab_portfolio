<?php
require_once __DIR__ . '/auth.php';
require_admin();

$currentUser = current_admin_user();
$notice = '';
$error = '';

function h(mixed $value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function checked(bool $value): string {
    return $value ? 'checked' : '';
}

function selected(string $actual, string $expected): string {
    return $actual === $expected ? 'selected' : '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'add_service') {
            add_item('services', [
                'title' => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'icon' => trim($_POST['icon'] ?? 'fa-code') ?: 'fa-code',
            ]);
            $notice = 'Service added successfully.';
        } elseif ($action === 'update_service') {
            update_item('services', (int) ($_POST['id'] ?? 0), [
                'title' => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'icon' => trim($_POST['icon'] ?? 'fa-code') ?: 'fa-code',
            ]);
            $notice = 'Service updated successfully.';
        } elseif ($action === 'delete_service') {
            delete_item('services', (int) ($_POST['id'] ?? 0));
            $notice = 'Service deleted successfully.';
        } elseif ($action === 'add_project') {
            add_item('projects', [
                'title' => trim($_POST['title'] ?? ''),
                'category' => trim($_POST['category'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'image' => trim($_POST['image'] ?? ''),
                'url' => trim($_POST['url'] ?? ''),
            ]);
            $notice = 'Project added successfully.';
        } elseif ($action === 'update_project') {
            update_item('projects', (int) ($_POST['id'] ?? 0), [
                'title' => trim($_POST['title'] ?? ''),
                'category' => trim($_POST['category'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'image' => trim($_POST['image'] ?? ''),
                'url' => trim($_POST['url'] ?? ''),
            ]);
            $notice = 'Project updated successfully.';
        } elseif ($action === 'delete_project') {
            delete_item('projects', (int) ($_POST['id'] ?? 0));
            $notice = 'Project deleted successfully.';
        } elseif ($action === 'add_post') {
            add_item('posts', ['title' => trim($_POST['title'] ?? ''), 'excerpt' => trim($_POST['excerpt'] ?? '')]);
            $notice = 'Post added successfully.';
        } elseif ($action === 'delete_post') {
            delete_item('posts', (int) ($_POST['id'] ?? 0));
            $notice = 'Post deleted successfully.';
        } elseif ($action === 'add_user') {
            create_user(
                trim($_POST['username'] ?? ''),
                trim($_POST['email'] ?? ''),
                (string) ($_POST['password'] ?? ''),
                $_POST['role'] === 'admin' ? 'admin' : 'editor',
                isset($_POST['is_active'])
            );
            $notice = 'User added successfully.';
        } elseif ($action === 'update_user') {
            $userId = (int) ($_POST['id'] ?? 0);
            $role = $_POST['role'] === 'admin' ? 'admin' : 'editor';
            $active = isset($_POST['is_active']);

            if ($currentUser !== null && $userId === (int) $currentUser['id']) {
                $role = 'admin';
                $active = true;
            }

            update_user(
                $userId,
                trim($_POST['username'] ?? ''),
                trim($_POST['email'] ?? ''),
                $role,
                $active,
                trim($_POST['password'] ?? '')
            );
            $notice = 'User updated successfully.';
        } elseif ($action === 'delete_user') {
            $userId = (int) ($_POST['id'] ?? 0);
            if ($currentUser !== null && $userId === (int) $currentUser['id']) {
                $error = 'You cannot delete your own admin account while logged in.';
            } else {
                delete_user($userId);
                $notice = 'User deleted successfully.';
            }
        }
    } catch (Throwable $exception) {
        $error = 'Database action failed: ' . $exception->getMessage();
    }
}

$services = read_collection('services');
$projects = read_collection('projects');
$posts = read_collection('posts');
$users = read_collection('users');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="stylez.css">
</head>
<body>
<main class="container admin-dashboard">
  <div class="admin-header">
    <h1>Admin Dashboard</h1>
    <p>Manage MySQL-backed services, projects, posts, and user accounts with CRUD workflows.</p>
    <div class="admin-actions">Signed in as <?= h($currentUser['username'] ?? 'admin') ?> · <a href="index.php">View Site</a> · <a href="logout.php">Logout</a></div>
  </div>

  <?php if ($notice): ?><div class="admin-alert success"><?= h($notice) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="admin-alert error"><?= h($error) ?></div><?php endif; ?>

  <section class="admin-panel">
    <h2>Add Service</h2>
    <form method="post" class="admin-form admin-form-grid">
      <input type="hidden" name="action" value="add_service">
      <input name="title" placeholder="Service title" required>
      <input name="icon" placeholder="Font Awesome icon e.g. fa-code" value="fa-code">
      <textarea name="description" placeholder="Description" required></textarea>
      <button class="btn btn-primary">Add Service</button>
    </form>
    <div class="admin-list">
      <?php foreach($services as $s): ?>
        <form method="post" class="admin-item admin-edit-card">
          <input type="hidden" name="id" value="<?= h($s['id']) ?>">
          <input type="hidden" name="action" value="update_service">
          <input name="title" value="<?= h($s['title']) ?>" required>
          <input name="icon" value="<?= h($s['icon'] ?? 'fa-code') ?>">
          <textarea name="description" required><?= h($s['description']) ?></textarea>
          <div class="admin-row-actions">
            <button class="btn btn-primary">Update</button>
            <button name="action" value="delete_service" class="btn btn-danger" formnovalidate>Delete</button>
          </div>
        </form>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="admin-panel">
    <h2>Add Project</h2>
    <form method="post" class="admin-form admin-form-grid">
      <input type="hidden" name="action" value="add_project">
      <input name="title" placeholder="Project title" required>
      <input name="category" placeholder="Category e.g. web/branding" required>
      <input name="image" placeholder="Image URL" required>
      <input name="url" placeholder="Project URL (https://...)">
      <textarea name="description" placeholder="Description" required></textarea>
      <button class="btn btn-primary">Add Project</button>
    </form>
    <div class="admin-list">
      <?php foreach($projects as $p): ?>
        <form method="post" class="admin-item admin-edit-card">
          <input type="hidden" name="id" value="<?= h($p['id']) ?>">
          <input type="hidden" name="action" value="update_project">
          <input name="title" value="<?= h($p['title']) ?>" required>
          <input name="category" value="<?= h($p['category']) ?>" required>
          <input name="image" value="<?= h($p['image']) ?>" required>
          <input name="url" value="<?= h($p['url'] ?? '') ?>">
          <textarea name="description" required><?= h($p['description']) ?></textarea>
          <div class="admin-row-actions">
            <button class="btn btn-primary">Update</button>
            <button name="action" value="delete_project" class="btn btn-danger" formnovalidate>Delete</button>
          </div>
        </form>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="admin-panel">
    <h2>Users</h2>
    <form method="post" class="admin-form admin-form-grid">
      <input type="hidden" name="action" value="add_user">
      <input name="username" placeholder="Username" required>
      <input name="email" type="email" placeholder="Email" required>
      <input name="password" type="password" placeholder="Temporary password" required>
      <select name="role"><option value="editor">Editor</option><option value="admin">Admin</option></select>
      <label class="checkbox-line"><input type="checkbox" name="is_active" checked> Active user</label>
      <button class="btn btn-primary">Add User</button>
    </form>
    <div class="admin-list">
      <?php foreach($users as $user): ?>
        <form method="post" class="admin-item admin-edit-card user-card">
          <input type="hidden" name="id" value="<?= h($user['id']) ?>">
          <input type="hidden" name="action" value="update_user">
          <input name="username" value="<?= h($user['username']) ?>" required>
          <input name="email" type="email" value="<?= h($user['email']) ?>" required>
          <input name="password" type="password" placeholder="Leave blank to keep password">
          <select name="role">
            <option value="editor" <?= selected($user['role'], 'editor') ?>>Editor</option>
            <option value="admin" <?= selected($user['role'], 'admin') ?>>Admin</option>
          </select>
          <label class="checkbox-line"><input type="checkbox" name="is_active" <?= checked((int) $user['is_active'] === 1) ?>> Active</label>
          <div class="admin-row-actions">
            <button class="btn btn-primary">Update</button>
            <button name="action" value="delete_user" class="btn btn-danger" formnovalidate>Delete</button>
          </div>
        </form>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="admin-panel">
    <h2>Blog posts (Products & Services)</h2>
    <form method="post" class="admin-form">
      <input type="hidden" name="action" value="add_post">
      <input name="title" placeholder="Post title" required>
      <textarea name="excerpt" placeholder="Short content" required></textarea>
      <button class="btn btn-primary">Add Post</button>
    </form>
    <div class="admin-list">
      <?php foreach($posts as $post): ?>
        <div class="admin-item"><strong><?= h($post['title']) ?></strong><form method="post" style="display:inline"><input type="hidden" name="action" value="delete_post"><input type="hidden" name="id" value="<?= h($post['id']) ?>"><button>Delete</button></form></div>
      <?php endforeach; ?>
    </div>
  </section>
</main>
</body>
</html>
