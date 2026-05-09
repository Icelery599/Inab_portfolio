<?php
require_once __DIR__ . '/_bootstrap.php';

$activePage = 'dashboard';
$pageTitle = 'Admin Control Center';
$services = read_collection('services');
$projects = read_collection('projects');
$updates = read_collection('posts');
$messages = read_collection('messages');
$users = read_collection('users');

$activeUsers = array_filter($users, fn(array $user): bool => (int) ($user['is_active'] ?? 0) === 1);
$adminUsers = array_filter($users, fn(array $user): bool => ($user['role'] ?? '') === 'admin');
$editorUsers = array_filter($users, fn(array $user): bool => ($user['role'] ?? '') === 'editor');
$recentServices = array_slice($services, 0, 3);
$recentProjects = array_slice($projects, 0, 3);
$recentUpdates = array_slice($updates, 0, 3);
$recentMessages = array_slice($messages, 0, 3);
$recentUsers = array_slice($users, 0, 3);

function admin_metric_label(int $count, string $singular, string $plural): string {
    return $count . ' ' . ($count === 1 ? $singular : $plural);
}

require __DIR__ . '/_header.php';
?>
  <section class="admin-panel admin-overview-panel">
    <div>
      <p class="admin-eyebrow">Back-end command area</p>
      <h2>Control every major website collection from one admin folder</h2>
      <p>
        This dashboard is aligned with the database tables for public services, projects, and updates while keeping
        admin users and contact messages private inside the dashboard.
      </p>
    </div>
    <div class="admin-control-grid" aria-label="Admin database summary">
      <div class="admin-control-stat">
        <strong><?= count($users) ?></strong>
        <span>Total users</span>
        <small><?= admin_metric_label(count($adminUsers), 'admin', 'admins') ?> · <?= admin_metric_label(count($editorUsers), 'editor', 'editors') ?></small>
      </div>
      <div class="admin-control-stat">
        <strong><?= count($services) ?></strong>
        <span>Services</span>
        <small>Offerings controlled from services.php</small>
      </div>
      <div class="admin-control-stat">
        <strong><?= count($projects) ?></strong>
        <span>Projects</span>
        <small>Portfolio items controlled from projects.php</small>
      </div>
      <div class="admin-control-stat">
        <strong><?= count($updates) ?></strong>
        <span>Updates</span>
        <small>Homepage posts controlled from updates.php</small>
      </div>
      <div class="admin-control-stat">
        <strong><?= count($messages) ?></strong>
        <span>Messages</span>
        <small>Private contact form inbox</small>
      </div>
      <div class="admin-control-stat">
        <strong><?= count($activeUsers) ?></strong>
        <span>Active accounts</span>
        <small><?= admin_metric_label(count($users) - count($activeUsers), 'inactive user', 'inactive users') ?></small>
      </div>
    </div>
  </section>

  <section class="admin-panel">
    <h2>Management sections</h2>
    <p>Use these dedicated pages to manage public portfolio content, private messages, and admin accounts directly against the portfolio database.</p>
    <div class="admin-card-grid">
      <a class="admin-card-link" href="services.php">
        <strong>Manage Services</strong>
        <span><?= admin_metric_label(count($services), 'service', 'services') ?></span>
        <small>Add new offerings, edit descriptions and icons, or delete old services from the back end.</small>
      </a>
      <a class="admin-card-link" href="projects.php">
        <strong>Manage Projects</strong>
        <span><?= admin_metric_label(count($projects), 'project', 'projects') ?></span>
        <small>Add portfolio projects, update categories, images, and links, or remove archived work.</small>
      </a>
      <a class="admin-card-link" href="updates.php">
        <strong>Manage Updates</strong>
        <span><?= admin_metric_label(count($updates), 'update', 'updates') ?></span>
        <small>Publish homepage announcements that appear below the public portfolio grid.</small>
      </a>
      <a class="admin-card-link" href="messages.php">
        <strong>View Messages</strong>
        <span><?= admin_metric_label(count($messages), 'message', 'messages') ?></span>
        <small>Review private enquiries submitted by visitors through the contact form.</small>
      </a>
      <a class="admin-card-link" href="users.php">
        <strong>Manage Users</strong>
        <span><?= admin_metric_label(count($users), 'user', 'users') ?></span>
        <small>Create admin/editor accounts, update roles, reset passwords, or disable inactive users.</small>
      </a>
    </div>
  </section>

  <section class="admin-panel">
    <h2>Recent database activity</h2>
    <div class="admin-activity-grid">
      <div class="admin-activity-card">
        <h3>Latest services</h3>
        <?php if ($recentServices === []): ?>
          <p class="admin-empty-state">No services have been added yet.</p>
        <?php else: ?>
          <ul>
            <?php foreach ($recentServices as $service): ?>
              <li><strong><?= h($service['title']) ?></strong><span><?= h($service['icon'] ?? 'fa-code') ?></span></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
      <div class="admin-activity-card">
        <h3>Latest projects</h3>
        <?php if ($recentProjects === []): ?>
          <p class="admin-empty-state">No projects have been added yet.</p>
        <?php else: ?>
          <ul>
            <?php foreach ($recentProjects as $project): ?>
              <li><strong><?= h($project['title']) ?></strong><span><?= h($project['category']) ?></span></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
      <div class="admin-activity-card">
        <h3>Latest updates</h3>
        <?php if ($recentUpdates === []): ?>
          <p class="admin-empty-state">No updates have been published yet.</p>
        <?php else: ?>
          <ul>
            <?php foreach ($recentUpdates as $update): ?>
              <li><strong><?= h($update['title']) ?></strong><span>public homepage update</span></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
      <div class="admin-activity-card">
        <h3>Latest messages</h3>
        <?php if ($recentMessages === []): ?>
          <p class="admin-empty-state">No messages have been received yet.</p>
        <?php else: ?>
          <ul>
            <?php foreach ($recentMessages as $message): ?>
              <li><strong><?= h($message['name']) ?></strong><span><?= h($message['email']) ?></span></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
      <div class="admin-activity-card">
        <h3>Latest users</h3>
        <?php if ($recentUsers === []): ?>
          <p class="admin-empty-state">No users have been added yet.</p>
        <?php else: ?>
          <ul>
            <?php foreach ($recentUsers as $user): ?>
              <li><strong><?= h($user['username']) ?></strong><span><?= h($user['role']) ?> · <?= (int) $user['is_active'] === 1 ? 'active' : 'inactive' ?></span></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </section>
<?php require __DIR__ . '/_footer.php'; ?>
