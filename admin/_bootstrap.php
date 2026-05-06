<?php

declare(strict_types=1);

require_once __DIR__ . '/../auth.php';
require_admin();

$currentUser = current_admin_user();
$notice = '';
$error = '';

function h(mixed $value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function checked(bool $value): string {
    return $value ? 'checked' : '';
}

function selected(string $actual, string $expected): string {
    return $actual === $expected ? 'selected' : '';
}

function admin_page_url(string $page): string {
    return $page === 'dashboard' ? 'index.php' : $page . '.php';
}
