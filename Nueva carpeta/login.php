<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        $db = getDBConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php');
            exit;
        } else {
            echo "<p>Credenciales incorrectas.</p>";
        }
    } catch (Exception $e) {
        echo "<p>Error de base de datos: " . $e->getMessage() . "</p>";
    }
} else {
    header('Location: login.html');
    exit;
}

session_start(); 
?>