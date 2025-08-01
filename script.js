document.addEventListener('DOMContentLoaded', function () {
    const addButton = document.getElementById('add');
    const taskInput = document.getElementById('tareas');
    const taskList = document.getElementById('pendingTasks');
    const errorMessage = document.getElementById('errorMessage');

    loadTasks();

    addButton.addEventListener('click', addTask);

    taskInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addTask();
        }
    });

    async function addTask() {
        const taskText = taskInput.value.trim();
        if (taskText === '') {
            showError('Por favor, ingresa una tarea válida');
            return;
        }
        clearError();

        try {
            const response = await fetch('addTask.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ task: taskText })
            });

            const result = await response.json();

            if (result.success) {
                taskInput.value = '';
                await loadTasks();
            } else {
                showError(result.message || 'Error al agregar la tarea');
            }
        } catch (error) {
            console.error('Error al agregar tarea:', error);
            showError('Error de conexión. Por favor, intenta nuevamente.');
        }
    }

    async function loadTasks() {
        try {
            const response = await fetch('getTask.php');
            if (!response.ok) throw new Error('Error al cargar tareas');

            const tasks = await response.json();
            taskList.innerHTML = '';

            tasks.forEach(task => {
                const taskElement = createTaskElement(task);
                taskList.appendChild(taskElement);
            });

        } catch (error) {
            console.error('Error al cargar tareas:', error);
            showError('Error al cargar las tareas');
        }
    }

    function createTaskElement(task) {
        const li = document.createElement('li');
        li.className = 'task-item';

        const span = document.createElement('span');
        span.className = 'task-text';
        span.textContent = escapeHtml(task.text);

        const date = document.createElement('span');
        date.className = 'task-date';
        date.textContent = task.date || '';

        const editBtn = document.createElement('button');
        editBtn.textContent = 'Edit';
        editBtn.addEventListener('click', () => editTask(task.id, span, editBtn));

        const deleteBtn = document.createElement('button');
        deleteBtn.textContent = 'Delete';
        deleteBtn.addEventListener('click', () => deleteTask(task.id));

        li.appendChild(span);
        li.appendChild(date);
        li.appendChild(editBtn);
        li.appendChild(deleteBtn);

        return li;
    }

    function editTask(id, span, button) {
        const currentText = span.textContent;
        const input = document.createElement('input');
        input.type = 'text';
        input.value = currentText;

        const saveBtn = document.createElement('button');
        saveBtn.textContent = 'Save';

        const parent = span.parentElement;
        parent.replaceChild(input, span);
        parent.replaceChild(saveBtn, button);

        saveBtn.addEventListener('click', async () => {
            const newText = input.value.trim();
            if (newText === '') {
                showError('La tarea no puede estar vacía');
                return;
            }

            try {
                const response = await fetch('editTask.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id, text: newText })
                });

                const result = await response.json();

                if (result.success) {
                    await loadTasks();
                } else {
                    showError('Error al editar la tarea');
                }
            } catch (error) {
                console.error('Error al editar tarea:', error);
                showError('Error de conexión al editar la tarea');
            }
        });
    }

    function showError(message) {
        if (errorMessage) {
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
        } else {
            alert(message);
        }
    }

    function clearError() {
        if (errorMessage) {
            errorMessage.style.display = 'none';
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    window.deleteTask = async function (taskId) {
        if (confirm('¿Estás seguro de que quieres eliminar esta tarea?')) {
            try {
                const response = await fetch('deleteTask.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: taskId })
                });

                const result = await response.json();

                if (result.success) {
                    await loadTasks();
                } else {
                    showError('Error al eliminar la tarea');
                }
            } catch (error) {
                console.error('Error al eliminar tarea:', error);
                showError('Error de conexión al eliminar la tarea');
            }
        }
    };
});
