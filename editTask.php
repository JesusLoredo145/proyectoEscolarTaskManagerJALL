<?php
require_once 'db.php';

setJSONHeaders();

try {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['id']) || !isset($input['text'])) {
        throw new Exception("Datos incompletos");
    }

    $id = (int) $input['id'];
    $text = trim($input['text']);

    if ($text === '') {
        throw new Exception("El texto de la tarea no puede estar vacío");
    }

    $db = getDBConnection();
    $query = "UPDATE tasks SET text = :text WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':text', $text);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    sendJSONResponse(['success' => true]);

} catch (Exception $e) {
    handleError($e, "Error al editar la tarea");
}
