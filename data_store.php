<?php

declare(strict_types=1);

const DATA_DIR = __DIR__ . '/data';

function ensure_data_dir(): void {
    if (!is_dir(DATA_DIR)) {
        mkdir(DATA_DIR, 0777, true);
    }
}

function data_path(string $name): string {
    ensure_data_dir();
    return DATA_DIR . '/' . $name . '.json';
}

function read_collection(string $name): array {
    $file = data_path($name);
    if (!file_exists($file)) {
        return [];
    }
    $raw = file_get_contents($file);
    if ($raw === false || trim($raw) === '') {
        return [];
    }
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

function write_collection(string $name, array $items): void {
    $file = data_path($name);
    file_put_contents($file, json_encode(array_values($items), JSON_PRETTY_PRINT));
}

function add_item(string $name, array $payload): void {
    $items = read_collection($name);
    $payload['id'] = uniqid('', true);
    $payload['created_at'] = gmdate('c');
    $items[] = $payload;
    write_collection($name, $items);
}

function delete_item(string $name, string $id): void {
    $items = read_collection($name);
    $items = array_filter($items, fn($item) => ($item['id'] ?? '') !== $id);
    write_collection($name, array_values($items));
}
