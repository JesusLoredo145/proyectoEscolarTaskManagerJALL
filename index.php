<?php
require_once 'session_check.php';
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager by JALL Software</title>
    <link rel="stylesheet" href="styles.css">
    </head>
    <body>
    <div class="pagina">
        <header>
        <div class="cabezaPagina">
            <h1>Task Manager</h1>
            <nav>
            <ul class="menu">
                <li><a href="login.html">Login</a></li>
                <li><a href="about.html" target="_blank">About</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
            </nav>
        </div>
        </header>
        
        <section>
        <div class="cuadroCentral">  
            <form id="taskInput">
            <h2>Write your daily tasks</h2>
            <input type="text" placeholder="Type a new task" id="tareas">
            <button type="button" id="add">Add</button>
            </form>
            
            <!-- Agregado: Div para mostrar mensajes de error -->
            <div id="errorMessage" class="error-message" style="display: none;"></div>
            
            <div class="taskList">  
            <h2>Tasks List</h2>
            <ul id="pendingTasks">
                <!-- Las tareas se cargarán aquí dinámicamente -->
            </ul>
            </div>
        </div>
        </section>

        <footer>
        <p>Something will be place here</p>
        </footer>
    </div>
    <script src="script.js"></script>
    </body>
    </html>
