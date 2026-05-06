<?php
require_once __DIR__ . '/_bootstrap.php';

$activePage = 'services';
$pageTitle = 'Manage Services';

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
        }
    } catch (Throwable $exception) {
        $error = 'Service action failed: ' . $exception->getMessage();
    }
}

$services = read_collection('services');
require __DIR__ . '/_header.php';
?>
  <section class="admin-panel">
    <h2>Add Service</h2>
    <form method="post" class="admin-form admin-form-grid">
      <input type="hidden" name="action" value="add_service">
      <input name="title" placeholder="Service title" required>
      <input name="icon" placeholder="Font Awesome icon e.g. fa-code" value="fa-code">
      <textarea name="description" placeholder="Description" required></textarea>
      <button class="btn btn-primary">Add Service</button>
    </form>
  </section>

  <section class="admin-panel">
    <h2>Existing Services</h2>
    <div class="admin-list">
      <?php foreach($services as $service): ?>
        <form method="post" class="admin-item admin-edit-card">
          <input type="hidden" name="id" value="<?= h($service['id']) ?>">
          <input type="hidden" name="action" value="update_service">
          <input name="title" value="<?= h($service['title']) ?>" required>
          <input name="icon" value="<?= h($service['icon'] ?? 'fa-code') ?>">
          <textarea name="description" required><?= h($service['description']) ?></textarea>
          <div class="admin-row-actions">
            <button class="btn btn-primary">Update</button>
            <button name="action" value="delete_service" class="btn btn-danger" formnovalidate>Delete</button>
          </div>
        </form>
      <?php endforeach; ?>
    </div>
  </section>
<?php require __DIR__ . '/_footer.php'; ?>
