<?php
require_once 'db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['task']) || empty(trim($input['task']))) {
        throw new Exception('La tarea no puede estar vacía');
    }

    $task = trim($input['task']);

    if (strlen($task) > 255) {
        throw new Exception('La tarea es demasiado larga (máximo 255 caracteres)');
    }

    $pdo = getDBConnection();

    $stmt = $pdo->prepare("INSERT INTO tasks (text, created_at) VALUES (?, NOW())");
    $stmt->execute([$task]);

    $taskId = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Tarea agregada exitosamente',
        'task_id' => $taskId
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
