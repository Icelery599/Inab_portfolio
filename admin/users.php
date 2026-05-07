<?php
require_once __DIR__ . '/_bootstrap.php';

$activePage = 'users';
$pageTitle = 'Manage Users';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'add_user') {
            create_user(
                trim($_POST['username'] ?? ''),
                trim($_POST['email'] ?? ''),
                (string) ($_POST['password'] ?? ''),
                admin_role_from_post(),
                isset($_POST['is_active'])
            );
            $notice = 'User added successfully.';
        } elseif ($action === 'update_user') {
            $userId = (int) ($_POST['id'] ?? 0);
            $role = admin_role_from_post();
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
        $error = 'User action failed: ' . $exception->getMessage();
    }
}

$users = read_collection('users');
require __DIR__ . '/_header.php';
?>
  <section class="admin-panel">
    <h2>Add User</h2>
    <form method="post" class="admin-form admin-form-grid">
      <input type="hidden" name="action" value="add_user">
      <input name="username" placeholder="Username" required>
      <input name="email" type="email" placeholder="Email" required>
      <input name="password" type="password" placeholder="Temporary password" required>
      <select name="role"><option value="editor">Editor</option><option value="admin">Admin</option></select>
      <label class="checkbox-line"><input type="checkbox" name="is_active" checked> Active user</label>
      <button class="btn btn-primary">Add User</button>
    </form>
  </section>

  <section class="admin-panel">
    <h2>Existing Users</h2>
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
<?php require __DIR__ . '/_footer.php'; ?>
