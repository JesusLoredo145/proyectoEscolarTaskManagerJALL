<?php
require_once 'db.php';
setJSONHeaders();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception('Método no permitido');
    }
    
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT id, text, created_at FROM tasks ORDER BY created_at DESC");
    $stmt->execute();
    
    $tasks = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tasks[] = [
            'id' => (int)$row['id'],
            'text' => $row['text'],
            'date' => date('d/m/Y', strtotime($row['created_at'])),
            'created_at' => $row['created_at']
        ];
    }
    
    sendJSONResponse($tasks);
} catch (Exception $e) {
    handleError($e);
}
?>