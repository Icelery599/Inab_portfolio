<?php

declare(strict_types=1);

session_start();

const ADMIN_USER = 'admin';
const ADMIN_PASS = 'admin123';

function is_admin_logged_in(): bool {
    return !empty($_SESSION['admin_logged_in']);
}

function require_admin(): void {
    if (!is_admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
