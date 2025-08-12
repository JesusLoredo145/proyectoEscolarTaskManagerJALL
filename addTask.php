<?php
require_once 'db.php'; // incluye el archivo con la conexion a la base de datos
session_start(); // inicia la sesion para acceder a variables de sesion
setJSONHeaders(); // configura las cabeceras para devolver respuestas en formato json

try {
    if (!isset($_SESSION['user_id'])) { // verifica si no hay usuario autenticado
        throw new Exception("Usuario no autenticado"); // lanza error si no esta logueado
    }

    $input = json_decode(file_get_contents("php://input"), true); // obtiene y decodifica los datos enviados en formato json

    if (!isset($input['task']) || trim($input['task']) === '') { // valida que el texto de la tarea exista y no este vacio
        throw new Exception("El texto de la tarea no puede estar vacío"); // lanza error si la validacion falla
    }

    $task = trim($input['task']); // limpia espacios extra en el texto de la tarea
    $userId = $_SESSION['user_id']; // obtiene el id del usuario desde la sesion

    $db = getDBConnection(); // obtiene la conexion a la base de datos
    $query = "INSERT INTO tasks (user_id, text) VALUES (:user_id, :text)"; // consulta para insertar la nueva tarea
    $stmt = $db->prepare($query); // prepara la consulta para evitar inyeccion sql
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT); // vincula el id de usuario al parametro de la consulta
    $stmt->bindParam(':text', $task); // vincula el texto de la tarea al parametro de la consulta
    $stmt->execute(); // ejecuta la consulta

    sendJSONResponse(['success' => true]); // envia respuesta json indicando exito

} catch (Exception $e) { // si ocurre algun error
    handleError($e, "Error al agregar la tarea"); // maneja y envia el error en formato json
}
?>
