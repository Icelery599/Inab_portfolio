<?php
/** @var array|null $currentUser */
/** @var string $activePage */
/** @var string $pageTitle */
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= h($pageTitle ?? 'Admin Dashboard') ?></title>
  <link rel="stylesheet" href="../stylez.css">
</head>
<body>
<main class="container admin-dashboard">
  <div class="admin-header">
    <h1><?= h($pageTitle ?? 'Admin Dashboard') ?></h1>
    <p>Manage users, projects, and services from dedicated admin pages.</p>
    <div class="admin-actions">
      Signed in as <?= h($currentUser['username'] ?? 'admin') ?> ·
      <a href="<?= admin_page_url('dashboard') ?>" class="<?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>">Dashboard</a> ·
      <a href="<?= admin_page_url('services') ?>" class="<?= ($activePage ?? '') === 'services' ? 'active' : '' ?>">Services</a> ·
      <a href="<?= admin_page_url('projects') ?>" class="<?= ($activePage ?? '') === 'projects' ? 'active' : '' ?>">Projects</a> ·
      <a href="<?= admin_page_url('users') ?>" class="<?= ($activePage ?? '') === 'users' ? 'active' : '' ?>">Users</a> ·
      <a href="../index.php">View Site</a> ·
      <a href="../logout.php">Logout</a>
    </div>
  </div>

  <?php if ($notice): ?><div class="admin-alert success"><?= h($notice) ?></div><?php endif; ?>
  <?php if ($error): ?><div class="admin-alert error"><?= h($error) ?></div><?php endif; ?>
