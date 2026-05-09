<?php
require_once __DIR__ . '/_bootstrap.php';

$activePage = 'messages';
$pageTitle = 'Contact Messages';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_message') {
    try {
        delete_item('messages', (int) ($_POST['id'] ?? 0));
        $notice = 'Message deleted successfully.';
    } catch (Throwable $exception) {
        $error = 'Message action failed: ' . $exception->getMessage();
    }
}

$messages = read_collection('messages');
require __DIR__ . '/_header.php';
?>
  <section class="admin-panel">
    <h2>Inbox</h2>
    <p class="admin-helper-text">Messages submitted from the public contact form are private to the admin dashboard and never appear on the portfolio page.</p>
    <div class="admin-list">
      <?php if ($messages === []): ?>
        <p class="admin-empty-state">No contact messages have been received yet.</p>
      <?php endif; ?>
      <?php foreach($messages as $message): ?>
        <article class="admin-item message-card">
          <div>
            <h3><?= h($message['name']) ?></h3>
            <a href="mailto:<?= h($message['email']) ?>"><?= h($message['email']) ?></a>
            <small><?= h($message['created_at'] ?? '') ?></small>
          </div>
          <p><?= nl2br(h($message['message'])) ?></p>
          <form method="post">
            <input type="hidden" name="id" value="<?= h($message['id']) ?>">
            <button name="action" value="delete_message" class="btn btn-danger" formnovalidate>Delete Message</button>
          </form>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
<?php require __DIR__ . '/_footer.php'; ?>
