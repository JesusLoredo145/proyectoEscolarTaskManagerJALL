<?php
require_once 'db.php';
setJSONHeaders();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['id']) || !is_numeric($input['id'])) {
        throw new Exception('ID de tarea inválido');
    }
    
    $taskId = (int)$input['id'];
    $pdo = getDBConnection();
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE id = ?");
    $stmt->execute([$taskId]);
    
    if ($stmt->fetchColumn() == 0) {
        throw new Exception('La tarea no existe');
    }
    
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$taskId]);
    
    if ($stmt->rowCount() > 0) {
        sendJSONResponse([
            'success' => true,
            'message' => 'Tarea eliminada exitosamente'
        ]);
    } else {
        throw new Exception('No se pudo eliminar la tarea');
    }
} catch (Exception $e) {
    handleError($e);
}
?>