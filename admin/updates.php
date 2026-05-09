<?php
require_once __DIR__ . '/_bootstrap.php';

$activePage = 'updates';
$pageTitle = 'Manage Updates';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'add_update') {
            add_item('posts', [
                'title' => trim($_POST['title'] ?? ''),
                'excerpt' => trim($_POST['excerpt'] ?? ''),
            ]);
            $notice = 'Update published successfully.';
        } elseif ($action === 'update_update') {
            update_item('posts', (int) ($_POST['id'] ?? 0), [
                'title' => trim($_POST['title'] ?? ''),
                'excerpt' => trim($_POST['excerpt'] ?? ''),
            ]);
            $notice = 'Update changed successfully.';
        } elseif ($action === 'delete_update') {
            delete_item('posts', (int) ($_POST['id'] ?? 0));
            $notice = 'Update deleted successfully.';
        }
    } catch (Throwable $exception) {
        $error = 'Update action failed: ' . $exception->getMessage();
    }
}

$updates = read_collection('posts');
require __DIR__ . '/_header.php';
?>
  <section class="admin-panel">
    <h2>Add Product or Service Update</h2>
    <p class="admin-helper-text">Publish news, offers, process notes, or service announcements directly to the portfolio homepage.</p>
    <form method="post" class="admin-form admin-form-grid">
      <input type="hidden" name="action" value="add_update">
      <input name="title" placeholder="Update title" required>
      <textarea name="excerpt" placeholder="Short public update" required></textarea>
      <button class="btn btn-primary">Publish Update</button>
    </form>
  </section>

  <section class="admin-panel">
    <h2>Existing Updates</h2>
    <div class="admin-list">
      <?php if ($updates === []): ?>
        <p class="admin-empty-state">No updates have been published yet.</p>
      <?php endif; ?>
      <?php foreach($updates as $update): ?>
        <form method="post" class="admin-item admin-edit-card">
          <input type="hidden" name="id" value="<?= h($update['id']) ?>">
          <input type="hidden" name="action" value="update_update">
          <input name="title" value="<?= h($update['title']) ?>" required>
          <textarea name="excerpt" required><?= h($update['excerpt']) ?></textarea>
          <div class="admin-row-actions">
            <button class="btn btn-primary">Update</button>
            <button name="action" value="delete_update" class="btn btn-danger" formnovalidate>Delete</button>
          </div>
        </form>
      <?php endforeach; ?>
    </div>
  </section>
<?php require __DIR__ . '/_footer.php'; ?>
