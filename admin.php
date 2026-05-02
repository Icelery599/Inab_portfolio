<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/data_store.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add_service') {
        add_item('services', ['title' => trim($_POST['title'] ?? ''), 'description' => trim($_POST['description'] ?? '')]);
    } elseif ($action === 'delete_service') {
        delete_item('services', $_POST['id'] ?? '');
    } elseif ($action === 'add_project') {
        add_item('projects', [
            'title' => trim($_POST['title'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'image' => trim($_POST['image'] ?? ''),
            'url' => trim($_POST['url'] ?? ''),
        ]);
    } elseif ($action === 'delete_project') {
        delete_item('projects', $_POST['id'] ?? '');
    } elseif ($action === 'add_post') {
        add_item('posts', ['title' => trim($_POST['title'] ?? ''), 'excerpt' => trim($_POST['excerpt'] ?? '')]);
    } elseif ($action === 'delete_post') {
        delete_item('posts', $_POST['id'] ?? '');
    }
    header('Location: admin.php');
    exit;
}
$services = read_collection('services');
$projects = read_collection('projects');
$posts = read_collection('posts');
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
    <p>Manage content with a clean workflow.</p>
    <div class="admin-actions"><a href="index.php">View Site</a> · <a href="logout.php">Logout</a></div>
  </div>

  <section class="admin-panel">
    <h2>Services</h2>
    <form method="post" class="admin-form">
      <input type="hidden" name="action" value="add_service">
      <input name="title" placeholder="Service title" required>
      <textarea name="description" placeholder="Description" required></textarea>
      <button class="btn btn-primary">Add Service</button>
    </form>
    <div class="admin-list">
      <?php foreach($services as $s): ?>
        <div class="admin-item"><strong><?= htmlspecialchars($s['title']) ?></strong> - <?= htmlspecialchars($s['description']) ?><form method="post" style="display:inline"><input type="hidden" name="action" value="delete_service"><input type="hidden" name="id" value="<?= htmlspecialchars($s['id']) ?>"><button>Delete</button></form></div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="admin-panel">
    <h2>Projects</h2>
    <form method="post" class="admin-form">
      <input type="hidden" name="action" value="add_project">
      <input name="title" placeholder="Project title" required>
      <input name="category" placeholder="Category e.g. web/branding" required>
      <input name="image" placeholder="Image URL" required>
      <input name="url" placeholder="Project URL (https://...)" required>
      <textarea name="description" placeholder="Description" required></textarea>
      <button class="btn btn-primary">Add Project</button>
    </form>
    <div class="admin-list">
      <?php foreach($projects as $p): ?>
        <div class="admin-item"><strong><?= htmlspecialchars($p['title']) ?></strong> (<?= htmlspecialchars($p['category']) ?>)<?php if (!empty($p['url'])): ?> · <a href="<?= htmlspecialchars($p['url']) ?>" target="_blank" rel="noopener">Visit</a><?php endif; ?><form method="post" style="display:inline"><input type="hidden" name="action" value="delete_project"><input type="hidden" name="id" value="<?= htmlspecialchars($p['id']) ?>"><button>Delete</button></form></div>
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
        <div class="admin-item"><strong><?= htmlspecialchars($post['title']) ?></strong><form method="post" style="display:inline"><input type="hidden" name="action" value="delete_post"><input type="hidden" name="id" value="<?= htmlspecialchars($post['id']) ?>"><button>Delete</button></form></div>
      <?php endforeach; ?>
    </div>
  </section>
</main>
</body>
</html>
