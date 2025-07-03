<?php
session_start();

if (!isset($_SESSION['todos'])) {
    $_SESSION['todos'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['todo'])) {
        $_SESSION['todos'][] = htmlspecialchars($_POST['todo']);
    }
    if (isset($_POST['clear'])) {
        $_SESSION['todos'] = [];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple To-Do App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 2em;
            background-color: #f9f9f9;
        }
        h2 {
            color: #333;
        }
        form {
            margin-bottom: 1em;
        }
        input[type="text"] {
            padding: 0.5em;
            width: 250px;
        }
        input[type="submit"] {
            padding: 0.5em 1em;
            margin-left: 5px;
        }
        ul {
            list-style-type: circle;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <h2>📝 Simple PHP To-Do List</h2>
    <form method="POST">
        <input type="text" name="todo" placeholder="Enter a task" required />
        <input type="submit" value="Add Task" />
        <input type="submit" name="clear" value="Clear All" />
    </form>
    <ul>
        <?php foreach ($_SESSION['todos'] as $todo): ?>
            <li><?= $todo ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>

