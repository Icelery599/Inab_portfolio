<?php

declare(strict_types=1);

require_once __DIR__ . '/data_store.php';

session_start();
initialize_database();

function is_admin_logged_in(): bool {
    return !empty($_SESSION['admin_user_id']);
}

function current_admin_user(): ?array {
    if (empty($_SESSION['admin_user_id'])) {
        return null;
    }

    return find_user_by_id((int) $_SESSION['admin_user_id']);
}

function require_admin(): void {
    if (!is_admin_logged_in()) {
        header('Location: login.php');
        exit;
    }

    $user = current_admin_user();
    if ($user === null || (int) $user['is_active'] !== 1 || $user['role'] !== 'admin') {
        session_destroy();
        header('Location: login.php');
        exit;
    }
}

function attempt_login(string $username, string $password): bool {
    $user = find_user_by_username($username);

    if ($user === null || (int) $user['is_active'] !== 1 || !password_verify($password, $user['password_hash'])) {
        return false;
    }

    $_SESSION['admin_user_id'] = (int) $user['id'];
    $_SESSION['admin_username'] = $user['username'];
    $_SESSION['admin_role'] = $user['role'];

    return true;
}
