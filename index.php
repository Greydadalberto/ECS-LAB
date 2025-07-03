<?php
session_start();

if (!isset($_SESSION['todos'])) {
    $_SESSION['todos'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add Task
    if (isset($_POST['todo']) && !empty(trim($_POST['todo']))) {
        $_SESSION['todos'][] = htmlspecialchars(trim($_POST['todo']));
    }

    // Clear All Tasks
    if (isset($_POST['clear'])) {
        $_SESSION['todos'] = [];
    }

    // Delete Single Task
    if (isset($_POST['delete'])) {
        $index = $_POST['delete'];
        if (isset($_SESSION['todos'][$index])) {
            array_splice($_SESSION['todos'], $index, 1);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>📝 PHP To-Do List</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f7f9;
            color: #333;
            padding: 2em;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 0.5em;
        }
        .todo-container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 1.5em;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        form {
            display: flex;
            gap: 0.5em;
            margin-bottom: 1.2em;
        }
        input[type="text"] {
            flex: 1;
            padding: 0.5em;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type="submit"] {
            background: #3498db;
            color: white;
            border: none;
            padding: 0.5em 1em;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }
        input[type="submit"]:hover {
            background: #2980b9;
        }
        ul {
            padding-left: 0;
            list-style: none;
        }
        li {
            background: #ecf0f1;
            margin-bottom: 0.5em;
            padding: 0.6em;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .delete-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 0.3em 0.6em;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="todo-container">
        <h2>📝 My To-Do List</h2>
        <form method="POST">
            <input type="text" name="todo" placeholder="Enter a task..." required />
            <input type="submit" value="Add" />
            <input type="submit" name="clear" value="Clear All" />
        </form>

        <ul>
            <?php foreach ($_SESSION['todos'] as $index => $todo): ?>
                <li>
                    <?= $todo ?>
                    <form method="POST" style="margin: 0;">
                        <input type="hidden" name="delete" value="<?= $index ?>">
                        <button class="delete-btn" type="submit">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>

