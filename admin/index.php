<?php
require_once __DIR__ . '/_bootstrap.php';

$activePage = 'dashboard';
$pageTitle = 'Admin Dashboard';
$services = read_collection('services');
$projects = read_collection('projects');
$users = read_collection('users');

require __DIR__ . '/_header.php';
?>
  <section class="admin-panel">
    <h2>Content management</h2>
    <p>Use these admin sections to add, update, and delete the portfolio content shown on the public site.</p>
    <div class="admin-card-grid">
      <a class="admin-card-link" href="services.php">
        <strong>Services</strong>
        <span><?= count($services) ?> service<?= count($services) === 1 ? '' : 's' ?></span>
        <small>Add new offerings, edit descriptions, or delete old services.</small>
      </a>
      <a class="admin-card-link" href="projects.php">
        <strong>Projects</strong>
        <span><?= count($projects) ?> project<?= count($projects) === 1 ? '' : 's' ?></span>
        <small>Add portfolio projects, update links, or remove archived work.</small>
      </a>
      <a class="admin-card-link" href="users.php">
        <strong>Users</strong>
        <span><?= count($users) ?> user<?= count($users) === 1 ? '' : 's' ?></span>
        <small>Create admin/editor accounts, update roles, or delete inactive users.</small>
      </a>
    </div>
  </section>
<?php require __DIR__ . '/_footer.php'; ?>
