<?php

declare(strict_types=1);

const DB_HOST = '127.0.0.1';
const DB_PORT = '3306';
const DB_NAME = 'inab_portfolio';
const DB_USER = 'root';
const DB_PASS = '';

function env_value(string $key, string $default): string {
    $value = getenv($key);
    return $value === false || $value === '' ? $default : $value;
}

function db(): PDO {
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = env_value('DB_HOST', DB_HOST);
    $port = env_value('DB_PORT', DB_PORT);
    $name = env_value('DB_NAME', DB_NAME);
    $user = env_value('DB_USER', DB_USER);
    $pass = env_value('DB_PASS', DB_PASS);
    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}

function initialize_database(): void {
    $schema = file_get_contents(__DIR__ . '/database.sql');
    if ($schema === false) {
        throw new RuntimeException('Unable to read database.sql.');
    }

    db()->exec($schema);
    seed_default_admin();
}

function seed_default_admin(): void {
    $stmt = db()->prepare('SELECT COUNT(*) FROM users WHERE role = :role');
    $stmt->execute(['role' => 'admin']);

    if ((int) $stmt->fetchColumn() > 0) {
        return;
    }

    create_user('admin', 'admin@inab.local', 'admin123', 'admin', true);
}

function read_collection(string $name): array {
    $allowedTables = ['services', 'projects', 'posts', 'messages', 'users'];
    if (!in_array($name, $allowedTables, true)) {
        throw new InvalidArgumentException('Unsupported collection requested.');
    }

    $stmt = db()->query("SELECT * FROM {$name} ORDER BY created_at DESC, id DESC");
    return $stmt->fetchAll();
}


function read_public_collection(string $name): array {
    $allowedTables = ['services', 'projects', 'posts'];
    if (!in_array($name, $allowedTables, true)) {
        throw new InvalidArgumentException('Unsupported public collection requested.');
    }

    return read_collection($name);
}

function public_project_categories(array $projects): array {
    $categories = [];
    foreach ($projects as $project) {
        $category = trim((string) ($project['category'] ?? ''));
        if ($category === '') {
            continue;
        }

        $slug = category_slug($category);
        $categories[$slug] = category_label($category);
    }

    asort($categories);
    return $categories;
}

function category_slug(string $category): string {
    $slug = strtolower(trim($category));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
    $slug = trim($slug, '-');

    return $slug === '' ? 'general' : $slug;
}

function category_label(string $category): string {
    $label = trim(str_replace(['-', '_'], ' ', $category));
    return $label === '' ? 'General' : ucwords($label);
}

function whatsapp_chat_url(string $message): string {
    $number = preg_replace('/\D+/', '', env_value('WHATSAPP_NUMBER', '')) ?? '';
    $baseUrl = $number === '' ? 'https://wa.me/' : 'https://wa.me/' . $number;

    return $baseUrl . '?text=' . rawurlencode($message);
}

function add_item(string $name, array $payload): int {
    $columnsByTable = [
        'services' => ['title', 'description', 'icon'],
        'projects' => ['title', 'category', 'description', 'image', 'url'],
        'posts' => ['title', 'excerpt'],
        'messages' => ['name', 'email', 'message'],
    ];

    if (!isset($columnsByTable[$name])) {
        throw new InvalidArgumentException('Unsupported collection requested.');
    }

    $columns = array_values(array_intersect($columnsByTable[$name], array_keys($payload)));
    if ($columns === []) {
        throw new InvalidArgumentException('No valid fields supplied.');
    }

    $placeholders = array_map(fn(string $column): string => ':' . $column, $columns);
    $sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', $name, implode(', ', $columns), implode(', ', $placeholders));
    $params = [];
    foreach ($columns as $column) {
        $params[$column] = $payload[$column];
    }

    $stmt = db()->prepare($sql);
    $stmt->execute($params);

    return (int) db()->lastInsertId();
}

function update_item(string $name, int $id, array $payload): void {
    $columnsByTable = [
        'services' => ['title', 'description', 'icon'],
        'projects' => ['title', 'category', 'description', 'image', 'url'],
        'posts' => ['title', 'excerpt'],
    ];

    if (!isset($columnsByTable[$name])) {
        throw new InvalidArgumentException('Unsupported collection requested.');
    }

    $columns = array_values(array_intersect($columnsByTable[$name], array_keys($payload)));
    if ($columns === []) {
        return;
    }

    $assignments = array_map(fn(string $column): string => "{$column} = :{$column}", $columns);
    $params = ['id' => $id];
    foreach ($columns as $column) {
        $params[$column] = $payload[$column];
    }

    $stmt = db()->prepare(sprintf('UPDATE %s SET %s WHERE id = :id', $name, implode(', ', $assignments)));
    $stmt->execute($params);
}

function delete_item(string $name, int $id): void {
    $allowedTables = ['services', 'projects', 'posts', 'messages'];
    if (!in_array($name, $allowedTables, true)) {
        throw new InvalidArgumentException('Unsupported collection requested.');
    }

    $stmt = db()->prepare("DELETE FROM {$name} WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

function find_user_by_username(string $username): ?array {
    $stmt = db()->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    return $user === false ? null : $user;
}

function find_user_by_id(int $id): ?array {
    $stmt = db()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch();

    return $user === false ? null : $user;
}

function create_user(string $username, string $email, string $password, string $role = 'editor', bool $active = true): int {
    $stmt = db()->prepare(
        'INSERT INTO users (username, email, password_hash, role, is_active) VALUES (:username, :email, :password_hash, :role, :is_active)'
    );
    $stmt->execute([
        'username' => $username,
        'email' => $email,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'role' => $role,
        'is_active' => $active ? 1 : 0,
    ]);

    return (int) db()->lastInsertId();
}

function update_user(int $id, string $username, string $email, string $role, bool $active, ?string $password = null): void {
    $params = [
        'id' => $id,
        'username' => $username,
        'email' => $email,
        'role' => $role,
        'is_active' => $active ? 1 : 0,
    ];
    $passwordSql = '';

    if ($password !== null && $password !== '') {
        $passwordSql = ', password_hash = :password_hash';
        $params['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
    }

    $stmt = db()->prepare(
        "UPDATE users SET username = :username, email = :email, role = :role, is_active = :is_active{$passwordSql} WHERE id = :id"
    );
    $stmt->execute($params);
}

function delete_user(int $id): void {
    $stmt = db()->prepare('DELETE FROM users WHERE id = :id');
    $stmt->execute(['id' => $id]);
}
