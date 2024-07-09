<?php

require 'db_connection.php';

                                                // Verifie si une soumission de formulaire a ete faite.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $title = $_POST['title'];
        $description = $_POST['description'] ?? '';

        $stmt = $pdo->prepare("INSERT INTO tasks (title, description) VALUES (?, ?)");
        $stmt->execute([$title, $description]);
       } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $is_done = isset($_POST['is_done']) ? 1 : 0;

        $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, is_done = ? WHERE id = ?");
        $stmt->execute([$title, $description, $is_done, $id]);
        header('Location: index.php');
        exit();
    }
}

                                     // Verifie si une demande de suppression a ete faite.
 if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: index.php');
    exit();
}

                                // Verifie si une demande de modification a ete faite.
 if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
}

                            // Verifie si une demande pour marquer une tache comme terminee a ete faite.
if (isset($_GET['mark_done'])) {
    $id = $_GET['mark_done'];
    $stmt = $pdo->prepare("UPDATE tasks SET is_done = 1 WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: index.php');
    exit();
}

                        // Recupere toutes les taches de la base de donnees
$sql = "SELECT * FROM tasks ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>To-Do List</h1>

             
        <section class="form-section">
            <h2>Ajouter un Nouveau Task</h2>
            <form action="index.php" method="post">
                <input type="hidden" name="id" value="<?= htmlspecialchars($task['id'] ?? '') ?>">
                <label for="title">Titre:</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($task['title'] ?? '') ?>" required>
                <br>
                <label for="description">Description:</label>
                <textarea id="description" name="description"><?= htmlspecialchars($task['description'] ?? '') ?></textarea>
                <br>
                <?php if (isset($task)): ?>
                <label>
                <input type="checkbox" name="is_done" <?= $task['is_done'] ? 'checked' : '' ?>>
                 Fait
                </label>
                <br>
            <button type="submit" name="update">Mettre à Jour</button>
            <?php else: ?>
            <button type="submit" name="add">Ajouter</button>
          <?php endif; ?>
            </form>
        </section>

        <!-- liste avec nouvelles tasks -->
        <section class="tasks-section">
            <h2>Liste des Tasks</h2>
            <?php if (count($tasks) > 0): ?>
                <?php foreach ($tasks as $task): ?>
                <div class="task <?= $task['is_done'] ? 'completed' : '' ?>">
                <h3><?= htmlspecialchars($task['title']) ?></h3>
                <p><?= htmlspecialchars($task['description']) ?></p>
                <p><strong>Créé le:</strong> <?= $task['created_at'] ?></p>
            <div class="actions">
         <a href="?edit=<?= $task['id'] ?>" class="btn">Modifier</a>
        <a href="?delete=<?= $task['id'] ?>" class="btn delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')">Supprimer</a>
                <?php if (!$task['is_done']): ?>
                 <a href="?mark_done=<?= $task['id'] ?>" class="btn mark-done">Marquer comme fait</a>
                 <?php endif; ?>
             </div>
         </div>
        <?php endforeach; ?>
     <?php else: ?>
     <p>Aucun task trouvé.</p>
     <?php endif; ?>
     </section>
    </div>
</body>
</html>
