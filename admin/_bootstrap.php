<?php

declare(strict_types=1);

require_once __DIR__ . '/../auth.php';
require_admin();

$currentUser = current_admin_user();
$notice = '';
$error = '';

function h($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function checked($value): string {
    return (bool) $value ? 'checked' : '';
}

function selected($actual, string $expected): string {
    return (string) $actual === $expected ? 'selected' : '';
}

function admin_role_from_post(): string {
    return ($_POST['role'] ?? '') === 'admin' ? 'admin' : 'editor';
}

function admin_page_url(string $page): string {
    return $page === 'dashboard' ? 'index.php' : $page . '.php';
}
