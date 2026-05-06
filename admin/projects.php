<?php
require_once __DIR__ . '/_bootstrap.php';

$activePage = 'projects';
$pageTitle = 'Manage Projects';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'add_project') {
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
        }
    } catch (Throwable $exception) {
        $error = 'Project action failed: ' . $exception->getMessage();
    }
}

$projects = read_collection('projects');
require __DIR__ . '/_header.php';
?>
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
  </section>

  <section class="admin-panel">
    <h2>Existing Projects</h2>
    <div class="admin-list">
      <?php foreach($projects as $project): ?>
        <form method="post" class="admin-item admin-edit-card">
          <input type="hidden" name="id" value="<?= h($project['id']) ?>">
          <input type="hidden" name="action" value="update_project">
          <input name="title" value="<?= h($project['title']) ?>" required>
          <input name="category" value="<?= h($project['category']) ?>" required>
          <input name="image" value="<?= h($project['image']) ?>" required>
          <input name="url" value="<?= h($project['url'] ?? '') ?>">
          <textarea name="description" required><?= h($project['description']) ?></textarea>
          <div class="admin-row-actions">
            <button class="btn btn-primary">Update</button>
            <button name="action" value="delete_project" class="btn btn-danger" formnovalidate>Delete</button>
          </div>
        </form>
      <?php endforeach; ?>
    </div>
  </section>
<?php require __DIR__ . '/_footer.php'; ?>
